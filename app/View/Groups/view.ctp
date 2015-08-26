	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Group'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($group['Group']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Name'); ?></th>
		<td>
			<?php echo h($group['Group']['name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($group['User']['email'], array('controller' => 'users', 'action' => 'view', $group['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Pic'); ?></th>
		<td>
			<?php echo h($group['Group']['pic']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Tagline'); ?></th>
		<td>
			<?php echo h($group['Group']['tagline']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($group['Group']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($group['Group']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>