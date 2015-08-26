	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Profile'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('Profile', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('user_id', array('class' => 'form-control', 'placeholder' => 'User Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('first_name', array('class' => 'form-control', 'placeholder' => 'First Name'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('middle_name', array('class' => 'form-control', 'placeholder' => 'Middle Name'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('last_name', array('class' => 'form-control', 'placeholder' => 'Last Name'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('gender', array('class' => 'form-control', 'placeholder' => 'Gender'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('date_of_birth', array('class' => 'form-control', 'placeholder' => 'Date Of Birth'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('address', array('class' => 'form-control', 'placeholder' => 'Address'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('city_id', array('class' => 'form-control', 'placeholder' => 'City Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('pincode', array('class' => 'form-control', 'placeholder' => 'Pincode'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('last_logged_in', array('class' => 'form-control', 'placeholder' => 'Last Logged In'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('last_logged_ip', array('class' => 'form-control', 'placeholder' => 'Last Logged Ip'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

