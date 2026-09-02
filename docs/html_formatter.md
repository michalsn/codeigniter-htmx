# HTML Formatter

There are times when we want to work with HTMX through an API. In such cases, we may want to use [Response Trait API](https://codeigniter.com/user_guide/outgoing/api_responses.html).

Unfortunately, CodeIgniter does not support HTML formatting for API by default. That's why such a formatter is included here. It will make it easier to work with the API when we want to return data in HTML format.

### Configuration

We should edit the `app/Config/Format.php` file to include the necessary changes. We should add `'text/html'` to the `$supportedResponseFormats` array, and `'text/html' => HTMLFormatter::class` to the `$formatters` array. This will be done automatically when you run the command:

    php spark htmx:publish

With HTMX 4, requests already send `Accept: text/html`, so in the most common HTMX case no extra client-side configuration is needed.

If you want to use the formatter outside HTMX requests, you still have two options:

1. Set the custom headers for a request explicitly
   ```html
   hx-headers='{"Accept":"text/html"}'
   ```
2. Move the `'text/html'` entry from the `$supportedResponseFormats` config array to the first position in the array - this way it will be used as the default value.
   ```php
   public array $supportedResponseFormats = [
        'text/html',
        'application/json',
        'application/xml', // machine-readable XML
        'text/xml', // human-readable XML
   ];
   ```

### Example

This is a sample of using HTML formatter:

```php
<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourcePresenter;

class Photos extends ResourcePresenter
{
    use ResponseTrait;

    public function index()
    {
        $this->format = 'html';
        return $this->respondCreated('<div>Some data</div>');
    }
}
```
