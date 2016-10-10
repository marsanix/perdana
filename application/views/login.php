<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PerDana - Aplikasi Permintaan Dana | Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the assets/css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="assets/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="assets/plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="assets/plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="assets/plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <style>
  .login-page {
    background-position: 30% 20%;
  }
  .login-logo a {
    color: #fff;
    text-shadow: -7px -1px 19px rgba(255, 255, 255, 1);
  }
  </style>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page lazy">
<div class="login-box" style="padding-top: 30px;">
  <div class="login-logo">
    <a href="login.html">Per<b>Dana</b> <small>v1.0</small>
      <div style="font-size: 17px; padding: 0px; margin-top: -10px;">Aplikasi Permintaan Dana</div>
    </a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Masuk sebagai Operasional</p>

    <?php echo form_open('login', array('name' => 'frmLogin', 'class' => 'form-signin')); ?>

      <?php echo validation_errors() ?>
      <?php if (isset($error) && !empty($error)) { ?>
          <div style="margin: 4px; padding: 7px;" class="alert alert-warning alert-dismissible" role="alert">
              <button style="right: 0px;" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $error ?>
          </div>
      <?php } ?>

      <div class="form-group has-feedback">
        <select class="form-control" name="groups" id="groups">
          <option value=""></option>
          <?php if(!empty($groupsList)) { ?>
          <?php foreach($groupsList as $groups) { ?>
          <option value="<?php echo $groups->id ?>" <?php echo ($groups->id == 2)?'selected':'' ?>><?php echo $groups->name ?></option>
          <?php } ?>
          <?php } ?>
        </select>
        <span class="fa fa-group form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback" id="togglePerusahaan" style="display: block">
        <select class="form-control" name="perusahaan">
          <option value="">- Nama Perusahaan -</option>
          <?php if(!empty($perusahaanList)) { ?>
          <?php foreach($perusahaanList as $perusahaan) { ?>
          <option value="<?php echo $perusahaan->id ?>"><?php echo $perusahaan->nama_perusahaan ?></option>
          <?php } ?>
          <?php } ?>
        </select>
        <span class="fa fa-ship form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="username" name="<?php echo isset($name_username) ? $name_username : '' ?>" class="form-control" placeholder="Nama Pengguna">
        <span class="fa fa-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="<?php echo isset($name_password) ? $name_password : '' ?>" class="form-control" placeholder="Kata Sandi">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">

        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
        </div>
        <!-- /.col -->
      </div>
    <?php echo form_close() ?>

    <!-- /.social-auth-links -->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="assets/js/jquery.lazyload.min.js?v=1.9.7"></script>

<!-- Bootstrap 3.3.6 -->
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script>
  $(function () {
    $('.focused').focus();

    setTimeout(function() {
      // $('body').attr('style','background: url(<?php echo assets_url('img/wallpaper.jpg') ?>)');
      $('body').attr('data-original','assets/img/wallpaper.jpg');
      $("body.lazy").lazyload({
          event : "sporty"
      });
      $(window).bind("load", function() {
          var timeout = setTimeout(function() { $("body.lazy").trigger("sporty") }, 3000);
      });
    }, 1000);

    $(document).on('keyup change', '#groups', function() {
      var me = $(this);
      var me_selected = me.find('option:selected');
      $('.login-box-msg').html('Masuk sebagai ' + me_selected.text());
      if(me_selected.val() == '2') {
        $('#togglePerusahaan').toggle(true);
      } else {
        $('#togglePerusahaan').toggle(false);
      }
    });

  });
</script>

</body>
</html>
