	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Verified Users'); ?></h1>
		</div><!-- end col md 12 -->
	</div><!-- end row -->


	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<thead>
			<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('twitter_handle'); ?></th>
		<th><?php echo $this->Paginator->sort('email'); ?></th>
		<th><?php echo $this->Paginator->sort('phone'); ?></th>
		<th><?php echo $this->Paginator->sort('first_name'); ?></th>
		<th><?php echo $this->Paginator->sort('last_name'); ?></th>
		<th><?php echo $this->Paginator->sort('deleted'); ?></th>
		<th><?php echo $this->Paginator->sort('deleted_date'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($verifiedUsers as $verifiedUser): ?>
					<tr>
						<td><?php echo h($verifiedUser['VerifiedUser']['id']); ?>&nbsp;</td>
						<td><?php echo h($verifiedUser['VerifiedUser']['twitter_handle']); ?>&nbsp;</td>
						<td><?php echo h($verifiedUser['VerifiedUser']['email']); ?>&nbsp;</td>
						<td><?php echo h($verifiedUser['VerifiedUser']['phone']); ?>&nbsp;</td>
						<td><?php echo h($verifiedUser['VerifiedUser']['first_name']); ?>&nbsp;</td>
						<td><?php echo h($verifiedUser['VerifiedUser']['last_name']); ?>&nbsp;</td>
						<td><?php echo h($verifiedUser['VerifiedUser']['deleted']); ?>&nbsp;</td>
						<td><?php echo h($verifiedUser['VerifiedUser']['deleted_date']); ?>&nbsp;</td>
						<td><?php echo h($verifiedUser['VerifiedUser']['created']); ?>&nbsp;</td>
						<td><?php echo h($verifiedUser['VerifiedUser']['modified']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $verifiedUser['VerifiedUser']['id']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $verifiedUser['VerifiedUser']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $verifiedUser['VerifiedUser']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $verifiedUser['VerifiedUser']['id'])); ?>
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
