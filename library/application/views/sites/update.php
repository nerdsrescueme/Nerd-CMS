<?php namespace Nerd; use \Application\Flash; ?>

<h2>Update <small><?= $site->host ?></small></h2>

<?= Flash::info($flash->get('info')) ?>
<?= Flash::success($flash->get('success')) ?>
<?= Flash::warning($flash->get('warning')) ?>
<?= Flash::error($flash->get('error')) ?>

<?= $form
