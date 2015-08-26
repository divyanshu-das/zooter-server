	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('User Group'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($userGroup['UserGroup']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($userGroup['User']['email'], array('controller' => 'users', 'action' => 'view', $userGroup['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Group'); ?></th>
		<td>
			<?php echo $this->Html->link($userGroup['Group']['name'], array('controller' => 'groups', 'action' => 'view', $userGroup['Group']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Level'); ?></th>
		<td>
			<?php echo h($userGroup['UserGroup']['level']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($userGroup['UserGroup']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($userGroup['UserGroup']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>