<?php

namespace Michalsn\CodeIgniterHtmx\Debug;

use CodeIgniter\Debug\Toolbar as BaseToolbar;
use CodeIgniter\HTTP\DownloadResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Config\Services;
use Config\Toolbar as ToolbarConfig;
use Kint\Kint;
use Michalsn\CodeIgniterHtmx\HTTP\IncomingRequest;
use Michalsn\CodeIgniterHtmx\HTTP\Response;

class Toolbar extends BaseToolbar
{
    /**
     * Prepare for debugging.
     *
     * @psalm-suppress UndefinedClass
     */
    public function prepare(?RequestInterface $request = null, ?ResponseInterface $response = null)
    {
        /**
         * @var IncomingRequest|null $request
         * @var Response|null        $response
         */
        if (CI_DEBUG && ! is_cli()) {
            $app = Services::codeigniter();

            $request ??= Services::request();
            $response ??= Services::response();

            // Disable the toolbar for downloads
            if ($response instanceof DownloadResponse) {
                return;
            }

            $toolbar = Services::toolbar(config(ToolbarConfig::class));
            $stats   = $app->getPerformanceStats();
            $data    = $toolbar->run(
                $stats['startTime'],
                $stats['totalTime'],
                $request,
                $response
            );

            helper('filesystem');

            // Updated to microtime() so we can get history
            $time = sprintf('%.6f', Time::now()->format('U.u'));

            if (! is_dir(WRITEPATH . 'debugbar')) {
                mkdir(WRITEPATH . 'debugbar', 0777);
            }

            write_file(WRITEPATH . 'debugbar/debugbar_' . $time . '.json', $data, 'w+');

            $format = $response->getHeaderLine('content-type');

            // Non-HTML formats should not include the debugbar
            // then we send headers saying where to find the debug data
            // for this response
            if ($request->isAJAX() || $request->isHtmx() || ! str_contains($format, 'html')) {
                $response->setHeader('Debugbar-Time', "{$time}")
                    ->setHeader('Debugbar-Link', site_url("?debugbar_time={$time}"));

                return;
            }

            $oldKintMode        = Kint::$mode_default;
            Kint::$mode_default = Kint::MODE_RICH;
            $kintScript         = @Kint::dump('');
            Kint::$mode_default = $oldKintMode;
            $kintScript         = substr($kintScript, 0, strpos($kintScript, '</style>') + 8);
            $kintScript         = ($kintScript === '0') ? '' : $kintScript;

            $script = PHP_EOL
                . '<script ' . csp_script_nonce() . ' id="debugbar_loader" '
                . 'data-time="' . $time . '" '
                . 'src="' . site_url() . '?debugbar"></script>'
                . '<script ' . csp_script_nonce() . ' id="debugbar_dynamic_script"></script>'
                . '<style ' . csp_style_nonce() . ' id="debugbar_dynamic_style"></style>'
                . $kintScript
                . PHP_EOL;

            if (str_contains($response->getBody(), '<head>')) {
                $response->setBody(
                    preg_replace(
                        '/<head>/',
                        '<head>' . $script,
                        $response->getBody(),
                        1
                    )
                );

                return;
            }

            $response->appendBody($script);
        }
    }
}
