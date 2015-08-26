	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Group Message Comment'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($groupMessageComment['GroupMessageComment']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($groupMessageComment['User']['email'], array('controller' => 'users', 'action' => 'view', $groupMessageComment['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Group Message'); ?></th>
		<td>
			<?php echo $this->Html->link($groupMessageComment['GroupMessage']['id'], array('controller' => 'group_messages', 'action' => 'view', $groupMessageComment['GroupMessage']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Message'); ?></th>
		<td>
			<?php echo h($groupMessageComment['GroupMessageComment']['message']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($groupMessageComment['GroupMessageComment']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($groupMessageComment['GroupMessageComment']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>