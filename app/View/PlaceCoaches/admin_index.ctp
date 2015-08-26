	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Place Coaches'); ?></h1>
		</div><!-- end col md 12 -->
	</div><!-- end row -->


	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<thead>
			<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('place_id'); ?></th>
		<th><?php echo $this->Paginator->sort('user_id'); ?></th>
		<th><?php echo $this->Paginator->sort('deleted'); ?></th>
		<th><?php echo $this->Paginator->sort('deleted_date'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($placeCoaches as $placeCoach): ?>
					<tr>
						<td><?php echo h($placeCoach['PlaceCoach']['id']); ?>&nbsp;</td>
								<td>
			<?php echo $this->Html->link($placeCoach['Place']['name'], array('controller' => 'places', 'action' => 'view', $placeCoach['Place']['id'])); ?>
		</td>
								<td>
			<?php echo $this->Html->link($placeCoach['User']['email'], array('controller' => 'users', 'action' => 'view', $placeCoach['User']['id'])); ?>
		</td>
						<td><?php echo h($placeCoach['PlaceCoach']['deleted']); ?>&nbsp;</td>
						<td><?php echo h($placeCoach['PlaceCoach']['deleted_date']); ?>&nbsp;</td>
						<td><?php echo h($placeCoach['PlaceCoach']['created']); ?>&nbsp;</td>
						<td><?php echo h($placeCoach['PlaceCoach']['modified']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $placeCoach['PlaceCoach']['id']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $placeCoach['PlaceCoach']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $placeCoach['PlaceCoach']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $placeCoach['PlaceCoach']['id'])); ?>
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
