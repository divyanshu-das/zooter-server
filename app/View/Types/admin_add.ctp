	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Type'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('Type', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('type', array('class' => 'form-control', 'placeholder' => 'Type'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

