	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Edit Image'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('Image', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('id', array('class' => 'form-control', 'placeholder' => 'Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('caption', array('class' => 'form-control', 'placeholder' => 'Caption'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('url', array('class' => 'form-control', 'placeholder' => 'Url'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('album_id', array('class' => 'form-control', 'placeholder' => 'Album Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('location_id', array('class' => 'form-control', 'placeholder' => 'Location Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('snap_date_time', array('class' => 'form-control', 'placeholder' => 'Snap Date Time'));?>
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

