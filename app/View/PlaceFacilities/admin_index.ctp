	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Place Facilities'); ?></h1>
		</div><!-- end col md 12 -->
	</div><!-- end row -->


	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<thead>
			<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('place_id'); ?></th>
		<th><?php echo $this->Paginator->sort('ground_id'); ?></th>
		<th><?php echo $this->Paginator->sort('bowling_machine_count'); ?></th>
		<th><?php echo $this->Paginator->sort('coach_student_ratio'); ?></th>
		<th><?php echo $this->Paginator->sort('accomodation'); ?></th>
		<th><?php echo $this->Paginator->sort('transport'); ?></th>
		<th><?php echo $this->Paginator->sort('turf_nets'); ?></th>
		<th><?php echo $this->Paginator->sort('cement_nets'); ?></th>
		<th><?php echo $this->Paginator->sort('mat_nets'); ?></th>
		<th><?php echo $this->Paginator->sort('cuisine'); ?></th>
		<th><?php echo $this->Paginator->sort('has_individual_classes'); ?></th>
		<th><?php echo $this->Paginator->sort('has_medical_facilities'); ?></th>
		<th><?php echo $this->Paginator->sort('has_gym'); ?></th>
		<th><?php echo $this->Paginator->sort('has_food'); ?></th>
		<th><?php echo $this->Paginator->sort('has_karyoke'); ?></th>
		<th><?php echo $this->Paginator->sort('has_wifi'); ?></th>
		<th><?php echo $this->Paginator->sort('accept_credit_card'); ?></th>
		<th><?php echo $this->Paginator->sort('deleted'); ?></th>
		<th><?php echo $this->Paginator->sort('deleted_date'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($placeFacilities as $placeFacility): ?>
					<tr>
						<td><?php echo h($placeFacility['PlaceFacility']['id']); ?>&nbsp;</td>
								<td>
			<?php echo $this->Html->link($placeFacility['Place']['name'], array('controller' => 'places', 'action' => 'view', $placeFacility['Place']['id'])); ?>
		</td>
								<td>
			<?php echo $this->Html->link($placeFacility['Ground']['name'], array('controller' => 'grounds', 'action' => 'view', $placeFacility['Ground']['id'])); ?>
		</td>
						<td><?php echo h($placeFacility['PlaceFacility']['bowling_machine_count']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['coach_student_ratio']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['accomodation']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['transport']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['turf_nets']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['cement_nets']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['mat_nets']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['cuisine']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['has_individual_classes']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['has_medical_facilities']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['has_gym']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['has_food']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['has_karyoke']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['has_wifi']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['accept_credit_card']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['deleted']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['deleted_date']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['created']); ?>&nbsp;</td>
						<td><?php echo h($placeFacility['PlaceFacility']['modified']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $placeFacility['PlaceFacility']['id']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $placeFacility['PlaceFacility']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $placeFacility['PlaceFacility']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $placeFacility['PlaceFacility']['id'])); ?>
						</td>
					</tr>
				<?php endforeach; ?>
		</tbody>
	</table>

	<p>
		<small><?php echo $this->Paginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?></small>
	</p>

	<?php
			$params = $this->Paginator->params();
			if ($params['pageCount'] > 1) {
			?>
	<ul class="pagination pagination-sm">
		<?php
					echo $this->Paginator->prev('&larr; Previous', array('class' => 'prev','tag' => 'li','escape' => false), '<a onclick="return false;">&larr; Previous</a>', array('class' => 'prev disabled','tag' => 'li','escape' => false));
					echo $this->Paginator->numbers(array('separator' => '','tag' => 'li','currentClass' => 'active','currentTag' => 'a'));
					echo $this->Paginator->next('Next &rarr;', array('class' => 'next','tag' => 'li','escape' => false), '<a onclick="return false;">Next &rarr;</a>', array('class' => 'next disabled','tag' => 'li','escape' => false));
				?>
	</ul>
	<?php } ?>
