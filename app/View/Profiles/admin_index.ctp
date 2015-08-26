	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Profiles'); ?></h1>
		</div><!-- end col md 12 -->
	</div><!-- end row -->


	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<thead>
			<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('user_id'); ?></th>
		<th><?php echo $this->Paginator->sort('first_name'); ?></th>
		<th><?php echo $this->Paginator->sort('middle_name'); ?></th>
		<th><?php echo $this->Paginator->sort('last_name'); ?></th>
		<th><?php echo $this->Paginator->sort('gender'); ?></th>
		<th><?php echo $this->Paginator->sort('date_of_birth'); ?></th>
		<th><?php echo $this->Paginator->sort('address'); ?></th>
		<th><?php echo $this->Paginator->sort('city_id'); ?></th>
		<th><?php echo $this->Paginator->sort('pincode'); ?></th>
		<th><?php echo $this->Paginator->sort('last_logged_in'); ?></th>
		<th><?php echo $this->Paginator->sort('last_logged_ip'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($profiles as $profile): ?>
					<tr>
						<td><?php echo h($profile['Profile']['id']); ?>&nbsp;</td>
								<td>
			<?php echo $this->Html->link($profile['User']['username'], array('controller' => 'users', 'action' => 'view', $profile['User']['id'])); ?>
		</td>
						<td><?php echo h($profile['Profile']['first_name']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['middle_name']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['last_name']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['gender']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['date_of_birth']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['address']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['city_id']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['pincode']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['last_logged_in']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['last_logged_ip']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['created']); ?>&nbsp;</td>
						<td><?php echo h($profile['Profile']['modified']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-search"></span>', array('action' => 'view', $profile['Profile']['id']), array('escape' => false)); ?>
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $profile['Profile']['id']), array('escape' => false)); ?>
							<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $profile['Profile']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $profile['Profile']['id'])); ?>
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
