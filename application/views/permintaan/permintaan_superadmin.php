  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Permintaan
        <small>Riwayat Permintaan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Permintaan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">

          <div class="box">
            <div class="box-header with-border">
              <i class="fa fa-search"></i>
              <h3 class="box-title"><a href="javascript:void(0)" id="toggleCariPermintaan" style="color: inherit;">Pencarian&nbsp;&nbsp;<i class="fa fa-angle-down"></i></a></h3>

              <form role="form" id="frmCariPermintaan" style="display: none;">
                <div class="box-body">
                  <div class="form-group">
                    <input type="text" class="form-control input-sm" id="no_aju" placeholder="No. Aju" style="max-width: 100px">
                  </div>
                  <div class="form-group">
                    <select class="form-control input-sm" style="max-width: 300px">
                      <option value="">Perusahaan</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control input-sm" id="cari" placeholder="Cari..." style="max-width: 300px">
                  </div>
                  <div class="checkbox">
                    <label>
                      <input name="status_proses" type="checkbox" checked> Proses
                    </label>&nbsp;&nbsp;
                    <label>
                      <input name="status_selesai" type="checkbox"> Sudah Selesai
                    </label>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm" style="width: 80px;"><i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                  </div>
                </div>

                <!-- /.box-body -->

              </form>

            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped table-condensed table-hover">
              <thead>
                <tr class="center middle">
                  <th rowspan="2">No</th>
                  <th rowspan="2">No. Aju</th>
                  <th rowspan="2">Nama Kapal</th>
                  <th colspan="2">Perkiraan Lama Kegiatan</th>
                  <th rowspan="2">Jumlah</th>
                  <th rowspan="2">Status</th>
                  <th rowspan="2">#</th>
                </tr>
                <tr class="center">
                  <th>Dari</th>
                  <th>Sampai</th>
                </tr>
              </thead>
              <tbody>
                <?php for($i = 1; $i <= 10; $i++) { ?>
                <tr>
                  <td class="center"><?php echo $i ?>.</td>
                  <td class="center"><?php echo str_pad($i, 6, "0", STR_PAD_LEFT); ?></td>
                  <td>Nama kapal <?php echo $i ?></td>
                  <td class="center">29/09/2016</td>
                  <td class="center">12/10/2016</td>
                  <td class="right">Rp. 5.000.000,00</td>
                  <td class="center">Process</td>
                  <td class="center">
                    <a href="" class=""><i class="fa fa-edit"></i></a>&nbsp;
                    <a href="" class=""><i class="fa fa-remove"></i></a>
                  </td>
                </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="#">&laquo;</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">&raquo;</a></li>
              </ul>
            </div>
          </div>



        </section>
        <!-- /.Left col -->

      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>

  <script>
    $(function() {
      //$('#frmCariPermintaan').toggle('hide');
      $('#toggleCariPermintaan').click(function() {
        $('#frmCariPermintaan').toggle();
      });
    });
  </script>