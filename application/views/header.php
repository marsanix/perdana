<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo isset($title)?$title:$this->config->item('app_name').' v'.$this->config->item('app_version') ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo assets_url('bootstrap/css/bootstrap.min.css') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo assets_url('css/AdminLTE.min.css') ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo assets_url('css/skins/_all-skins.min.css') ?>">
  <!-- iCheck for checkboxes and radio inputs -->
  <!-- <link rel="stylesheet" href="<?php echo assets_url('plugins/iCheck/all.css') ?>"> -->
  <!-- Morris chart -->
  <!-- <link rel="stylesheet" href="<?php echo assets_url('plugins/morris/morris.css') ?>"> -->
  <!-- jvectormap -->
  <!-- <link rel="stylesheet" href="<?php echo assets_url('plugins/jvectormap/jquery-jvectormap-1.2.2.css') ?>"> -->
  <!-- Date Picker -->
  <!-- <link rel="stylesheet" href="<?php echo assets_url('plugins/datepicker/datepicker3.css') ?>"> -->
  <!-- Daterange picker -->
  <!-- <link rel="stylesheet" href="<?php echo assets_url('plugins/daterangepicker/daterangepicker.css') ?>"> -->
  <!-- bootstrap wysihtml5 - text editor -->
  <!-- <link rel="stylesheet" href="<?php echo assets_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>"> -->

  <?php if (isset($css_link) && sizeof($css_link) > 0) { ?>
    <?php foreach ($css_link as $css) { ?>
      <link href="<?php echo $css['href'] ?>" <?php echo $css['attr'] ?> rel="stylesheet">
    <?php } ?>
  <?php } ?>

  <link rel="stylesheet" href="<?php echo assets_url('css/app.css') ?>">

  <!-- jQuery 2.2.3 -->
  <script src="<?php echo assets_url('plugins/jQuery/jquery-2.2.3.min.js') ?>"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-black-light sidebar-mini sidebar-collapse">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">P<b>D</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">Per<b>Dana</b> <small>v1.0</small></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-user"></i>
              <span class="hidden-xs"><?php echo $this->auth->get_name() ?></span>&nbsp;&nbsp;<i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <i class="fa fa-user fa-white" style="font-size: 7em; color: #fff;"></i>

                <p>
                  <?php echo $this->auth->get_name() ?> - <?php echo $this->auth->get_username() ?>
                  <small><?php echo $this->auth->get_group_name() ?></small>
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo site_url('profil') ?>" class="btn btn-default btn-flat">Profil</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo site_url('login/out') ?>" class="btn btn-default btn-flat">Keluar</a>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <?php if($this->auth->isOperasional()) { ?>
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo assets_url('img/snepac.jpg') ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->auth->get_perusahaan_name() ?></p>
          <div><?php echo $this->auth->get_penanggung_jawab() ?></div>
        </div>
      </div>
      <?php } ?>

      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Cari riwayat permintaan...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENU UTAMA</li>
        <li><a href="<?php echo site_url('beranda') ?>"><i class="fa fa-home"></i> <span>Beranda</span></a></li>
        <?php if($this->auth->isOperasional()) { ?>
        <li><a href="<?php echo site_url('permintaan/add') ?>" style="color: #0A9A03;"><i class="fa fa-plus"></i> <span>Buat Permintaan Dana</span></a></li>
        <?php } ?>
        <li><a href="<?php echo site_url('permintaan') ?>"><i class="fa fa-history"></i> <span>Lihat Riwayat Permintaan</span></a></li>
        <?php if($this->auth->isAdmin() || $this->auth->isSuperAdmin()) { ?>
        <li><a href="<?php echo site_url('perusahaan') ?>"><i class="fa fa-money"></i> <span>Ubah Saldo</span></a></li>
        <li><a href="<?php echo site_url('permintaan/status/proses') ?>"><i class="fa fa-hand-o-right"></i> <span>Ubah Status Permintaan</span></a></li>
        <?php } ?>
        <?php if($this->auth->isSuperAdmin()) { ?>
        <li><a href="<?php echo site_url('users') ?>"><i class="fa fa-users"></i> <span>Daftar Pengguna</span></a></li>
        <?php } ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>


