	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('User Friend'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($userFriend['UserFriend']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($userFriend['User']['email'], array('controller' => 'users', 'action' => 'view', $userFriend['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Friend Id'); ?></th>
		<td>
			<?php echo h($userFriend['UserFriend']['friend_id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Status'); ?></th>
		<td>
			<?php echo h($userFriend['UserFriend']['status']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($userFriend['UserFriend']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($userFriend['UserFriend']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>