<?php namespace Nerd; use \Application\Flash; ?>

<h2><?= $page->title ?> <small><?= $page->uri ?></small></h2>

<?= Flash::info($flash->get('info')) ?>
<?= Flash::success($flash->get('success')) ?>
<?= Flash::warning($flash->get('warning')) ?>
<?= Flash::error($flash->get('error')) ?>

<a href="<?= Url::site($lastSearch) ?>" class="btn">Back</a>