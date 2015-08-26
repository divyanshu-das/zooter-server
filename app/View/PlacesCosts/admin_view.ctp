	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Places Cost'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($placesCost['PlacesCost']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Place'); ?></th>
		<td>
			<?php echo $this->Html->link($placesCost['Place']['name'], array('controller' => 'places', 'action' => 'view', $placesCost['Place']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Type'); ?></th>
		<td>
			<?php echo h($placesCost['PlacesCost']['type']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Amount'); ?></th>
		<td>
			<?php echo h($placesCost['PlacesCost']['amount']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($placesCost['PlacesCost']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($placesCost['PlacesCost']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($placesCost['PlacesCost']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($placesCost['PlacesCost']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>