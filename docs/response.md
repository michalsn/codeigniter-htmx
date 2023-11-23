# Response

Available methods:

- [setPushUrl()](#setpushurl)
- [setReplaceUrl()](#setreplaceurl)
- [setReswap()](#setreswap)
- [setRetarget()](#setretarget)
- [setReselect()](#setreselect)
- [triggerClientEvent()](#triggerclientevent)

### setPushUrl()

Sets the value in `HX-Push-Url` header. Pushes a new url into the history stack.

```php
$this->response->setPushUrl('/pushed-url');
```

### setReplaceUrl()

Sets the value in `HX-Replace-Url` header. Replaces the current URL in the location bar.

```php
$this->response->setReplaceUrl('/replaced-url');
```

### setReswap()

Sets the value in `HX-Reswap` header. Allows you to specify how the response will be swapped. See [hx-swap](https://htmx.org/attributes/hx-swap) for possible values.

```php
$this->response->setReswap('innerHTML show:#another-div:top');
```

### setRetarget()

Sets the value in `HX-Retarget` header. A CSS selector that updates the target of the content update to a different element on the page.

```php
$this->response->setRetarget('#another-div');
```

### setReselect()

Sets the value in `HX-Reselect` header. A CSS selector that allows you to choose which part of the response is used to be swapped in. Overrides an existing [hx-select](https://htmx.org/attributes/hx-select/) on the triggering element.

```php
$this->response->setReselect('#another-div');
```

### triggerClientEvent()

Allows you to set the headers: `HX-Trigger`, `HX-Trigger-After-Settle` or `HX-Trigger-After-Swap`.

This method has 3 parameters:
* `name`
* `params`
* `method` - which can be one of: `receive` (default), `settle`, `swap`.

```php
$this->response->triggerClientEvent('showMessage', ['level' => 'info', 'message' => 'Here Is A Message']);
```

For more information, please see [hx-trigger](https://htmx.org/headers/hx-trigger/).
