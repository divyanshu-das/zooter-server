	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Edit Group Message'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('GroupMessage', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('id', array('class' => 'form-control', 'placeholder' => 'Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('user_id', array('class' => 'form-control', 'placeholder' => 'User Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('group_id', array('class' => 'form-control', 'placeholder' => 'Group Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('message', array('class' => 'form-control', 'placeholder' => 'Message'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('image', array('class' => 'form-control', 'placeholder' => 'Image'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('video', array('class' => 'form-control', 'placeholder' => 'Video'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

