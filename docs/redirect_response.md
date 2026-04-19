# RedirectResponse

Available methods:

- [hxLocation()](#hxlocation)
- [hxRedirect()](#hxredirect)
- [hxRefresh()](#hxrefresh)

### hxLocation()

Sets the `HX-Location` header to redirect without reloading the whole page.

```php
return redirect()->hxLocation('/path');
```
For convenience, absolute `http(s)://` paths are converted to relative paths. For example, `http://example.com/articles/` becomes `/articles/`.

Supported fields mirror the HTMX 4 `HX-Location` JSON payload:

- `path` - required
- `source`
- `event`
- `handler`
- `target`
- `swap`
- `values`
- `headers`
- `select`
- `push`
- `replace`

Example:

```php
return redirect()->hxLocation(
    path: '/photos',
    target: '#content',
    swap: 'innerHTML',
    select: '#photos-list',
    push: '/photos',
);
```

For more information, please see the HTMX 4 [HX-Location response header docs](https://four.htmx.org/reference/headers/hx-location/).

### hxRedirect()

Can be used to do a client-side redirect to a new location.

```php
return redirect()->hxRedirect('/path');
```

### hxRefresh()

If called the client side will do a full refresh of the page.

```php
return redirect()->hxRefresh();
```
