# IncomingRequest

Available methods:

- [isHtmx()](#ishtmx)
- [isBoosted()](#isboosted)
- [isHistoryRestoreRequest()](#ishistoryrestorerequest)
- [getRequestType()](#getrequesttype)
- [isPartial()](#ispartial)
- [isFull()](#isfull)
- [getCurrentUrl()](#getcurrenturl)
- [getSource()](#getsource)
- [getTarget()](#gettarget)
- [is()](#is)

### isHtmx()

Checks if the request carries the `HX-Request` header with the value `true`.

```php
$this->request->isHtmx();
```

### isBoosted()

Checks if there is a `HX-Boosted` header in place.
Indicates that the request is via an element using [hx-boost](https://four.htmx.org/reference/attributes/hx-boost)

```php
$this->request->isBoosted();
```

### isHistoryRestoreRequest()

Checks if there is a `HX-History-Restore-Request` header in place.
True if the request is for history restoration.

```php
$this->request->isHistoryRestoreRequest();
```

### getRequestType()

Checks the `HX-Request-Type` header.
Returns `partial` for targeted swaps and `full` for body-level requests, including `hx-boost` requests, or requests using `hx-select`.

```php
$this->request->getRequestType();
```

### isPartial()

Convenience method for checking if `HX-Request-Type` equals `partial`.

```php
$this->request->isPartial();
```

### isFull()

Convenience method for checking if `HX-Request-Type` equals `full`.

```php
$this->request->isFull();
```

### getCurrentUrl()

Checks the `HX-Current-URL` header and return current URL of the browser.

```php
$this->request->getCurrentUrl();
```

### getSource()

Checks the `HX-Source` header.
In HTMX 4 this identifies the triggering element using the element identifier format, for example `button#save`.

```php
$this->request->getSource();
```

### getTarget()

Checks the `HX-Target` header.
In HTMX 4 it identifies the target element using the element identifier format, for example `div#results`.

```php
$this->request->getTarget();
```

### is()

This new method is available in CodeIgniter since v4.3. It's a handful shortcut and alternative to another CodeIgniter method: `getMethod()`. But it also provides different types of checks - you can read more about it in the [user guide](https://codeigniter.com/user_guide/incoming/incomingrequest.html#is).

Along with this library, we added extra parameters that can be used: `htmx`, `boosted`, `partial`, and `full`.

```php
$this->request->is('htmx');
// or
$this->request->is('boosted');
// or
$this->request->is('partial');
// or
$this->request->is('full');
```
