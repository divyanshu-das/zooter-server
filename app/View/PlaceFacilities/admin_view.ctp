	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Place Facility'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Place'); ?></th>
		<td>
			<?php echo $this->Html->link($placeFacility['Place']['name'], array('controller' => 'places', 'action' => 'view', $placeFacility['Place']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Ground'); ?></th>
		<td>
			<?php echo $this->Html->link($placeFacility['Ground']['name'], array('controller' => 'grounds', 'action' => 'view', $placeFacility['Ground']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Bowling Machine Count'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['bowling_machine_count']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Coach Student Ratio'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['coach_student_ratio']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Accomodation'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['accomodation']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Transport'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['transport']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Turf Nets'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['turf_nets']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Cement Nets'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['cement_nets']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Mat Nets'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['mat_nets']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Cuisine'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['cuisine']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Has Individual Classes'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['has_individual_classes']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Has Medical Facilities'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['has_medical_facilities']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Has Gym'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['has_gym']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Has Food'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['has_food']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Has Karyoke'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['has_karyoke']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Has Wifi'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['has_wifi']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Accept Credit Card'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['accept_credit_card']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($placeFacility['PlaceFacility']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>