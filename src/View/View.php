<?php

namespace Michalsn\CodeIgniterHtmx\View;

use CodeIgniter\Debug\Toolbar\Collectors\Views;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\View\Exceptions\ViewException;
use CodeIgniter\View\RendererInterface;
use CodeIgniter\View\View as BaseView;
use Config\Toolbar;
use RuntimeException;

/**
 * Class View
 */
class View extends BaseView
{
    /**
     * Show fragments tags or not.
     */
    protected bool $showFragments = false;

    /**
     * The name of the current section being rendered,
     * if any.
     *
     * @var list<string>
     */
    protected array $fragmentStack = [];

    /**
     * Starts holds content for a fragment within the layout.
     *
     * @param string $name Fragment name
     */
    public function fragment(string $name): void
    {
        $this->fragmentStack[] = $name;

        if ($this->showFragments) {
            echo sprintf('@[[fragmentStart="%s"]]', $name);
        }
    }

    /**
     * Captures the last fragment
     *
     * @throws RuntimeException
     */
    public function endFragment(): void
    {
        if ($this->fragmentStack === []) {
            ob_end_clean();

            throw new RuntimeException('View themes, no current fragment.');
        }

        $name = array_pop($this->fragmentStack);

        if ($this->showFragments) {
            echo sprintf('@[[fragmentEnd="%s"]]', $name);
        }
    }

    /**
     * Whether we should display fragments tags or not.
     */
    protected function showFragments(bool $display = true): RendererInterface
    {
        $this->showFragments = $display;

        return $this;
    }

    /**
     * Render fragments.
     */
    public function renderFragments(string $name, ?array $options = null, ?bool $saveData = null): string
    {
        $fragments = $options['fragments'] ?? [];
        $output    = $this->showFragments()->render($name, $options, $saveData);

        if ($fragments === []) {
            return preg_replace('/@\[\[fragmentStart="[^"]+"\]\]|@\[\[fragmentEnd="[^"]+"\]\]/', '', $output);
        }

        $result = $this->showFragments(false)->parseFragments($output, $fragments);
        $output = '';

        foreach ($result as $contents) {
            $output .= implode('', $contents);
        }

        return $output;
    }

    /**
     * Parse output to retrieve fragments.
     */
    protected function parseFragments(string $output, array $fragments): array
    {
        $results = [];
        $stack   = [];

        // Match all fragment start and end tags at once
        preg_match_all('/@\[\[fragmentStart="([^"]+)"\]\]|@\[\[fragmentEnd="([^"]+)"\]\]/', $output, $matches, PREG_OFFSET_CAPTURE);

        // Return empty array if no matches
        if ($matches[0] === []) {
            return $results;
        }

        foreach ($matches[0] as $index => $match) {
            $pos     = $match[1];
            $isStart = isset($matches[1][$index]) && $matches[1][$index][0] !== '';
            $name    = $isStart ? $matches[1][$index][0] : (isset($matches[2][$index]) ? $matches[2][$index][0] : '');

            if ($isStart) {
                $stack[] = ['name' => $name, 'start' => $pos];
            } elseif ($stack !== [] && end($stack)['name'] === $name) {
                $info = array_pop($stack);

                // Calculate the position of the fragment content
                $fragmentStart = $info['start'] + strlen($matches[0][array_search($info['name'], array_column($matches[1], 0), true)][0]);
                $fragmentEnd   = $pos;

                // Extract the content between the tags
                $content = substr($output, $fragmentStart, $fragmentEnd - $fragmentStart);
                // Clean the fragment content by removing the tags
                $content = preg_replace('/@\[\[fragmentStart="[^"]+"\]\]|@\[\[fragmentEnd="[^"]+"\]\]/', '', $content);

                if (in_array($info['name'], $fragments, true)) {
                    $results[$info['name']][] = $content;
                }
            }
        }

        return $results;
    }

