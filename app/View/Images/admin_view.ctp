	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Image'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($image['Image']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Caption'); ?></th>
		<td>
			<?php echo h($image['Image']['caption']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Url'); ?></th>
		<td>
			<?php echo h($image['Image']['url']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Album'); ?></th>
		<td>
			<?php echo $this->Html->link($image['Album']['name'], array('controller' => 'albums', 'action' => 'view', $image['Album']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Location'); ?></th>
		<td>
			<?php echo $this->Html->link($image['Location']['name'], array('controller' => 'locations', 'action' => 'view', $image['Location']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Snap Date Time'); ?></th>
		<td>
			<?php echo h($image['Image']['snap_date_time']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($image['User']['email'], array('controller' => 'users', 'action' => 'view', $image['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($image['Image']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($image['Image']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($image['Image']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($image['Image']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>