	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Edit Wall Post Comment'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('WallPostComment', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('id', array('class' => 'form-control', 'placeholder' => 'Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('user_id', array('class' => 'form-control', 'placeholder' => 'User Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('comment', array('class' => 'form-control', 'placeholder' => 'Comment'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('wall_post_id', array('class' => 'form-control', 'placeholder' => 'Wall Post Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>
				</div>

			<?php echo $this->Form->end() ?>

