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

Supported fields mirror the serializable htmx 4 request-context options accepted by `HX-Location`:

- `path` - required
- `source`
- `event`
- `target`
- `swap`
- `values`
- `headers`
- `select`
- `selectOOB`
- `push`
- `replace`
- `transition`

`push` and `replace` accept a path, the strings `true` or `false`, or the boolean `false`. Absolute URLs used for `path`, `push`, or `replace` are normalized to same-origin paths.

For positional calls, `push`, `replace`, and `select` retain their positions from version 2.3. The legacy `handler` position is also reserved to prevent subsequent arguments from being misinterpreted. Because final htmx 4 no longer supports this callback option, passing a non-null `handler` throws an `InvalidArgumentException`. Use client-side htmx lifecycle events instead.

Example:

```php
return redirect()->hxLocation(
    path: '/photos',
    target: '#content',
    swap: 'innerHTML',
    select: '#photos-list',
    selectOOB: '#flash:beforeend',
    push: '/photos',
    transition: true,
);
```

For more information, see the htmx 4 [HX-Location response header documentation](https://four.htmx.org/reference/headers/hx-location/).

### hxRedirect()

Can be used to do a client-side redirect to a new location.

```php
return redirect()->hxRedirect('/path');
```

### hxRefresh()

If called, the client performs a full refresh of the page.

```php
return redirect()->hxRefresh();
```
