<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Perusahaan
        <small>Ubah Saldo</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Perusahaan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">

            <div class="box">
            <div class="box-header with-border" style="min-height: 40px">
              <h3 class="box-title" style="padding: 5px;"><i class="fa fa-search" style="font-size: medium;"></i> <a href="javascript:void(0)" id="toggleCariPerusahaan" style="color: inherit;">Pencarian&nbsp;&nbsp;<i class="fa fa-angle-down"></i></a></h3>


              <div class="box-tools">
                <a href="<?php echo site_url('perusahaan/add') ?>" class="btn btn-success btn-sm pull-right dialog-fancybox"><i class="fa fa-plus"></i> Tambah Perusahaan</a>
              </div>

              <?php echo form_open('perusahaan/load_data', array('name' => 'frmSearchData', 'id' => 'frmSearchData', 'role' => 'form', 'style' => 'display: none;' )); ?>
                <div class="box-body">
                  <div class="form-group">
                    <input type="text" class="form-control input-sm" name="cari" id="cari" placeholder="Cari nama kapal" style="max-width: 300px">
                  </div>
                  <div class="form-group">
                    <select name="per_page" id="per_page" class="form-control input-sm" style="max-width: 120px">
                      <option value="5">Per page 5</option>
                      <option value="10">Per page 10</option>
                      <option value="20" selected="">Per page 20</option>
                      <option value="50">Per page 50</option>
                      <option value="100">Per page 100</option>
                      <option value="200">Per page 200</option>
                      <option value="500">Per page 500</option>
                      <option value="1000">Per page 1000</option>
                      <option value="2000">Per page 2000</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <button type="reset" id="btnRefreshSearch" class="btn btn-default btn-sm" ><i class="fa fa-refresh"></i></button>
                    <button type="submit" class="btn btn-primary btn-sm" style="width: 80px;"><i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                  </div>
                </div>

                <!-- /.box-body -->

              <?php echo form_close() ?>

            </div>

            <div id="msgSearchForm" style="padding-left: 10px; padding-right: 10px;"></div>

            <!-- /.box-header -->
            <div id="tableContent" class="box-body table-responsive"></div>
            <!-- /.box-body -->

          </div>

        </section>
        <!-- /.Left col -->

      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>