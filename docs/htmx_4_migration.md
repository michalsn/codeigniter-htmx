# HTMX 4 migration

This package now follows the HTMX 4 request/response model.

The most important HTMX 4 changes for this package are:

- Requests use `fetch()` instead of `XMLHttpRequest`.
- Browser event names use the `htmx:phase:action` format.
- `HX-Source` replaces `HX-Trigger`.
- `HX-Request-Type` is available with `partial` and `full` values.
- `HX-Target` now uses the `tag#id` identifier format.
- `HX-Prompt` and `HX-Trigger-Name` are no longer sent by HTMX 4 clients.
- Error response handling is configured through `hx-status:*` and `htmx.config.noSwap`.

## Quick start

If you want to preserve the most common HTMX 2 defaults while you migrate your application, add:

```html
<script>
    htmx.config.implicitInheritance = true;
    htmx.config.noSwap = [204, 304, '4xx', '5xx'];
</script>
```

This is only a transition aid. For a native HTMX 4 setup, prefer explicit `:inherited` attributes and keep the default `noSwap` behavior unless your application needs something else.

## IncomingRequest changes

Use the new methods when working with HTMX 4 requests:

```php
$this->request->getSource();
$this->request->getRequestType();
$this->request->isPartial();
$this->request->isFull();
```

## Response behavior

HTMX 4 still supports the response headers used by this package, including:

- `HX-Location`
- `HX-Push-Url`
- `HX-Redirect`
- `HX-Refresh`
- `HX-Replace-Url`
- `HX-Retarget`
- `HX-Reswap`
- `HX-Reselect`
- `HX-Trigger`

## Error responses

If you want to avoid swapping `4xx` and `5xx` responses, configure:

```html
<script>
    htmx.config.noSwap = [204, 304, '4xx', '5xx'];
</script>
```

You can also enable HTMX 2 compatibility mode in the browser:

```html
<script src="/path/to/htmx.js"></script>
<script src="/path/to/ext/htmx-2-compat.js"></script>
```

## Attribute inheritance

HTMX 4 uses explicit inheritance by default.

If your markup relied on inherited attributes such as `hx-target`, `hx-boost`, or `hx-confirm`, update the attributes to use the `:inherited` modifier:

```html
<div hx-confirm:inherited="Are you sure?">
    <button hx-delete="/item/1">Delete</button>
</div>
```

If you need the old behavior:

```html
<script>
    htmx.config.implicitInheritance = true;
</script>
```

For larger migrations, the HTMX 2 compatibility extension can be a more convenient temporary bridge than enabling `implicitInheritance` globally.

## Request markup changes

HTMX 4 also changes a few client-side attributes that may affect applications using this package:

- `hx-delete` no longer includes enclosing form values automatically. Use `hx-include="closest form"` when needed.
- In HTMX 2, `hx-disable` prevented HTMX processing. In HTMX 4 that behavior moved to `hx-ignore`, while `hx-disabled-elt` was renamed to `hx-disable`.
- `hx-request` was replaced by `hx-config`.
- `hx-prompt` was removed. Use `hx-confirm` with JavaScript instead.
- `hx-ext` was removed. Extensions now register through standard events, so load the extension script directly and use its attributes where needed.
- `hx-history` and `hx-history-elt` were removed.
- `data-hx-*` attributes are no longer recognized automatically unless you configure `htmx.config.prefix`.

## History behavior

HTMX 4 no longer stores page snapshots in browser storage.

When the user navigates through history, HTMX issues a new full-page request instead. The `HX-History-Restore-Request` header is still available, so this package continues to expose `isHistoryRestoreRequest()`.

If you want hard browser reloads on history navigation instead of HTMX restoration, use:

```html
<script>
    htmx.config.history = 'reload';
</script>
```

## Out-of-band swap order

In HTMX 4, the main swap happens before `hx-swap-oob` content.

If your UI depends on out-of-band fragments being processed first, review the markup and make each swap independent.

## Timeouts

HTMX 4 sets a 60-second request timeout by default:

```html
<script>
    htmx.config.defaultTimeout = 60000;
</script>
```

If your application expects no timeout, set it back to `0`.

## Extensions

Extensions are now loaded by including their scripts directly.

```html
<script src="/path/to/htmx.js"></script>
<script src="/path/to/ext/sse.js"></script>
```

You can optionally restrict which extensions may load with `htmx.config.extensions`.

## Caching

If your application returns different HTML for full and partial HTMX requests, configure caching carefully and consider sending:

```http
Vary: HX-Request-Type
```

If responses also depend on the source or target element, extend the `Vary` header accordingly.

## References

- [HTMX 4 migration guide](https://four.htmx.org/docs/get-started/migration)
- [HTMX 4 reference](https://four.htmx.org/reference/)
