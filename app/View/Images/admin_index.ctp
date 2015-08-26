	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Images'); ?></h1>
		</div><!-- end col md 12 -->
	</div><!-- end row -->


	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<thead>
			<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('caption'); ?></th>
		<th><?php echo $this->Paginator->sort('url'); ?></th>
		<th><?php echo $this->Paginator->sort('album_id'); ?></th>
		<th><?php echo $this->Paginator->sort('location_id'); ?></th>
		<th><?php echo $this->Paginator->sort('snap_date_time'); ?></th>
		<th><?php echo $this->Paginator->sort('user_id'); ?></th>
		<th><?php echo $this->Paginator->sort('deleted'); ?></th>
		<th><?php echo $this->Paginator->sort('deleted_date'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($images as $image): ?>
					<tr>
						<td><?php echo h($image['Image']['id']); ?>&nbsp;</td>
						<td><?php echo h($image['Image']['caption']); ?>&nbsp;</td>
						<td><?php echo h($image['Image']['url']); ?>&nbsp;</td>
								<td>
			<?php echo $this->Html->link($image['Album']['name'], array('controller' => 'albums', 'action' => 'view', $image['Album']['id'])); ?>
		</td>
								<td>
			<?php echo $this->Html->link($image['Location']['name'], array('controller' => 'locations', 'action' => 'view', $image['Location']['id'])); ?>
		</td>
						<td><?php echo h($image['Image']['snap_date_time']); ?>&nbsp;</td>
								<td>
			<?php echo $this->Html->link($image['User']['email'], array('controller' => 'users', 'action' => 'view', $image['User']['id'])); ?>
		</td>
						<td><?php echo h($image['Image']['deleted']); ?>&nbsp;</td>
						<td><?php echo h($image['Image']['deleted_date']); ?>&nbsp;</td>
						<td><?php echo h($image['Image']['created']); ?>&nbsp;</td>
						<td><?php echo h($image['Image']['modified']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $image['Image']['id']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $image['Image']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $image['Image']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $image['Image']['id'])); ?>
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
