	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Wall Post Comment'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($wallPostComment['WallPostComment']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($wallPostComment['User']['email'], array('controller' => 'users', 'action' => 'view', $wallPostComment['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Comment'); ?></th>
		<td>
			<?php echo h($wallPostComment['WallPostComment']['comment']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Wall Post'); ?></th>
		<td>
			<?php echo $this->Html->link($wallPostComment['WallPost']['id'], array('controller' => 'wall_posts', 'action' => 'view', $wallPostComment['WallPost']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($wallPostComment['WallPostComment']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($wallPostComment['WallPostComment']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>