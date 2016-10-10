<table class="table table-bordered table-striped table-condensed table-hover">
<thead>
  <tr class="center middle">
    <th rowspan="2" style="width: 70px">No</th>
    <th rowspan="2" class="col-sm-1">No. Aju</th>
    <th rowspan="2">Nama Kapal</th>
    <th colspan="2">Perkiraan Lama Kegiatan</th>
    <th rowspan="2" class="col-sm-2">Jumlah</th>
    <th rowspan="2" class="col-sm-1">Status</th>
    <th rowspan="2" class="col-sm-1">#</th>
  </tr>
  <tr class="center">
    <th class="col-sm-1">Dari</th>
    <th class="col-sm-1">Sampai</th>
  </tr>
</thead>
<tbody>
  <?php if(!empty($permintaanList)) { ?>
  <?php $no = 1 + $page; foreach($permintaanList as $permintaan) { ?>
  <tr class="rows-permintaan">
    <td class="center"><?php echo $no ?>.</td>
    <td class="center"><a href="<?php echo site_url('permintaan/view/'.$permintaan->id) ?>" class="dialog-fancybox"><?php echo str_pad($permintaan->no_aju, 6, "0", STR_PAD_LEFT); ?></a></td>
    <td><?php echo $permintaan->nama_kapal ?>
      <span class="rows-permintaan-view" style="padding-left: 20px; display: none;"><a href="<?php echo site_url('permintaan/view/'.$permintaan->id) ?>" class="dialog-fancybox" ><i class="fa fa-search-plus"></i></a></span>
    </td>
    <td class="center"><?php echo FormatDateTime($permintaan->perkiraan_kegiatan_from, 7) ?></td>
    <td class="center"><?php echo FormatDateTime($permintaan->perkiraan_kegiatan_to, 7) ?></td>
    <td class="right"><?php echo FormatCurrency($permintaan->jumlah,2) ?></td>
    <td class="center"><?php echo (!$permintaan->status)?'PROSES':'SELESAI' ?></td>
    <td class="center">
      <a href="<?php echo site_url('permintaan/edit/'.$permintaan->id) ?>" class="dialog-fancybox btn-xs <?php echo (!$permintaan->status)?'btn-info':'btn-default' ?>"><i class="fa fa-edit"></i> <?php echo (isset($status) && $status == "proses")?'Ubah Status':'Edit' ?></a>
    </td>
  </tr>
  <?php $no++; } ?>
  <?php } else { ?>

  <?php } ?>
  </tbody>
</table>

<div class="box-footer clearfix">
<nav><?php echo $pagination ?></nav>
</div>

<!--  <div class="box-footer clearfix">
  <ul class="pagination pagination-sm no-margin pull-right">
    <li><a href="#">&laquo;</a></li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">&raquo;</a></li>
  </ul>
</div> -->

<script>
    $(function() {
        $.ajax({
            url:'<?php echo site_url('trace/get_token') ?>',
            type: 'get',
            success: function(rdata) {
                if(rdata != '') {
                    $('input:hidden[name="<?php echo $this->security->get_csrf_token_name() ?>"]').val(rdata);
                }
            }
        });

        $('tr.rows-permintaan').hover(
            function(){
                $(".rows-permintaan-view", this).fadeIn(150);
            },
            function(){
                $(".rows-permintaan-view", this).fadeOut(150);
            }
        );


    });
</script>