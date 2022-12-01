<?php $this->extend('layout') ?>

<?php $this->section('content') ?>

Fragment 1 header
<?php $this->fragment('sample1'); ?>
<?= $testString1 ?? 'Hello' ?>, fragment1!
<?php $this->endFragment(); ?>
Fragment 2 header
<?php $this->fragment('sample2'); ?>
<?= $testString2 ?? 'Hello' ?>, fragment2!
<?php $this->endFragment(); ?>

<?php $this->endSection() ?>
