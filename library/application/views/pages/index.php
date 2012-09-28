<?php namespace Nerd; use \Application\Flash; ?>

<form action="<?= Url::site('/pages/search') ?>" method="get" class="form-inline pull-right">
	<div class="input-append">
		<input type="text" name="q" class="span2" value="<?= isset($term) ? $term : '' ?>">
		<button type="submit" class="btn" rel="tooltip" title="Search"><i class="icon icon-search"></i></button>
		<a href="<?= Url::site('/pages') ?>" class="btn" rel="tooltip" title="Clear search results"><i class="icon icon-remove"></i></a>
	</div>
</form>

<h2>Listing Pages</h2>

<?= Flash::info($flash->get('info')) ?>
<?= Flash::success($flash->get('success')) ?>
<?= Flash::warning($flash->get('warning')) ?>
<?= Flash::error($flash->get('error')) ?>

<?php if (count($pages)) : ?>
<table class="table table-striped table-hover">
	<thead>
		<th>Title</th>
		<th>Uri</th>
		<th></th>
	</thead>
	<tbody>
	<?php foreach($pages as $page) : ?>
		<tr>
			<td><?= $page->title ?></td>
			<td><?= $page->uri ?></td>
			<td>
				<a href="<?= Url::site("/pages/view/{$page->id}") ?>" class="btn btn-mini">View</a>
				<a href="<?= Url::site("/pages/update/{$page->id}") ?>" class="btn btn-mini">Edit</a>
				<a href="<?= Url::site("/pages/delete/{$page->id}") ?>" class="btn btn-mini btn-danger<?= $page->isSpecialUri() ? ' disabled' : '' ?>">Delete</a>
			</td>
		</tr>
	<?php endforeach ?>
	</tbody>
</table>
<?php else : ?>
<p>There are no results to display.</p>
<?php endif ?>

<div class="form-actions">
	<a href="<?= Url::site('/pages/create') ?>" class="btn btn-primary">New Page</a>
</div>