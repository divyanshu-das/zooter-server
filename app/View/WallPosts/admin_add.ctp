	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Wall Post'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('WallPost', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('user_id', array('class' => 'form-control', 'placeholder' => 'User Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('posted_on_id', array('class' => 'form-control', 'placeholder' => 'Posted On Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('post', array('class' => 'form-control', 'placeholder' => 'Post'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('image', array('class' => 'form-control', 'placeholder' => 'Image'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('location_id', array('class' => 'form-control', 'placeholder' => 'Location Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('hyperlink', array('class' => 'form-control', 'placeholder' => 'Hyperlink'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

