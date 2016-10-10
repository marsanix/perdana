<table class="table table-bordered table-striped table-condensed table-hover">
<thead>
  <tr class="center middle">
    <th style="width: 70px">No</th>
    <th>Nama Perusahaan</th>
    <th>Nama Pengguna</th>
    <th>Nama Penanggung Jawab</th>
    <th>Saldo</th>
    <th class="col-sm-1">#</th>
  </tr>
</thead>
<tbody>
  <?php if(!empty($perusahaanList)) { ?>
  <?php $no = 1 + $page; foreach($perusahaanList as $perusahaan) { ?>
  <tr class="rows-perusahaan">
    <td class="center"><?php echo $no ?>.</td>
    <td><?php echo $perusahaan->nama_perusahaan ?></td>
    <td>
    <?php
    if(!empty($perusahaan->daftar_nama_pengguna)) {
      // print_r($perusahaan->daftar_nama_pengguna);
      $count = sizeof($perusahaan->daftar_nama_pengguna);
      $i = 1;
      foreach($perusahaan->daftar_nama_pengguna as $lists) {
        $i++;
        echo $lists->nama_pengguna.(($count == $i)?', ':'');
      }
    }
    ?>
    </td>
    <td><?php echo $perusahaan->penanggung_jawab ?></td>
    <td class="right"><?php echo $perusahaan->saldo ?></td>
    <td class="center">
      <a href="<?php echo site_url('perusahaan/edit/'.$perusahaan->id) ?>" class="dialog-fancybox"><i class="fa fa-edit"></i></a>
      <?php if($this->auth->isSuperAdmin()) { ?>
      &nbsp;&nbsp;
      <a href="<?php echo site_url('perusahaan/delete/'.$perusahaan->id) ?>" data-nama_perusahaan="<?php echo $perusahaan->nama_perusahaan ?>" class="rows-perusahaan-delete" style="color: #FF562F;"><i class="fa fa-trash"></i></a>
      <?php } ?>
    </td>
  </tr>
  <?php $no++; } ?>
  <?php } else { ?>
  <tr>
    <td colspan="6">Tidak ada data</td>
  </tr>
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

        $('tr.rows-perusahaan').hover(
            function(){
                $(".rows-perusahaan-view", this).fadeIn(150);
            },
            function(){
                $(".rows-perusahaan-view", this).fadeOut(150);
            }
        );


    });
</script>