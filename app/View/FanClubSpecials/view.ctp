	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Fan Club Special'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($fanClubSpecial['FanClubSpecial']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Fan Club'); ?></th>
		<td>
			<?php echo $this->Html->link($fanClubSpecial['FanClub']['name'], array('controller' => 'fan_clubs', 'action' => 'view', $fanClubSpecial['FanClub']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($fanClubSpecial['User']['email'], array('controller' => 'users', 'action' => 'view', $fanClubSpecial['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Type'); ?></th>
		<td>
			<?php echo h($fanClubSpecial['FanClubSpecial']['type']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Status'); ?></th>
		<td>
			<?php echo h($fanClubSpecial['FanClubSpecial']['status']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($fanClubSpecial['FanClubSpecial']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($fanClubSpecial['FanClubSpecial']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($fanClubSpecial['FanClubSpecial']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($fanClubSpecial['FanClubSpecial']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>