    /**
     * Builds the output based upon a file name and any
     * data that has already been set.
     *
     * Valid $options:
     *  - cache      Number of seconds to cache for
     *  - cache_name Name to use for cache
     *
     * @param string                    $view     File name of the view source
     * @param array<string, mixed>|null $options  Reserved for 3rd-party uses since
     *                                            it might be needed to pass additional info
     *                                            to other template engines.
     * @param bool|null                 $saveData If true, saves data for subsequent calls,
     *                                            if false, cleans the data after displaying,
     *                                            if null, uses the config setting.
     */
    public function render(string $view, ?array $options = null, ?bool $saveData = null): string
    {
        $this->renderVars['start'] = microtime(true);

        // Store the results here so even if
        // multiple views are called in a view, it won't
        // clean it unless we mean it to.
        $saveData ??= $this->saveData;
        $fileExt                     = pathinfo($view, PATHINFO_EXTENSION);
        $realPath                    = empty($fileExt) ? $view . '.php' : $view; // allow Views as .html, .tpl, etc (from CI3)
        $this->renderVars['view']    = $realPath;
        $this->renderVars['options'] = $options ?? [];

        // Was it cached?
        if (isset($this->renderVars['options']['cache'])) {
            $cacheName = $this->renderVars['options']['cache_name'] ?? str_replace('.php', '', $this->renderVars['view']) . (empty($this->renderVars['options']['fragments']) ? '' : implode('', $this->renderVars['options']['fragments']));
            $cacheName = str_replace(['\\', '/'], '', $cacheName);

            $this->renderVars['cacheName'] = $cacheName;

            if ($output = cache($this->renderVars['cacheName'])) {
                $this->logPerformance($this->renderVars['start'], microtime(true), $this->renderVars['view']);

                return $output;
            }
        }

        $this->renderVars['file'] = $this->viewPath . $this->renderVars['view'];

        if (! is_file($this->renderVars['file'])) {
            $this->renderVars['file'] = $this->loader->locateFile($this->renderVars['view'], 'Views', empty($fileExt) ? 'php' : $fileExt);
        }

        // locateFile will return an empty string if the file cannot be found.
        if (empty($this->renderVars['file'])) {
            throw ViewException::forInvalidFile($this->renderVars['view']);
        }

        // Make our view data available to the view.
        $this->prepareTemplateData($saveData);

        // Save current vars
        $renderVars = $this->renderVars;

        $output = (function (): string {
            extract($this->tempData);
            ob_start();
            include $this->renderVars['file'];

            return ob_get_clean() ?: '';
        })();

        // Get back current vars
        $this->renderVars = $renderVars;

        // When using layouts, the data has already been stored
        // in $this->sections, and no other valid output
        // is allowed in $output so we'll overwrite it.
        if ($this->layout !== null && $this->sectionStack === []) {
            $layoutView   = $this->layout;
            $this->layout = null;
            // Save current vars
            $renderVars = $this->renderVars;
            $output     = $this->render($layoutView, $options, $saveData);
            // Get back current vars
            $this->renderVars = $renderVars;
        }

        $output = $this->decorateOutput($output);

        $this->logPerformance($this->renderVars['start'], microtime(true), $this->renderVars['view']);

        if (($this->debug && (! isset($options['debug']) || $options['debug'] === true))
            && in_array(DebugToolbar::class, service('filters')->getFiltersClass()['after'], true)
            && empty($this->renderVars['options']['fragments'])
        ) {
            $toolbarCollectors = config(Toolbar::class)->collectors;

            if (in_array(Views::class, $toolbarCollectors, true)) {
                // Clean up our path names to make them a little cleaner
                $this->renderVars['file'] = clean_path($this->renderVars['file']);
                $this->renderVars['file'] = ++$this->viewsCount . ' ' . $this->renderVars['file'];

                $output = '<!-- DEBUG-VIEW START ' . $this->renderVars['file'] . ' -->' . PHP_EOL
                    . $output . PHP_EOL
                    . '<!-- DEBUG-VIEW ENDED ' . $this->renderVars['file'] . ' -->' . PHP_EOL;
            }
        }

        // Should we cache?
        if (isset($this->renderVars['options']['cache'])) {
            cache()->save($this->renderVars['cacheName'], $output, (int) $this->renderVars['options']['cache']);
        }

        $this->tempData = null;

        return $output;
    }
}
