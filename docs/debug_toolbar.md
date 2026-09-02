# Debug Toolbar

As long as you **don't use** the [hx-head](https://four.htmx.org/extensions/hx-head) extension,
the Debug Toolbar should work out of the box. It will be updated after every request, so please remember
it will only display the latest information. If you want to see what happened in earlier request,
use the `History` tab in the Toolbar.

If you're using the `hx-head` extension then the Debug Toolbar rendering will not work for `htmx` requests.
You can still access the toolbar for a given request by checking the URL in the `debugbar-link` response header.

This feature can be disabled in the [Config](configuration.md) file.
