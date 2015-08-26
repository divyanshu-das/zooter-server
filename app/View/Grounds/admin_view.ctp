	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Ground'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($ground['Ground']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Name'); ?></th>
		<td>
			<?php echo h($ground['Ground']['name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Location'); ?></th>
		<td>
			<?php echo $this->Html->link($ground['Location']['name'], array('controller' => 'locations', 'action' => 'view', $ground['Location']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Contact Name'); ?></th>
		<td>
			<?php echo h($ground['Ground']['contact_name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Contact Number'); ?></th>
		<td>
			<?php echo h($ground['Ground']['contact_number']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($ground['Ground']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($ground['Ground']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($ground['Ground']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($ground['Ground']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>