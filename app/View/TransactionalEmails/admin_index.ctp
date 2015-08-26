	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Transactional Emails'); ?></h1>
		</div><!-- end col md 12 -->
	</div><!-- end row -->


	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<thead>
			<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('from_email'); ?></th>
		<th><?php echo $this->Paginator->sort('to_email'); ?></th>
		<th><?php echo $this->Paginator->sort('cc'); ?></th>
		<th><?php echo $this->Paginator->sort('bcc'); ?></th>
		<th><?php echo $this->Paginator->sort('subject'); ?></th>
		<th><?php echo $this->Paginator->sort('template'); ?></th>
		<th><?php echo $this->Paginator->sort('merge_vars'); ?></th>
		<th><?php echo $this->Paginator->sort('attachments'); ?></th>
		<th><?php echo $this->Paginator->sort('is_sent'); ?></th>
		<th><?php echo $this->Paginator->sort('is_delivered'); ?></th>
		<th><?php echo $this->Paginator->sort('delivery_datetime'); ?></th>
		<th><?php echo $this->Paginator->sort('deleted'); ?></th>
		<th><?php echo $this->Paginator->sort('deleted_date'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($transactionalEmails as $transactionalEmail): ?>
					<tr>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['id']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['from_email']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['to_email']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['cc']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['bcc']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['subject']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['template']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['merge_vars']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['attachments']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['is_sent']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['is_delivered']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['delivery_datetime']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['deleted']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['deleted_date']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['created']); ?>&nbsp;</td>
						<td><?php echo h($transactionalEmail['TransactionalEmail']['modified']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $transactionalEmail['TransactionalEmail']['id']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $transactionalEmail['TransactionalEmail']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $transactionalEmail['TransactionalEmail']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $transactionalEmail['TransactionalEmail']['id'])); ?>
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
