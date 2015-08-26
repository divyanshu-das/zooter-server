	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Group Message Comment'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('GroupMessageComment', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('user_id', array('class' => 'form-control', 'placeholder' => 'User Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('group_message_id', array('class' => 'form-control', 'placeholder' => 'Group Message Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('message', array('class' => 'form-control', 'placeholder' => 'Message'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

