# Error handling

HTMX 4 handles error responses through its response handling rules, using `hx-status:*` attributes and `htmx.config.noSwap`.

In development mode, when `errorModalDecorator` is enabled, this library overrides that default browser-side behavior for failed HTMX requests.

Instead of allowing HTMX to continue with its normal response handling, the raw response is displayed in a modal window and the normal HTMX swap is skipped.

This makes it easier to inspect exception pages, validation output, and malformed HTML returned during development, without changing the actual HTTP status code.

HTML error pages are shown in a sandboxed preview iframe, with a source view available for inspecting the raw response. JSON and plain-text responses are shown directly as source.

If you want to use HTMX's native error handling rules in development instead, disable `errorModalDecorator` in the config.

When the decorator is disabled, you can configure HTMX directly, for example:

```html
<script>
    htmx.config.noSwap = [204, 304, '4xx', '5xx'];
</script>
```

You can also use `hx-status:*` attributes to define per-status error targets in your application.

This feature can be disabled in the [Config](configuration.md) file.
