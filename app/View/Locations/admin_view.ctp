	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Location'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($location['Location']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Name'); ?></th>
		<td>
			<?php echo h($location['Location']['name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Unique Identifier'); ?></th>
		<td>
			<?php echo h($location['Location']['unique_identifier']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Latitude'); ?></th>
		<td>
			<?php echo h($location['Location']['latitude']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Longitude'); ?></th>
		<td>
			<?php echo h($location['Location']['longitude']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($location['Location']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($location['Location']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>