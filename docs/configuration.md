# Configuration

To make changes to the config file, we have to have our copy in the `app/Config/Htmx.php`. Luckily, this package comes with handy command that will make this easy.

When we run:

    php spark htmx:publish

We will get our copy ready for modifications.

---

Available options:

- [$toolbarDecorator](#toolbarDecorator)
- [$errorModalDecorator](#errorModalDecorator)
- [$skipViewDecoratorsString](#skipViewDecoratorsString)

### $toolbarDecorator

This allows us to disable the `ToolbarDecorator` class. Please read [Debug Toolbar](debug_toolbar.md) page for more information.

### $errorModalDecorator

This allows us to disable the `ErrorModalDecorator` class. Please read [Error handling](error_handling.md) page for more information.

### $skipViewDecoratorsString

If this string appears in the content of the file, it will prevent CodeIgniter from using both View Decorator classes above - even if they are enabled.

You can change this string to whatever you want. Just remember to make it unique enough to not use it by accident.

This may be useful when we want to send an e-mail, which message is prepared via the View file.
Since these decorators are used automatically in the `development` mode (or to be more strict - when `CI_DEBUG` is enabled), we may want to disable all the scripts for the e-mail messages.

We can add the defined string as an `id` or `class` to the html tag.

In the `production` environment these decorators are ignored by design. So this is useful only for the `development` mode.

### $storePreviousURL

Specifies whether the HTMX request URL should be stored in the session, for use with the `previous_url()` helper function.
For more information, see the [user guide](https://codeigniter.com/user_guide/helpers/url_helper.html#previous_url).

Basically, if you use HTMX extensively, including for navigating your site, you will probably want to leave it as `true`,
and in cases where storing the request is not desirable, even if it uses HTMX, you can use custom header, to indicate the
AJAX call or [ajax-header](https://github.com/bigskysoftware/htmx-extensions/blob/main/src/ajax-header/README.md) extension,
which will add the necessary headers automatically. URLs from AJAX requests are always excluded from session storage.
