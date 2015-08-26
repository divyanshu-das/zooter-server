	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Ground'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('Ground', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('name', array('class' => 'form-control', 'placeholder' => 'Name'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('location_id', array('class' => 'form-control', 'placeholder' => 'Location Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('contact_name', array('class' => 'form-control', 'placeholder' => 'Contact Name'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('contact_number', array('class' => 'form-control', 'placeholder' => 'Contact Number'));?>
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

