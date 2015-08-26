	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Location'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('Location', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('name', array('class' => 'form-control', 'placeholder' => 'Name'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('unique_identifier', array('class' => 'form-control', 'placeholder' => 'Unique Identifier'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('latitude', array('class' => 'form-control', 'placeholder' => 'Latitude'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('longitude', array('class' => 'form-control', 'placeholder' => 'Longitude'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

