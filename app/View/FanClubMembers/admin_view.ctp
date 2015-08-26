	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Fan Club Member'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($fanClubMember['FanClubMember']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Fan Club'); ?></th>
		<td>
			<?php echo $this->Html->link($fanClubMember['FanClub']['name'], array('controller' => 'fan_clubs', 'action' => 'view', $fanClubMember['FanClub']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($fanClubMember['User']['email'], array('controller' => 'users', 'action' => 'view', $fanClubMember['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Type'); ?></th>
		<td>
			<?php echo h($fanClubMember['FanClubMember']['type']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Level'); ?></th>
		<td>
			<?php echo h($fanClubMember['FanClubMember']['level']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Status'); ?></th>
		<td>
			<?php echo h($fanClubMember['FanClubMember']['status']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($fanClubMember['FanClubMember']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($fanClubMember['FanClubMember']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($fanClubMember['FanClubMember']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($fanClubMember['FanClubMember']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>