<?php namespace Nerd; use \Application\Flash; ?>

<h2>Updating: <?= $page->title ?></h2>

<?= Flash::info($flash->get('info')) ?>
<?= Flash::success($flash->get('success')) ?>
<?= Flash::warning($flash->get('warning')) ?>
<?= Flash::error($flash->get('error')) ?>

<?= $form
