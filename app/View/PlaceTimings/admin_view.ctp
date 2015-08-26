	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Place Timing'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($placeTiming['PlaceTiming']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Place'); ?></th>
		<td>
			<?php echo $this->Html->link($placeTiming['Place']['name'], array('controller' => 'places', 'action' => 'view', $placeTiming['Place']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Day Of Week'); ?></th>
		<td>
			<?php echo h($placeTiming['PlaceTiming']['day_of_week']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Time Open'); ?></th>
		<td>
			<?php echo h($placeTiming['PlaceTiming']['time_open']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Working Time'); ?></th>
		<td>
			<?php echo h($placeTiming['PlaceTiming']['working_time']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($placeTiming['PlaceTiming']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($placeTiming['PlaceTiming']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($placeTiming['PlaceTiming']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($placeTiming['PlaceTiming']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>