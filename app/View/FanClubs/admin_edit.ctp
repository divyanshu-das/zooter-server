	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Edit Fan Club'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('FanClub', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('id', array('class' => 'form-control', 'placeholder' => 'Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('name', array('class' => 'form-control', 'placeholder' => 'Name'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('user_id', array('class' => 'form-control', 'placeholder' => 'User Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('image_id', array('class' => 'form-control', 'placeholder' => 'Image Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('cover_image_id', array('class' => 'form-control', 'placeholder' => 'Cover Image Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('tagline', array('class' => 'form-control', 'placeholder' => 'Tagline'));?>
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

