	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Places Essential'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($placesEssential['PlacesEssential']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Place'); ?></th>
		<td>
			<?php echo $this->Html->link($placesEssential['Place']['name'], array('controller' => 'places', 'action' => 'view', $placesEssential['Place']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Location'); ?></th>
		<td>
			<?php echo $this->Html->link($placesEssential['Location']['name'], array('controller' => 'locations', 'action' => 'view', $placesEssential['Location']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Type'); ?></th>
		<td>
			<?php echo h($placesEssential['PlacesEssential']['type']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Distance'); ?></th>
		<td>
			<?php echo h($placesEssential['PlacesEssential']['distance']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($placesEssential['PlacesEssential']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($placesEssential['PlacesEssential']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($placesEssential['PlacesEssential']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($placesEssential['PlacesEssential']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>