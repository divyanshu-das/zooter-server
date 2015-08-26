	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('User Follower'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($userFollower['UserFollower']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User From'); ?></th>
		<td>
			<?php echo $this->Html->link($userFollower['UserFrom']['email'], array('controller' => 'users', 'action' => 'view', $userFollower['UserFrom']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User To'); ?></th>
		<td>
			<?php echo $this->Html->link($userFollower['UserTo']['email'], array('controller' => 'users', 'action' => 'view', $userFollower['UserTo']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Status'); ?></th>
		<td>
			<?php echo h($userFollower['UserFollower']['status']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($userFollower['UserFollower']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($userFollower['UserFollower']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($userFollower['UserFollower']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($userFollower['UserFollower']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>