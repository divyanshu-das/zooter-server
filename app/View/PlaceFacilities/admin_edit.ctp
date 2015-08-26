	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Edit Place Facility'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('PlaceFacility', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('id', array('class' => 'form-control', 'placeholder' => 'Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('place_id', array('class' => 'form-control', 'placeholder' => 'Place Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('ground_id', array('class' => 'form-control', 'placeholder' => 'Ground Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('bowling_machine_count', array('class' => 'form-control', 'placeholder' => 'Bowling Machine Count'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('coach_student_ratio', array('class' => 'form-control', 'placeholder' => 'Coach Student Ratio'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('accomodation', array('class' => 'form-control', 'placeholder' => 'Accomodation'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('transport', array('class' => 'form-control', 'placeholder' => 'Transport'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('turf_nets', array('class' => 'form-control', 'placeholder' => 'Turf Nets'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('cement_nets', array('class' => 'form-control', 'placeholder' => 'Cement Nets'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('mat_nets', array('class' => 'form-control', 'placeholder' => 'Mat Nets'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('cuisine', array('class' => 'form-control', 'placeholder' => 'Cuisine'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('has_individual_classes', array('class' => 'form-control', 'placeholder' => 'Has Individual Classes'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('has_medical_facilities', array('class' => 'form-control', 'placeholder' => 'Has Medical Facilities'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('has_gym', array('class' => 'form-control', 'placeholder' => 'Has Gym'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('has_food', array('class' => 'form-control', 'placeholder' => 'Has Food'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('has_karyoke', array('class' => 'form-control', 'placeholder' => 'Has Karyoke'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('has_wifi', array('class' => 'form-control', 'placeholder' => 'Has Wifi'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('accept_credit_card', array('class' => 'form-control', 'placeholder' => 'Accept Credit Card'));?>
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

