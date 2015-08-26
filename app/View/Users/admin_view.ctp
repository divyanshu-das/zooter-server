	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('User'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Email'); ?></th>
		<td>
			<?php echo h($user['User']['email']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Username'); ?></th>
		<td>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Password'); ?></th>
		<td>
			<?php echo h($user['User']['password']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Password Reset'); ?></th>
		<td>
			<?php echo h($user['User']['password_reset']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Phone'); ?></th>
		<td>
			<?php echo h($user['User']['phone']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Is Verified'); ?></th>
		<td>
			<?php echo h($user['User']['is_verified']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Image'); ?></th>
		<td>
			<?php echo h($user['User']['image']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Dir'); ?></th>
		<td>
			<?php echo h($user['User']['dir']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Type'); ?></th>
		<td>
			<?php echo $this->Html->link($user['Type']['type'], array('controller' => 'types', 'action' => 'view', $user['Type']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Role'); ?></th>
		<td>
			<?php echo h($user['User']['role']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Access Level'); ?></th>
		<td>
			<?php echo h($user['User']['access_level']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($user['User']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($user['User']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>