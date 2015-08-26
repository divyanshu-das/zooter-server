	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Verified User'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($verifiedUser['VerifiedUser']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Twitter Handle'); ?></th>
		<td>
			<?php echo h($verifiedUser['VerifiedUser']['twitter_handle']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Email'); ?></th>
		<td>
			<?php echo h($verifiedUser['VerifiedUser']['email']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Phone'); ?></th>
		<td>
			<?php echo h($verifiedUser['VerifiedUser']['phone']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('First Name'); ?></th>
		<td>
			<?php echo h($verifiedUser['VerifiedUser']['first_name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Last Name'); ?></th>
		<td>
			<?php echo h($verifiedUser['VerifiedUser']['last_name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($verifiedUser['VerifiedUser']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($verifiedUser['VerifiedUser']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($verifiedUser['VerifiedUser']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($verifiedUser['VerifiedUser']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>