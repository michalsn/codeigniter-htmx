# Troubleshooting

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
