	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Group Message'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($groupMessage['GroupMessage']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($groupMessage['User']['email'], array('controller' => 'users', 'action' => 'view', $groupMessage['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Group'); ?></th>
		<td>
			<?php echo $this->Html->link($groupMessage['Group']['name'], array('controller' => 'groups', 'action' => 'view', $groupMessage['Group']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Message'); ?></th>
		<td>
			<?php echo h($groupMessage['GroupMessage']['message']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Image'); ?></th>
		<td>
			<?php echo h($groupMessage['GroupMessage']['image']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Video'); ?></th>
		<td>
			<?php echo h($groupMessage['GroupMessage']['video']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($groupMessage['GroupMessage']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($groupMessage['GroupMessage']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>