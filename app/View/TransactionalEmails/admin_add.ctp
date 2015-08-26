	<div class="row">
		<div class="col-md-12">
			<h1><?php echo __('Admin Add Transactional Email'); ?></h1>
		</div>
	</div>

			<?php echo $this->Form->create('TransactionalEmail', array('role' => 'form')); ?>

				<div class="form-group">
					<?php echo $this->Form->input('from_email', array('class' => 'form-control', 'placeholder' => 'From Email'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('to_email', array('class' => 'form-control', 'placeholder' => 'To Email'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('cc', array('class' => 'form-control', 'placeholder' => 'Cc'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('bcc', array('class' => 'form-control', 'placeholder' => 'Bcc'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('subject', array('class' => 'form-control', 'placeholder' => 'Subject'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('template', array('class' => 'form-control', 'placeholder' => 'Template'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('merge_vars', array('class' => 'form-control', 'placeholder' => 'Merge Vars'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('attachments', array('class' => 'form-control', 'placeholder' => 'Attachments'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('is_sent', array('class' => 'form-control', 'placeholder' => 'Is Sent'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('is_delivered', array('class' => 'form-control', 'placeholder' => 'Is Delivered'));?>
				</div>
				<div class="form-group">
					<?php echo $this->Form->input('delivery_datetime', array('class' => 'form-control', 'placeholder' => 'Delivery Datetime'));?>
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

