<?php

namespace Michalsn\CodeIgniterHtmx\Config;

use CodeIgniter\Config\BaseService;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\UserAgent;
use Config\App;
use Config\Services as AppServices;
use Config\Toolbar as ToolbarConfig;
use Config\View as ViewConfig;
use Michalsn\CodeIgniterHtmx\Debug\Toolbar;
use Michalsn\CodeIgniterHtmx\HTTP\IncomingRequest;
use Michalsn\CodeIgniterHtmx\HTTP\RedirectResponse;
use Michalsn\CodeIgniterHtmx\HTTP\Response;
use Michalsn\CodeIgniterHtmx\View\View;

class Services extends BaseService
{
    /**
     * The Renderer class is the class that actually displays a file to the user.
     * The default View class within CodeIgniter is intentionally simple, but this
     * service could easily be replaced by a template engine if the user needed to.
     *
     * @return View
     */
    public static function renderer(?string $viewPath = null, ?ViewConfig $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('renderer', $viewPath, $config);
        }

        $viewPath = $viewPath ?: config('Paths')->viewDirectory;
        $config ??= config('View');

        return new View($config, $viewPath, AppServices::locator(), CI_DEBUG, AppServices::logger());
    }

    /**
     * Returns the current Request object.
     *
     * createRequest() injects IncomingRequest or CLIRequest.
     *
     * @deprecated The parameter $config and $getShared are deprecated.
     */
    public static function request(?App $config = null, bool $getShared = true): CLIRequest|IncomingRequest
    {
        if ($getShared) {
            return static::getSharedInstance('request', $config);
        }

        // @TODO remove the following code for backward compatibility
        return static::incomingrequest($config, $getShared);
    }

    /**
     * The IncomingRequest class models an HTTP request.
     *
     * @return IncomingRequest
     *
     * @internal
     */
    public static function incomingrequest(?App $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('request', $config);
        }

        $config ??= config('App');

        return new IncomingRequest(
            $config,
            AppServices::uri(),
            'php://input',
            new UserAgent()
        );
    }

    /**
     * The Redirect class provides nice way of working with redirects.
     *
     * @return RedirectResponse
     */
    public static function redirectresponse(?App $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('redirectresponse', $config);
        }

        $config ??= config('App');
        $response = new RedirectResponse($config);
        $response->setProtocolVersion(AppServices::request()->getProtocolVersion());

        return $response;
    }

    /**
     * The Response class models an HTTP response.
     *
     * @return ResponseInterface
     */
    public static function response(?App $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('response', $config);
        }

        $config ??= config('App');

        return new Response($config);
    }

    /**
     * Return the debug toolbar.
     *
     * @return Toolbar
     */
    public static function toolbar(?ToolbarConfig $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('toolbar', $config);
        }

        $config ??= config('Toolbar');

        return new Toolbar($config);
    }
}
