	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Fan Club Favorite Member'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($fanClubFavoriteMember['FanClubFavoriteMember']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Fan Club'); ?></th>
		<td>
			<?php echo $this->Html->link($fanClubFavoriteMember['FanClub']['name'], array('controller' => 'fan_clubs', 'action' => 'view', $fanClubFavoriteMember['FanClub']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($fanClubFavoriteMember['User']['email'], array('controller' => 'users', 'action' => 'view', $fanClubFavoriteMember['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($fanClubFavoriteMember['FanClubFavoriteMember']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($fanClubFavoriteMember['FanClubFavoriteMember']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($fanClubFavoriteMember['FanClubFavoriteMember']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($fanClubFavoriteMember['FanClubFavoriteMember']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>