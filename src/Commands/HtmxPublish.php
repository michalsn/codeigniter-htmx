<?php

namespace Michalsn\CodeIgniterHtmx\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Publisher\Publisher;
use Throwable;

class HtmxPublish extends BaseCommand
{
    protected $group       = 'Htmx';
    protected $name        = 'htmx:publish';
    protected $description = 'Publish Htmx config file into the current application.';

    /**
     * @return void
     */
    public function run(array $params)
    {
        $source = service('autoloader')->getNamespace('Michalsn\\CodeIgniterHtmx')[0];

        $publisher = new Publisher($source, APPPATH);

        try {
            $publisher->addPaths([
                'Config/Htmx.php',
            ])->merge(false);
        } catch (Throwable $e) {
            $this->showError($e);

            return;
        }

        foreach ($publisher->getPublished() as $file) {
            $contents = file_get_contents($file);
            $contents = str_replace('namespace Michalsn\\CodeIgniterHtmx\\Config', 'namespace Config', $contents);
            $contents = str_replace('use CodeIgniter\\Config\\BaseConfig', 'use Michalsn\\CodeIgniterHtmx\\Config\\Htmx as BaseHtmx', $contents);
            $contents = str_replace('class Htmx extends BaseConfig', 'class Htmx extends BaseHtmx', $contents);
            file_put_contents($file, $contents);
        }

        $file = APPPATH . 'Config/Format.php';

        $publisher->addLineAfter(
            $file,
            "        'text/html'",
            "'text/xml', // human-readable XML",
        );

        $publisher->addLineAfter(
            $file,
            "        'text/html'        => HTMLFormatter::class,",
            "'text/xml'         => XMLFormatter::class,",
        );

        $publisher->addLineAfter(
            $file,
            'use Michalsn\\CodeIgniterHtmx\\Format\\HTMLFormatter;',
            'use CodeIgniter\\Format\\XMLFormatter;',
        );

        CLI::write(CLI::color('  Published! ', 'green') . 'You can customize the configuration by editing the "app/Config/Htmx.php" file.');
    }
}
