 <style type="text/css">
    .datepicker {
        z-index: 9902 !important;
        font-size: small;
    }
</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Permintaan Dana
        <small>Buat Permintaan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">Permintaan</li>
        <li class="active">Tambah</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">

          <div class="box">
            <div class="box-header with-border" style="padding: 20px;">

            <div class="row">
              <div class="col-lg-6">

                <table>
                  <tr>
                    <td style="width: 140px"><b>Nama Perusahaan</b></td>
                    <td style="width: 7px">:</td>
                    <td><?php echo $perusahaan->nama_perusahaan ?></td>
                  </tr>
                  <tr>
                    <td><b>Nama Pengguna</b></td>
                    <td>:</td>
                    <td style="text-transform: uppercase;"><?php echo $perusahaan->nama_pengguna ?></td>
                  </tr>
                  <tr>
                    <td><b>Penanggung Jawab</b></td>
                    <td>:</td>
                    <td><?php echo $perusahaan->penanggung_jawab ?></td>
                  </tr>
                  <tr>
                    <td><b>SALDO</b></td>
                    <td>:</td>
                    <td><span style="font-weight: bold; color: <?php echo ($perusahaan->saldo <= 150000)?'#FF1800':'#0EAD02'; ?>;"><?php echo FormatCurrency($perusahaan->saldo, 2) ?></span></td>
                  </tr>
                </table>

              </div>
              <div class="col-lg-6 right">
                <a href="<?php echo site_url('permintaan') ?>" class="btn btn-primary btn-sm"><i class="fa fa-history"></i>&nbsp; Lihat Riwayat Permintaan</a>
              </div>

              </div>

            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <?php echo form_open('permintaan/add/save', array('name' => 'frmAddPermintaan', 'id'=> 'frmAddPermintaan', 'class' => 'form-horizontal')); ?>




              <div class="box-body" style="padding-top: 30px;">

                <div id="msgForm" style="padding:10px;"></div>

                <div class="form-group">
                  <label for="no_aju" class="col-sm-2 control-label">No. Aju</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control input-sm" name="no_aju" id="no_aju" placeholder="{AUTO}" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="nama_kapal" class="col-sm-2 control-label">Nama Kapal</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" name="nama_kapal" id="nama_kapal" placeholder="NAMA KAPAL">
                  </div>
                </div>
                <div class="form-group">
                  <label for="grt" class="col-sm-2 control-label">GRT</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" name="grt" id="grt" placeholder="GRT">
                  </div>
                </div>
                <div class="form-group">
                  <label for="grt" class="col-sm-2 control-label">Perkiraan Lama Kegiatan</label>

                  <div class="col-sm-6">
                    <input type="text" class="form-control input-sm inline calendar" name="perkiraan_kegiatan_from" id="perkiraan_kegiatan_from" placeholder="DD/MM/YYYY" style="width: 100px">&nbsp;&nbsp;
                    s/d&nbsp;&nbsp;<input type="text" class="form-control input-sm inline calendar" name="perkiraan_kegiatan_to" id="perkiraan_kegiatan_to" placeholder="DD/MM/YYYY" style="width: 100px">
                  </div>
                </div>
                <div class="form-group">
                  <label for="pemilik_kapal" class="col-sm-2 control-label">Pemilik Kapal</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" name="pemilik_kapal" id="pemilik_kapal" placeholder="PEMILIK KAPAL">
                  </div>
                </div>
                <div class="form-group">
                  <label for="posisi_kapal" class="col-sm-2 control-label">Posisi Kapal</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control input-sm" name="posisi_kapal" id="posisi_kapal" placeholder="POSISI KAPAL">
                  </div>
                </div>
                <div class="form-group">
                  <label for="jenis_transaksi" class="col-sm-2 control-label">Jenis Transaksi</label>
                  <div class="col-sm-6">
                    <select name="jenis_transaksi" id="jenis_transaksi" class="form-control input-sm" style="color: #9c9c9c;">
                      <option value="" style="color: #9c9c9c;">- JENIS TRANSAKSI -</option>
                      <?php if(!empty($jenis_transaksiList)) { ?>
                      <?php foreach($jenis_transaksiList as $jenis_transaksi) { ?>
                      <option value="<?php echo $jenis_transaksi->id ?>" style="color: #555;"><?php echo $jenis_transaksi->nama ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="jenis_jasa" class="col-sm-2 control-label">Jenis Jasa</label>
                  <div class="col-sm-6">
                    <div class="checkbox">
                      <label style="padding-left: 0px">
                        <input type="checkbox" name="jenis_jasa_labuh" value="1" class="minimal">&nbsp;
                        LABUH
                      </label>
                      <label>
                        <input type="checkbox" name="jenis_jasa_tambat" value="2" class="minimal">&nbsp;
                        TAMBAT
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="jumlah_booking" class="col-sm-2 control-label">Jumlah Booking</label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control input-sm" name="jumlah_booking" id="jumlah_booking" placeholder="0">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-offset-2 col-sm-6">
                <a href="<?php echo site_url('permintaan') ?>" id="btnBack" class="btn btn-default"><i class="fa fa-arrow-left"></i>&nbsp; Batal</a>
                <button type="reset" class="btn btn-default" id="btnReset"><i class="fa fa-undo"></i>&nbsp; Atur Ulang</button>
                <button type="submit" id="btnSave" class="btn btn-info pull-right"><i class="fa fa-send"></i>&nbsp; Booking</button>
                <span class="pull-right" id="frmAddPermintaanLoading" style="padding-right: 10px; padding-top: 5px;"></span>
                </div>
              </div>
              <!-- /.box-footer -->
            <?php echo form_close(); ?>

            <div style="height: 20px"></div>
          </div>



        </section>
        <!-- /.Left col -->

      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>

  <script>

  </script>