	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Movie'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($movie['Movie']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Name'); ?></th>
		<td>
			<?php echo h($movie['Movie']['name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Genre'); ?></th>
		<td>
			<?php echo h($movie['Movie']['genre']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Rating'); ?></th>
		<td>
			<?php echo h($movie['Movie']['rating']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($movie['Movie']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($movie['Movie']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($movie['Movie']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($movie['Movie']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>