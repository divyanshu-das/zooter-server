	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Places Cost'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('PlacesCost', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('place_id', array('class' => 'form-control', 'placeholder' => 'Place Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('type', array('class' => 'form-control', 'placeholder' => 'Type'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('amount', array('class' => 'form-control', 'placeholder' => 'Amount'));?>
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

