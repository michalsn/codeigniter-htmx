# CodeIgniter HTMX

A set of methods for `IncomingRequest`, `Response` and `RedirectResponse` classes to help you work with [HTMX](https://htmx.org) fluently in CodeIgniter 4 framework.

## IncomingRequest

### isHtmx()

Checks if there is a `HX-Request` header in place.
Indicates that the request was fired with htmx.

```php
$this->request->isHtmx();
```

### isBoosted()

Checks if there is a `HX-Boosted` header in place.
Indicates that the request is via an element using [hx-boost](https://htmx.org/attributes/hx-boost)

```php
$this->request->isBoosted();
```

### isHistoryRestoreRequest()

Checks if there is a `HX-History-Restore-Request` header in place.
True if the request is for history restoration after a miss in the local history cache.

```php
$this->request->isHistoryRestoreRequest();
```

### getCurrentUrl()

Checks the `HX-Current-URL` header and return current URL of the browser.

```php
$this->request->getCurrentUrl();
```

### getPrompt()

Checks the `HX-Prompt` header - the user response to an [hx-prompt](https://htmx.org/attributes/hx-prompt/).

```php
$this->request->getPrompt();
```

### getTarget()

Checks the `HX-Target` header. Returns the `id` of the target element if it exists.

```php
$this->request->getTarget();
```

### getTrigger()

Checks the `HX-Trigger` header. Returns the `id` of the triggered element if it exists.

```php
$this->request->getTrigger();
```

### getTriggerName()

Checks the `HX-Trigger-Name` header. Returns the `name` of the triggered element if it exists.

```php
$this->request->getTriggerName();
```

### getTriggeringEvent()

Checks the `Triggering-Event` header. The value of the header is a JSON serialized version of the event that triggered the request.
Check the [event-header](https://htmx.org/extensions/event-header/) plugin for more information.

## Response

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

## RedirectResponse

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



