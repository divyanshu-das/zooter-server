	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Wall Post'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($wallPost['WallPost']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($wallPost['User']['email'], array('controller' => 'users', 'action' => 'view', $wallPost['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Posted On Id'); ?></th>
		<td>
			<?php echo h($wallPost['WallPost']['posted_on_id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Post'); ?></th>
		<td>
			<?php echo h($wallPost['WallPost']['post']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Image'); ?></th>
		<td>
			<?php echo h($wallPost['WallPost']['image']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Location'); ?></th>
		<td>
			<?php echo $this->Html->link($wallPost['Location']['name'], array('controller' => 'locations', 'action' => 'view', $wallPost['Location']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Hyperlink'); ?></th>
		<td>
			<?php echo h($wallPost['WallPost']['hyperlink']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($wallPost['WallPost']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($wallPost['WallPost']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>