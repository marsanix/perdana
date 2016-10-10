<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Profil
      <small>Ubah profil Anda</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Profil</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">
        <div class="col-md-3">

          <!-- Profil Image -->
          <div class="box box-primary">
            <div class="box-body box-profil">
              <div id="imageProfil" style="text-align: center">
                <i class="fa fa-user fa-6" style="font-size: 15em"></i>
              </div>
              <h3 class="profil-username text-center"><?php echo $this->auth->get_name() ?></h3>
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Username</b> <a class="pull-right"><?php echo $this->auth->get_username() ?></a>
                </li>
                <li class="list-group-item">
                  <b>Group</b> <a class="pull-right"><?php echo $this->auth->get_group_name() ?></a>
                </li>
              </ul>

            </div><!-- /.box-body -->
          </div><!-- /.box -->

        </div><!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#settings" data-toggle="tab">Umum</a></li>
              <li><a href="#change_password" data-toggle="tab">Ubah Password</a></li>
            </ul>
            <div class="tab-content">

              <div class="tab-pane active" id="settings">
                <?php echo form_open('profil/update', array('name' => 'frmEditProfil', 'id' => 'frmEditProfil', 'class' => 'form-horizontal')); ?>

                  <div id="msgEditProfil"></div>

                  <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">Nama</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="name" name="name" value="<?php echo $this->auth->get_name() ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="username" class="col-sm-3 control-label">Username</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="username" value="<?php echo $this->auth->get_username() ?>" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="groups" class="col-sm-3 control-label">Tipe Pengguna</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="groups" value="<?php echo $this->auth->get_group_name() ?>" disabled>
                    </div>
                  </div>
                  <?php if($this->auth->isOperasional()) { ?>
                  <div class="form-group">
                    <label for="company" class="col-sm-3 control-label">Perusahaan</label>
                    <div class="col-sm-8">
                      <?php if(!empty($perusahaanByUsers)) { ?>
                      <?php $no = 1; foreach ($perusahaanByUsers as $perusahaan) { ?>
                        <?php echo '<div>'.$no.'. '.$perusahaan->nama_perusahaan.'</div>' ?>
                      <?php $no++; } ?>
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>

                  <div class="form-group" style="margin-top: 20px;">
                    <div class="col-sm-offset-3 col-sm-8">
                      <button type="submit" id="btnSaveEditProfil" class="btn btn-danger">Simpan Perubahan</button>
                    </div>
                  </div>
                <?php echo form_close() ?>
              </div><!-- /.tab-pane -->

              <div class="tab-pane" id="change_password">

                <?php echo form_open('profil/change_password', array('name' => 'frmChangePassword', 'id' => 'frmChangePassword', 'class' => 'form-horizontal')); ?>

                  <div id="msgChangePassword"></div>

                  <div class="form-group">
                    <label for="old_password" class="col-sm-3 control-label">Password Lama</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="new_password" class="col-sm-3 control-label">Password Baru</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="new_password2" class="col-sm-3 control-label">Ulangi Password Baru</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" id="new_password2" name="new_password2" placeholder="Retype New Password">
                    </div>
                  </div>
                  <div class="form-group" style="margin-top: 20px;">
                    <div class="col-sm-offset-3 col-sm-8">
                      <button type="submit" id="btnSaveChangePassword" class="btn btn-danger">Ubah Password</button>
                    </div>
                  </div>
                <?php echo form_close() ?>
              </div><!-- /.tab-pane -->

            </div><!-- /.tab-content -->
          </div><!-- /.nav-tabs-custom -->
        </div><!-- /.col -->
      </div><!-- /.row -->

  </section>

</div>