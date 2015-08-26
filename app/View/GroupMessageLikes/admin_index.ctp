	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Group Message Likes'); ?></h1>
		</div><!-- end col md 12 -->
	</div><!-- end row -->


	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<thead>
			<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('user_id'); ?></th>
		<th><?php echo $this->Paginator->sort('group_message_id'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($groupMessageLikes as $groupMessageLike): ?>
					<tr>
						<td><?php echo h($groupMessageLike['GroupMessageLike']['id']); ?>&nbsp;</td>
								<td>
			<?php echo $this->Html->link($groupMessageLike['User']['email'], array('controller' => 'users', 'action' => 'view', $groupMessageLike['User']['id'])); ?>
		</td>
								<td>
			<?php echo $this->Html->link($groupMessageLike['GroupMessage']['id'], array('controller' => 'group_messages', 'action' => 'view', $groupMessageLike['GroupMessage']['id'])); ?>
		</td>
						<td><?php echo h($groupMessageLike['GroupMessageLike']['created']); ?>&nbsp;</td>
						<td><?php echo h($groupMessageLike['GroupMessageLike']['modified']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $groupMessageLike['GroupMessageLike']['id']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $groupMessageLike['GroupMessageLike']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $groupMessageLike['GroupMessageLike']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $groupMessageLike['GroupMessageLike']['id'])); ?>
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
