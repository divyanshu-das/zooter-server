	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Crm Email'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('From Email'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['from_email']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('To Email'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['to_email']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Subject'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['subject']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Template'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['template']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Merge Vars'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['merge_vars']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Is Sent'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['is_sent']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Is Delivered'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['is_delivered']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Delivery Datetime'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['delivery_datetime']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($crmEmail['CrmEmail']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>