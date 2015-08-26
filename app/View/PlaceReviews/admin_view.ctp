	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Place Review'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($placeReview['PlaceReview']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Place'); ?></th>
		<td>
			<?php echo $this->Html->link($placeReview['Place']['name'], array('controller' => 'places', 'action' => 'view', $placeReview['Place']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($placeReview['User']['email'], array('controller' => 'users', 'action' => 'view', $placeReview['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Review'); ?></th>
		<td>
			<?php echo h($placeReview['PlaceReview']['review']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($placeReview['PlaceReview']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($placeReview['PlaceReview']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($placeReview['PlaceReview']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($placeReview['PlaceReview']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>