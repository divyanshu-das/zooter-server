	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Fan Club Favorite'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($fanClubFavorite['FanClubFavorite']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Fan Club'); ?></th>
		<td>
			<?php echo $this->Html->link($fanClubFavorite['FanClub']['name'], array('controller' => 'fan_clubs', 'action' => 'view', $fanClubFavorite['FanClub']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Favorite Id'); ?></th>
		<td>
			<?php echo h($fanClubFavorite['FanClubFavorite']['favorite_id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Type'); ?></th>
		<td>
			<?php echo h($fanClubFavorite['FanClubFavorite']['type']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($fanClubFavorite['FanClubFavorite']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($fanClubFavorite['FanClubFavorite']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($fanClubFavorite['FanClubFavorite']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($fanClubFavorite['FanClubFavorite']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>