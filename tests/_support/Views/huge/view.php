<?php echo $this->extend('huge/layout') ?>

<?php echo $this->section('content'); ?>
Section (from "huge/view")
<?php echo $this->endSection(); ?>

View top

<?php $this->fragment('fragment_one'); ?>
Fragment one (from "huge/view")
<?php $this->endFragment(); ?>

# include start in "huge/view"
<?php echo $this->include('huge/include') ?>
# include end in "huge/view"

View bottom
