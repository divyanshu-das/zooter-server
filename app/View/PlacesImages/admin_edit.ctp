	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Edit Places Image'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('PlacesImage', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('id', array('class' => 'form-control', 'placeholder' => 'Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('place_id', array('class' => 'form-control', 'placeholder' => 'Place Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('image_id', array('class' => 'form-control', 'placeholder' => 'Image Id'));?>
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

