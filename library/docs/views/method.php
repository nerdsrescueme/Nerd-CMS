<a name="<?= $method->getName() ?>"></a>
<section id="<?= strtolower($method->getName()) ?>">
	<h3>
		<?= $method->getName() ?>(<?= $method->getParameters(true).') &mdash; <span>'.$method->getDocblock()->getShortDescription().'</span>' ?>
	</h3>
	<?= $md->transformMarkdown($method->getDocblock()->getLongDescription()) ?> 
	<table>
		<tr>
			<th>Visibility</th>
			<td><?= methodVisibility($method) ?></td>
		</tr>
		<tr>
			<th>Static</th>
			<td><?= $method->isStatic() ? 'yes' : 'no' ?></td>
		</tr>
		<?php if($method->getDocblock()->hasTag('return')) : ?> 
		<tr>
			<th>Returns</th>
			<td class="params">
				<table>
				<thead>
					<tr>
						<th>Returns</th>
						<th>Condition</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($method->getDocblock()->getTag('return') as $tag) : ?> 
					<tr>
						<td><?= $tag->getType() ?></td>
						<td><?= $tag->getDescription() ?></td>
					</tr>
					<?php endforeach ?> 
				</tbody>
				</table>
		</tr>
		<?php endif ?> 
		<?php if($method->getDocblock()->hasTag('throw')) : ?>
		<tr>
			<th>Exceptions</th>
			<?php  ?>
			<td class="params">
				<table>
				<thead>
					<tr>
						<th>Throws</th>
						<th>Condition</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($method->getDocblock->getTag('throw') as $tag) : ?> 
					<tr>
						<td><?= $tag->getType() ?></td>
						<td><?= $tag->getDescription() ?></td>
					</tr>
					<?php endforeach ?> 
				</tbody>
				</table>
		</tr>
		<?php endif ?> 
		<tr>
			<th>Parameters</th>
			<td class="params">
				<?php if($method->getNumberOfParameters() > 0) : ?> 
				<table>
				<thead>
					<tr>
						<th class="param">Parameter</th>
						<th class="type">Type</th>
						<th class="default">Default</th>
						<th class="description">Description</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($method->getParameters() as $key => $param) : ?>
					<tr>
						<td><?= '$'.$param->getName() ?></td>
						<td><?= isset($params[$key]) ? $params[$key]->getType() : 'unknown' ?></td>
						<td><?= $param->isDefaultValueAvailable() ? paramTranslate($param->getDefaultValue()) : 'required' ?></td>
						<td><?= isset($params[$key]) ? $params[$key]->getDescription() : 'none' ?></td>
					</tr>
					<?php endforeach ?> 
				</tbody>
				</table>
				<?php else : ?> 
				<table>
					<tr>
						<td>No parameters required.</td>
					</tr>
				</table>
				<?php endif ?> 
			</td>
		</tr>
	</table>
	<footer>
		<p>
			<strong>File:</strong> <?= str_replace(\Nerd\LIBRARY_PATH, '', $method->getDeclaringClass()->getFileName()) ?>
			<strong>Line:</strong> <?= $method->getStartLine() ?>
			<a href="#">show source</a>
		</p>
		<pre><?= ($docblock = $method->getDocblock() and $docblock->getShortDescription() == 'Empty Docblock') ? '// Source inherited from PHP\'s '.$method->getDeclaringClass()->getName().' class' : $method->getContents() ?></pre>
	</footer>
</section>