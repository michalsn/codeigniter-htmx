# View fragments

We can use view fragments inside our views. The syntax is similar to one known from "Sections", example:

```php
// app/Views/page.php
...
<h2>Page header</h2>
<?php $this->fragment('example'); ?>
    <table>
        <caption><?= $caption; ?></caption>
        ...
    </table>
<?php $this->endFragment(); ?>
<div>
    ...
</div>
...
```

Now, if we make a normal call to the `view('page')`, the view will be returned as usual.
But if we make a call with a new function `view_fragment('page', 'example')`,
the whole view will be parsed as before, but we will get only the part inside the fragment "example".

```php
class Home extends BaseController
{
    public function page(): string
    {
        return view('page', ['caption' => 'Full page returned']);
    }

    public function pageFragment(): string
    {
        return view_fragment('page', 'example', ['caption' => 'Only page fragment returned']);
    }
}
```

We can return multiple fragments at the same time. Just separate each fragment with a comma or assign an array
instead of a string.
