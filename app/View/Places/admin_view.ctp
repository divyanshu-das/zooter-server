	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Place'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($place['Place']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($place['User']['email'], array('controller' => 'users', 'action' => 'view', $place['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Location'); ?></th>
		<td>
			<?php echo $this->Html->link($place['Location']['name'], array('controller' => 'locations', 'action' => 'view', $place['Location']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Name'); ?></th>
		<td>
			<?php echo h($place['Place']['name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Description'); ?></th>
		<td>
			<?php echo h($place['Place']['description']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Type'); ?></th>
		<td>
			<?php echo h($place['Place']['type']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Phone'); ?></th>
		<td>
			<?php echo h($place['Place']['phone']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Alt Phone'); ?></th>
		<td>
			<?php echo h($place['Place']['alt_phone']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Email'); ?></th>
		<td>
			<?php echo h($place['Place']['email']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Min Age'); ?></th>
		<td>
			<?php echo h($place['Place']['min_age']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Max Age'); ?></th>
		<td>
			<?php echo h($place['Place']['max_age']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($place['Place']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($place['Place']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($place['Place']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($place['Place']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>