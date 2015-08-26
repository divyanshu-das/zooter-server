	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Users'); ?></h1>
		</div><!-- end col md 12 -->
	</div><!-- end row -->


	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<thead>
			<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('email'); ?></th>
		<th><?php echo $this->Paginator->sort('username'); ?></th>
		<th><?php echo $this->Paginator->sort('phone'); ?></th>
		<th><?php echo $this->Paginator->sort('is_verified'); ?></th>
		<th><?php echo $this->Paginator->sort('type_id'); ?></th>
		<th><?php echo $this->Paginator->sort('role'); ?></th>
		<th><?php echo $this->Paginator->sort('access_level'); ?></th>
		<th><?php echo $this->Paginator->sort('created', 'Join Date'); ?></th>
		<th class="actions"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($users as $user): ?>
					<tr>
						<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
						<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
						<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
						<td><?php echo h($user['User']['phone']); ?>&nbsp;</td>
						<td><?php echo h($user['User']['is_verified']); ?>&nbsp;</td>
						<td>
							<?php echo $this->Html->link($user['Type']['type'], array('controller' => 'types', 'action' => 'view', $user['Type']['id'])); ?>
						</td>
						<td><?php echo h($user['User']['role']); ?>&nbsp;</td>
						<td><?php echo UserLevel::stringValue($user['User']['access_level']); ?>&nbsp;</td>
						<td><?php echo h($user['User']['created']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $user['User']['id']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $user['User']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $user['User']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
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
