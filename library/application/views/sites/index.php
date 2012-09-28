<?php namespace Nerd; use \Application\Flash; ?>

<h2>Listing Sites</h2>

<?= Flash::info($flash->get('info')) ?>
<?= Flash::success($flash->get('success')) ?>
<?= Flash::warning($flash->get('warning')) ?>
<?= Flash::error($flash->get('error')) ?>

<?php if (count($sites)) : ?>
<table class="table table-striped table-hover">
	<thead>
		<th>Host</th>
		<th>Active</th>
		<th>Maintainence</th>
		<th></th>
	</thead>
	<tbody>
	<?php foreach($sites as $site) : ?>
		<tr>
			<td><?= $site->host ?></td>
			<td><?= $site->active ? 'active' : 'inactive' ?></td>
			<td><?= $site->maintaining ? 'maintainence mode' : 'normal' ?></td>
			<td>
				<a href="<?= Url::site("/sites/view/{$site->id}") ?>" class="btn btn-mini">View</a>
				<a href="<?= Url::site("/sites/update/{$site->id}") ?>" class="btn btn-mini">Edit</a>
				<a href="<?= Url::site("/sites/delete/{$site->id}") ?>" class="btn btn-mini btn-danger">Delete</a>
			</td>
		</tr>
	<?php endforeach ?>
	</tbody>
</table>
<?php else : ?>
<p>There are no results to display.</p>
<?php endif ?>

<div class="form-actions">
	<a href="<?= Url::site('/sites/create') ?>" class="btn btn-primary">New Site</a>
</div>