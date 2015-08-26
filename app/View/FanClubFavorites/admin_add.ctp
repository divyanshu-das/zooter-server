	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Fan Club Favorite'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('FanClubFavorite', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('fan_club_id', array('class' => 'form-control', 'placeholder' => 'Fan Club Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('favorite_id', array('class' => 'form-control', 'placeholder' => 'Favorite Id'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('type', array('class' => 'form-control', 'placeholder' => 'Type'));?>
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

