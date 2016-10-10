  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Beranda
        <small>Admin</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Beranda</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Quick Menu</h3>
            </div>
            <div class="box-body">
              <a href="<?php echo site_url('permintaan') ?>" class="btn btn-app" style="width: 130px; height: 100px; vertical-align: middle;">
                <span class="badge bg-green"><?php echo $totals->total_permintaan ?></span><br />
                <i class="fa fa-history"></i>Riwayat Permintaan
              </a>
              <a href="<?php echo site_url('permintaan/status/proses') ?>" class="btn btn-app" style="width: 130px; height: 100px">
                <span class="badge bg-yellow"><?php echo $totals->total_permintaan_proses ?></span>
                <i class="fa fa-hand-o-right"></i> Ubah<br />Status Permintaan
              </a>
              <a href="<?php echo site_url('perusahaan') ?>" class="btn btn-app" style="width: 130px; height: 100px"><br />
                <span class="badge bg-yellow"><?php echo $totals->total_perusahaan ?></span>
                <i class="fa fa-money"></i> Ubah Saldo
              </a>

            </div>
            <!-- /.box-body -->

            <div style="height: 100px"></div>

          </div>



        </section>
        <!-- /.Left col -->

      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>