	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Group Message Like'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($groupMessageLike['GroupMessageLike']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($groupMessageLike['User']['email'], array('controller' => 'users', 'action' => 'view', $groupMessageLike['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Group Message'); ?></th>
		<td>
			<?php echo $this->Html->link($groupMessageLike['GroupMessage']['id'], array('controller' => 'group_messages', 'action' => 'view', $groupMessageLike['GroupMessage']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($groupMessageLike['GroupMessageLike']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($groupMessageLike['GroupMessageLike']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>