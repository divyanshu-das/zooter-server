<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <?php echo $this->Html->link(__('Zooter Admin Panel'), '/', array('class' => 'navbar-brand')) ?>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="active"><?php echo $this->Html->link(__('Home'), array('controller' => 'users', 'action' => 'index', 'admin' => true)) ?></li>
        <li class="dropdown">
          <?php echo $this->Html->link(__('Users') . ' <b class="caret"></b>', 'javascript:void(0);', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'escape' => false)); ?>
          <ul class="dropdown-menu">
            <li><?php echo $this->Html->link(__('Manage Users'), array('controller' => 'users', 'action' => 'index', 'admin' => true)) ?></li>
            <li><?php echo $this->Html->link(__('Add New User'), array('controller' => 'users', 'action' => 'add', 'admin' => true)) ?></li>
          </ul>
        </li>
        <li class="dropdown">
          <?php echo $this->Html->link(__('Places') . ' <b class="caret"></b>', 'javascript:void(0);', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'escape' => false)); ?>
          <ul class="dropdown-menu">
            <li><?php echo $this->Html->link(__('Manage Places'), array('controller' => 'places', 'action' => 'index', 'admin' => true)) ?></li>
            <li><?php echo $this->Html->link(__('Add New Place'), array('controller' => 'places', 'action' => 'add', 'admin' => true)) ?></li>
          </ul>
        </li>
        <li class="dropdown">
          <?php echo $this->Html->link(__('Emails') . ' <b class="caret"></b>', 'javascript:void(0);', array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'escape' => false)); ?>
          <ul class="dropdown-menu">
            <li><?php echo $this->Html->link(__('Manage Transactional Emails'), array('controller' => 'transactional_emails', 'action' => 'index', 'admin' => true)) ?></li>
            <li><?php echo $this->Html->link(__('Add New Email'), array('controller' => 'transactional_emails', 'action' => 'add', 'admin' => true)) ?></li>
            <li class="divider"></li>
            <li><?php echo $this->Html->link(__('Manage Crm Emails'), array('controller' => 'crm_emails', 'action' => 'index', 'admin' => true)) ?></li>
            <li><?php echo $this->Html->link(__('Add New Place'), array('controller' => 'crm_emails', 'action' => 'add', 'admin' => true)) ?></li>
          </ul>
        </li>
        <li><?php echo $this->Html->link(__('Logout'), array('controller' => 'users', 'action' => 'logout', 'admin' => false)); ?></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>