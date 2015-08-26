	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Image Comment'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($imageComment['ImageComment']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Comment'); ?></th>
		<td>
			<?php echo h($imageComment['ImageComment']['comment']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Url'); ?></th>
		<td>
			<?php echo h($imageComment['ImageComment']['url']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Image'); ?></th>
		<td>
			<?php echo $this->Html->link($imageComment['Image']['caption'], array('controller' => 'images', 'action' => 'view', $imageComment['Image']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($imageComment['User']['email'], array('controller' => 'users', 'action' => 'view', $imageComment['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($imageComment['ImageComment']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($imageComment['ImageComment']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($imageComment['ImageComment']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($imageComment['ImageComment']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>