	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Movie'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('Movie', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('name', array('class' => 'form-control', 'placeholder' => 'Name'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('genre', array('class' => 'form-control', 'placeholder' => 'Genre'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('rating', array('class' => 'form-control', 'placeholder' => 'Rating'));?>
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

