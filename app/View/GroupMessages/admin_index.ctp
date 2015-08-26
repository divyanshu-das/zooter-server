	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Group Messages'); ?></h1>
		</div><!-- end col md 12 -->
	</div><!-- end row -->


	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<thead>
			<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('user_id'); ?></th>
		<th><?php echo $this->Paginator->sort('group_id'); ?></th>
		<th><?php echo $this->Paginator->sort('message'); ?></th>
		<th><?php echo $this->Paginator->sort('image'); ?></th>
		<th><?php echo $this->Paginator->sort('video'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($groupMessages as $groupMessage): ?>
					<tr>
						<td><?php echo h($groupMessage['GroupMessage']['id']); ?>&nbsp;</td>
								<td>
			<?php echo $this->Html->link($groupMessage['User']['email'], array('controller' => 'users', 'action' => 'view', $groupMessage['User']['id'])); ?>
		</td>
								<td>
			<?php echo $this->Html->link($groupMessage['Group']['name'], array('controller' => 'groups', 'action' => 'view', $groupMessage['Group']['id'])); ?>
		</td>
						<td><?php echo h($groupMessage['GroupMessage']['message']); ?>&nbsp;</td>
						<td><?php echo h($groupMessage['GroupMessage']['image']); ?>&nbsp;</td>
						<td><?php echo h($groupMessage['GroupMessage']['video']); ?>&nbsp;</td>
						<td><?php echo h($groupMessage['GroupMessage']['created']); ?>&nbsp;</td>
						<td><?php echo h($groupMessage['GroupMessage']['modified']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $groupMessage['GroupMessage']['id']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $groupMessage['GroupMessage']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $groupMessage['GroupMessage']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $groupMessage['GroupMessage']['id'])); ?>
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
