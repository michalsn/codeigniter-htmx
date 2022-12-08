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

For more information, please see [hx-location](https://htmx.org/headers/hx-location/).

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
