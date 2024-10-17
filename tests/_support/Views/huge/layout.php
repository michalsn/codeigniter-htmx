Layout top

<?php echo $this->renderSection('content') ?>

<?php echo $this->include('huge/include') ?>

<?php echo $this->fragment('fragment_one'); ?>
Fragment one (from "huge/layout")
<?php echo $this->endFragment(); ?>

Layout bottom
