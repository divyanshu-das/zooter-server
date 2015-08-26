	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Favorite Place'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($favoritePlace['FavoritePlace']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($favoritePlace['User']['email'], array('controller' => 'users', 'action' => 'view', $favoritePlace['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Place'); ?></th>
		<td>
			<?php echo $this->Html->link($favoritePlace['Place']['name'], array('controller' => 'places', 'action' => 'view', $favoritePlace['Place']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($favoritePlace['FavoritePlace']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($favoritePlace['FavoritePlace']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($favoritePlace['FavoritePlace']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($favoritePlace['FavoritePlace']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>