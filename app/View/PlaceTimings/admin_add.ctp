	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Place Timing'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('PlaceTiming', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('place_id', array('class' => 'form-control', 'placeholder' => 'Place Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('day_of_week', array('class' => 'form-control', 'placeholder' => 'Day Of Week'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('time_open', array('class' => 'form-control', 'placeholder' => 'Time Open'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('working_time', array('class' => 'form-control', 'placeholder' => 'Working Time'));?>
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

