# Troubleshooting

## Slow content swapping

In `development` environment, if you experience slow content swapping but the requests itself are fast,
you may want to turn off the `View Collector`. To do so, go to the `app/Config/Toolbar.php` file and
comment out the line with `View::class`:

```php

// ...

class Toolbar extends BaseConfig
{

    // ...

    public array $collectors = [
        Timers::class,
        Database::class,
        Logs::class,
        //Views::class,

        // ...
    ];

    // ...

```

This happens because HTML comments added by this collector class slow down the proces of parsing the content by htmx.

This problem will not apply to the `production` environment, since the `Debug Toolbar` is disabled by default in this environment.

## PHPStan

### Request
If you keep getting PHPStan error whenever you use any new **request** method, like `isHtmx()`, example:

> ```Call to an undefined method CodeIgniter\HTTP\CLIRequest|CodeIgniter\HTTP\IncomingRequest::isHtmx().```

Please edit your `BaseController` and replace

* `use CodeIgniter\HTTP\IncomingRequest;` with
* `use Michalsn\CodeIgniterHtmx\HTTP\IncomingRequest;`

### Response

If you keep getting PHPStan error whenever you use any new **response** method, like `setPushUrl()`, example:

> ```Call to an undefined method CodeIgniter\HTTP\ResponseInterface::setPushUrl().```

Please edit your `BaseController` and add:

* `use Michalsn\CodeIgniterHtmx\HTTP\Response;` before a class definition
* And a class variable with description:

```php
 /**
   * Instance of the main Response object.
   *
   * @var Response
   */
  protected $response;
```
