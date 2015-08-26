<!DOCTYPE html>
<html lang="en">
  <head>
  <title>
    <?php echo $title_for_layout; ?>
  </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

  <?php
    echo $this->Html->meta('icon');

    echo $this->fetch('meta');
    echo $this->Html->css('bootstrap.min');
    echo $this->Html->css('admin');
    echo $this->fetch('css');
    echo $this->Html->script('jquery.min');
    echo $this->Html->script('bootstrap.min');
    echo $this->Html->script('admin');
    echo $this->fetch('script');
  ?>
  <script type="text/javascript">
    var WEBROOT = '<?php echo $this->request->webroot; ?>';
  </script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
      body{ padding: 70px 0px; }
    </style>


  </head>

  <body>

    <?php echo $this->Element('navigation'); ?>

    <div class="container">

      <?php echo $this->Session->flash(); ?>

      <?php echo $this->fetch('content'); ?>

    </div><!-- /.container -->

  </body>
</html>
