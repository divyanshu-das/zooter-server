	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Fan Club'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($fanClub['FanClub']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Name'); ?></th>
		<td>
			<?php echo h($fanClub['FanClub']['name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($fanClub['User']['email'], array('controller' => 'users', 'action' => 'view', $fanClub['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Image'); ?></th>
		<td>
			<?php echo $this->Html->link($fanClub['Image']['caption'], array('controller' => 'images', 'action' => 'view', $fanClub['Image']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Cover Image Id'); ?></th>
		<td>
			<?php echo h($fanClub['FanClub']['cover_image_id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Tagline'); ?></th>
		<td>
			<?php echo h($fanClub['FanClub']['tagline']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($fanClub['FanClub']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($fanClub['FanClub']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($fanClub['FanClub']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($fanClub['FanClub']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>