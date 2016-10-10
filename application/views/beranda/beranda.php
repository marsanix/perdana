  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Beranda
        <small>Operasional</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Beranda</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">

        <section class="col-lg-12 connectedSortable">

          <div class="box">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">Permintaan masih dalam proses</h3>

              <div class="box-tools pull-right">
                <a href="<?php echo site_url('permintaan') ?>" class="btn btn-sm btn-primary"><i class="fa fa-history"></i>&nbsp;&nbsp;Lihat Riwayat Lainnya</a>&nbsp;
                <a href="<?php echo site_url('permintaan/add') ?>" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;Buat Permintaan Dana</a>
              </div>

            </div>
            <!-- /.box-header -->
            <div class="box-body">

                <table class="table table-condensed table-striped table-hover">
                    <thead>
                        <tr class="center">
                            <th>No</th>
                            <th>No. Aju</th>
                            <th>Nama Kapal</th>
                            <th>Perkiraan Kegiatan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($permintaanList)) { ?>
                    <?php $no = 1; foreach($permintaanList as $permintaan) { ?>
                        <tr>
                            <td class="center"><?php echo $no ?></td>
                            <td class="center"><a href="<?php echo site_url('permintaan/view/'.$permintaan->id) ?>" class="dialog-fancybox"><?php echo $permintaan->no_aju ?></a></td>
                            <td><?php echo $permintaan->nama_kapal ?></td>
                            <td class="center"><?php echo FormatDateTime($permintaan->perkiraan_kegiatan_from, 7).'&nbsp;&nbsp;s/d&nbsp;&nbsp;'.FormatDateTime($permintaan->perkiraan_kegiatan_to,7) ?></td>
                            <td class="right"><?php echo FormatCurrency($permintaan->jumlah, 2) ?></td>
                        </tr>
                    <?php $no++; } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5">Tidak ada permintaan dana</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>


          </div>

        </section>

      </div>

    </section>

  </div>

  <script>
    $(function() {
        $(document).on('click','.dialog-fancybox', function() {
            var me = $(this);

            $.fancybox({
                href: me.attr('href'),
                type: 'ajax',
                ajax: {
                    type: "get"
                },
                scrolling: 'auto',
                closeBtn: false,
                openEffect: 'none',
                closeEffect: 'none',
                openSpeed: 'fast',
                closeSpeed: 'fast'
            });
            $('.fancybox-skin').removeClass('fancybox-skin');
            return false;
        });
    });
  </script>