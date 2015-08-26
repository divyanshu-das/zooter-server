	<div class="row">
		<div class="col-md-12">
				<h1><?php echo __('Album Contributor'); ?></h1>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
		<tbody>
		<tr>
		<th><?php echo __('Id'); ?></th>
		<td>
			<?php echo h($albumContributor['AlbumContributor']['id']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Album'); ?></th>
		<td>
			<?php echo $this->Html->link($albumContributor['Album']['name'], array('controller' => 'albums', 'action' => 'view', $albumContributor['Album']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('User'); ?></th>
		<td>
			<?php echo $this->Html->link($albumContributor['User']['email'], array('controller' => 'users', 'action' => 'view', $albumContributor['User']['id'])); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted'); ?></th>
		<td>
			<?php echo h($albumContributor['AlbumContributor']['deleted']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Deleted Date'); ?></th>
		<td>
			<?php echo h($albumContributor['AlbumContributor']['deleted_date']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Created'); ?></th>
		<td>
			<?php echo h($albumContributor['AlbumContributor']['created']); ?>
			&nbsp;
		</td>
</tr>
<tr>
		<th><?php echo __('Modified'); ?></th>
		<td>
			<?php echo h($albumContributor['AlbumContributor']['modified']); ?>
			&nbsp;
		</td>
</tr>
		</tbody>
	</table>