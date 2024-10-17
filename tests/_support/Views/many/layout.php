<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?= $this->renderSection('content') ?>
<?php $this->fragment('fragment1') ?>
<b>Fragment 1 (3)</b><br>
<?php $this->endFragment() ?>
<?php $this->fragment('fragment2') ?>
<b>Fragment 2 (2)</b><br>
<?php $this->endFragment() ?>
</body>
</html>
