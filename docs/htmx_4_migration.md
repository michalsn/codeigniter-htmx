# Migrating from htmx 2 to htmx 4

This page covers changes in this CodeIgniter integration. Use the official [htmx 2 to htmx 4 migration guide](https://four.htmx.org/docs#migrating-from-htmx-2x-to-4x) for client-side attributes, JavaScript APIs, events, configuration, and extensions. The official [What's new in htmx 4](https://four.htmx.org/docs/whats-new-in-htmx-4) page provides additional context.

## Install htmx 4 explicitly

This package does not install the browser library. Select the htmx 4 major version explicitly; an unversioned npm install may still resolve to htmx 2 during the transition period.

```console
npm install htmx.org@4
```

## IncomingRequest API

htmx 4 renamed request headers and added a request type:

| htmx 2 package API | htmx 4 package API | Notes |
| --- | --- | --- |
| `getTrigger()` | `getSource()` | `HX-Trigger` became `HX-Source`; its value is now an element identifier such as `button#save`. |
| `getTriggerName()` | — | `HX-Trigger-Name` was removed. |
| `getTriggeringEvent()` | — | The `Triggering-Event` extension header is not part of the htmx 4 request model. Read a custom header through CodeIgniter if your application still sends one. |
| `getPrompt()` | `getPrompt()` | Retained for the optional [hx-prompt extension](https://four.htmx.org/extensions/hx-prompt). |
| — | `getRequestType()` | Returns `partial`, `full`, or `null`. |
| — | `isPartial()` / `isFull()` | Convenience checks for `HX-Request-Type`. These values also work with `is('partial')` and `is('full')`. |

`getTarget()` remains available, but `HX-Target` now contains an element identifier such as `div#results`, rather than only its ID.

The existing `isHtmx()`, `isBoosted()`, `isHistoryRestoreRequest()`, and `getCurrentUrl()` methods remain available.

## Response API

htmx 4 removed the response headers that scheduled triggers after the swap or settle phase. Consequently, the third `$after` argument was removed from `triggerClientEvent()`:

```php
$this->response->triggerClientEvent('showMessage', [
    'level'   => 'info',
    'message' => 'Saved',
]);
```

Events are now sent through `HX-Trigger`. Listen at the appropriate point in the htmx 4 event lifecycle when later client-side handling is required.

`setReswap()` accepts the final htmx 4 swap styles, including `innerMorph`, `outerMorph`, `outerSync`, `textContent`, `before`, `after`, `prepend`, and `append`, in addition to the existing styles.

`RedirectResponse::hxLocation()` supports the serializable htmx 4 request-context options exposed by this package: `source`, `event`, `target`, `swap`, `values`, `headers`, `select`, `selectOOB`, `push`, `replace`, and `transition`.

The `push`, `replace`, and `select` parameter positions introduced in package version 2.3 are retained for positional calls. The legacy `handler` position is reserved as a migration guard, but passing a value throws an `InvalidArgumentException`: final htmx 4 no longer supports a response callback in `htmx.ajax()` options.

## Server behavior to review

htmx 4 swaps error responses by default; only `204` and `304` are excluded. If your application expects the htmx 2 behavior, either configure `htmx.config.noSwap` or use `hx-status:*` attributes. The package's development error modal continues to show error responses without changing their HTTP status.

If a cache can serve both full and partial responses for the same URL, make the variants explicit. A typical starting point is:

```http
Vary: HX-Request-Type
```

Extend `Vary` when the representation also depends on another request header.

## Client-side checklist

The following application-level changes are intentionally not duplicated here; review them in the official migration guide:

- explicit attribute inheritance and the temporary htmx 2 compatibility extension;
- renamed or removed attributes and configuration options;
- the `fetch()`-based request and event lifecycle;
- history restoration, timeouts, and out-of-band swap ordering;
- the htmx 4 extension registration model.

Use the official upgrade checker as a first pass, then test behavior that depends on inheritance, error responses, history, and custom events.

## References

- [Migrating from htmx 2.x to 4.x](https://four.htmx.org/docs#migrating-from-htmx-2x-to-4x)
- [What's new in htmx 4](https://four.htmx.org/docs/whats-new-in-htmx-4)
- [htmx 4.0.0 release notes](https://four.htmx.org/announcements/2026-08-28-htmx-4.0.0-is-released)
- [htmx 4 reference](https://four.htmx.org/reference/)
