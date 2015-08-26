	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Album Contributor'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('AlbumContributor', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('album_id', array('class' => 'form-control', 'placeholder' => 'Album Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('user_id', array('class' => 'form-control', 'placeholder' => 'User Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('deleted', array('class' => 'form-control', 'placeholder' => 'Deleted'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('deleted_date', array('class' => 'form-control', 'placeholder' => 'Deleted Date'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

