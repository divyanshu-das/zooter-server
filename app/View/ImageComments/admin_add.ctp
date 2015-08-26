	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Image Comment'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('ImageComment', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('comment', array('class' => 'form-control', 'placeholder' => 'Comment'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('url', array('class' => 'form-control', 'placeholder' => 'Url'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('image_id', array('class' => 'form-control', 'placeholder' => 'Image Id'));?>
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

