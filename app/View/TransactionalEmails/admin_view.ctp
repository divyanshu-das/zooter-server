	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Transactional Email'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('From Email'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['from_email']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('To Email'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['to_email']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Cc'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['cc']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Bcc'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['bcc']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Subject'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['subject']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Template'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['template']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Merge Vars'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['merge_vars']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Attachments'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['attachments']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Is Sent'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['is_sent']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Is Delivered'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['is_delivered']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Delivery Datetime'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['delivery_datetime']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($transactionalEmail['TransactionalEmail']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>