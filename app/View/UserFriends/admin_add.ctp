	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add User Friend'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('UserFriend', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('user_id', array('class' => 'form-control', 'placeholder' => 'User Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('friend_id', array('class' => 'form-control', 'placeholder' => 'Friend Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('status', array('class' => 'form-control', 'placeholder' => 'Status'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

