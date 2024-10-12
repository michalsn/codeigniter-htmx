<div>
    <b>View 3</b><br>
    <b>foo: </b> <?= isset($foo) ? 'YES' : 'NO' ?><br>
    <b>bar: </b> <?= isset($bar) ? 'YES' : 'NO' ?><br>
    <b>baz: </b> <?= isset($baz) ? 'YES' : 'NO' ?><br>
    <b>too: </b> <?= isset($too) ? 'YES' : 'NO' ?><br>
    <b>inc: </b> <?= isset($inc) ? 'YES' : 'NO' ?><br>
</div>
<?php $this->fragment('fragment3') ?>
<div>
    <b>Fragment 3</b><br>
    <b>foo: </b> <?= isset($foo) ? 'YES' : 'NO' ?><br>
    <b>bar: </b> <?= isset($bar) ? 'YES' : 'NO' ?><br>
    <b>baz: </b> <?= isset($baz) ? 'YES' : 'NO' ?><br>
    <b>too: </b> <?= isset($too) ? 'YES' : 'NO' ?><br>
    <b>inc: </b> <?= isset($inc) ? 'YES' : 'NO' ?><br>
</div>
<?php $this->endFragment() ?>
