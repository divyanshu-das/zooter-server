	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Places Image'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($placesImage['PlacesImage']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Place'); ?></th>
		<td>
			<?php echo $this->Html->link($placesImage['Place']['name'], array('controller' => 'places', 'action' => 'view', $placesImage['Place']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Image'); ?></th>
		<td>
			<?php echo $this->Html->link($placesImage['Image']['caption'], array('controller' => 'images', 'action' => 'view', $placesImage['Image']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($placesImage['PlacesImage']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($placesImage['PlacesImage']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($placesImage['PlacesImage']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($placesImage['PlacesImage']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>