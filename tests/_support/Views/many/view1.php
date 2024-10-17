<?php echo $this->extend('many/layout') ?>
<?php $this->section('content') ?>
    <div>
        <b>View 1</b><br>
        <?php $this->fragment('fragment1') ?>
<b>Fragment 1 (1)</b><br>
        <?php $this->endFragment() ?>
        <?php $this->fragment('fragment2') ?>
<b>Fragment 2 (1)</b><br>
        <?php $this->endFragment() ?>
    </div>
<?php $this->endSection() ?>
<?php $this->fragment('fragment1') ?>
<b>Fragment 1 (2)</b><br>
<?php $this->endFragment() ?>
