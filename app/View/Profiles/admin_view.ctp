	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Profile'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($profile['Profile']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($profile['User']['username'], array('controller' => 'users', 'action' => 'view', $profile['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('First Name'); ?></th>
		<td>
			<?php echo h($profile['Profile']['first_name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Middle Name'); ?></th>
		<td>
			<?php echo h($profile['Profile']['middle_name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Last Name'); ?></th>
		<td>
			<?php echo h($profile['Profile']['last_name']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Gender'); ?></th>
		<td>
			<?php echo h($profile['Profile']['gender']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Date Of Birth'); ?></th>
		<td>
			<?php echo h($profile['Profile']['date_of_birth']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Address'); ?></th>
		<td>
			<?php echo h($profile['Profile']['address']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('City Id'); ?></th>
		<td>
			<?php echo h($profile['Profile']['city_id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Pincode'); ?></th>
		<td>
			<?php echo h($profile['Profile']['pincode']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Last Logged In'); ?></th>
		<td>
			<?php echo h($profile['Profile']['last_logged_in']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Last Logged Ip'); ?></th>
		<td>
			<?php echo h($profile['Profile']['last_logged_ip']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($profile['Profile']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($profile['Profile']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>