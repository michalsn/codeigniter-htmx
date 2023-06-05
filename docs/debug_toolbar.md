# Debug Toolbar

Although the Debug Toolbar rendering is disabled for HTMX requests, you can still access the toolbar
for a given request by checking the URL in the `debugbar-link` response header.

### Other options

Alternatively, if you're rendering content only inside the `body` tag, you can use the snippet below.

```js
htmx.on('htmx:afterRequest', function (ev) {
    let debugBarTime = ev.detail.xhr.getResponseHeader("debugbar-time");
    if (debugBarTime !== null) {
        loadDoc(debugBarTime);
    }
});
```

It will cause the Debug Toolbar, rendered by the "regular" request, to be automatically
replaced which information from the current HTMX request.
