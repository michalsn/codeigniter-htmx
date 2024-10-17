Sample view
<?php $this->fragment('sample1'); ?>
<?= $testString1 ?? 'Hello' ?>, fragment1!
<?= $this->include('view_included_2'); ?>
<?php $this->endFragment(); ?>
