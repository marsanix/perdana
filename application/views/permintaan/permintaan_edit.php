<div class="modal fade bs-example-modal-lg in" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false" style="display: block; padding-right: 17px;">
    <div class="modal-dialog modal-lg">
      	<div class="modal-content">

      	<?php echo form_open('permintaan/edit/'.$data->id.'/save', array('name' => 'frmEditPermintaan', 'class' => 'form-horizontal')); ?>

                        <input type="hidden" name="id" value="<?php echo $data->id ?>" />


		        <div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:$.fancybox.close();"><span aria-hidden="true">&times;</span></button>
		          	<h4 class="modal-title" id="myLargeModalLabel">Detail Riwayat - Ubah Status</h4>
		        </div>
		        <div class="modal-body">

		        	<div id="msgForm"></div>

              <div class="form-group">
                <div class="col-sm-offset-1 col-sm-11">

                  <table style="width: 100%;">
                        <tr>
                          <td style="width: 150px"><b>Nama Perusahaan</b></td>
                          <td style="width: 7px">:</td>
                          <td><?php echo $perusahaan->nama_perusahaan ?></td>
                          <td rowspan="2" class="center">
                            <b>Saldo</b>
                            <p><span id="saldoSaatIni" data-saldo="<?php echo $perusahaan->saldo ?>" style="font-weight: bold; color: <?php echo ($perusahaan->saldo <= 1500000)?'#FF1800':'#0EAD02'; ?>;"><?php echo FormatCurrency($perusahaan->saldo,2) ?></span></p>
                          </td>
                        </tr>
                        <tr>
                          <td><b>Nama Pengguna</b></td>
                          <td>:</td>
                          <td><?php echo $data->created_by_name ?></td>
                        </tr>
                        <tr>
                          <td><b>Penanggung Jawab</b></td>
                          <td>:</td>
                          <td><?php echo $perusahaan->penanggung_jawab ?></td>
                        </tr>
                    </table>

                  </div>
                </div>

              <hr />

                    <div class="form-group">
                      <label for="no_aju" class="col-sm-3 control-label">No. Aju</label>
                      <div class="col-sm-2">
                        <p class="form-control-static"><b><?php echo $data->no_aju ?></b></p>
                      </div>
                    </div>
	                <div class="form-group">
                      <label for="nama_kapal" class="col-sm-3 control-label">Nama Kapal</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" value="<?php echo $data->nama_kapal ?>" readonly />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="grt" class="col-sm-3 control-label">GRT</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" value="<?php echo $data->grt ?>" readonly />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="perkiraan_kegiatan_from" class="col-sm-3 control-label">Perkiraan Lama Kegiatan</label>
                      <div class="col-sm-9 inline">
                        <input type="text" class="form-control input-sm inline" value="<?php echo FormatDateTime($data->perkiraan_kegiatan_from, 7) ?>" readonly style="width: 100px" />
                        &nbsp;&nbsp;s/d&nbsp;&nbsp; <input type="text" class="form-control input-sm inline" value="<?php echo FormatDateTime($data->perkiraan_kegiatan_to, 7) ?>" readonly style="width: 100px" />
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="pemilik_kapal" class="col-sm-3 control-label">Pemilik Kapal</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control input-sm" value="<?php echo $data->pemilik_kapal ?>" readonly />
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="posisi_kapal" class="col-sm-3 control-label">Posisi Kapal</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" value="<?php echo $data->posisi_kapal ?>" readonly />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="jenis_transaksi" class="col-sm-3 control-label">Jenis Transaksi</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" value="<?php echo $data->nama_jenis_transaksi ?>" readonly />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="jenis_jasa" class="col-sm-3 control-label">Jenis Jasa</label>
                      <div class="col-sm-6">
                          <input type="text" class="form-control input-sm" value="<?php echo $data->nama_jenis_jasa ?>" readonly />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="jenis_jasa" class="col-sm-3 control-label">Jumlah Booking</label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control input-sm" id="jumlah_booking" data-jumlah_booking="<?php echo $data->jumlah ?>" value="<?php echo FormatCurrency($data->jumlah, 2) ?>" readonly />
                      </div>
                    </div>
                    <?php if(!$data->status) { ?>
                    <div class="form-group">
                      <label for="status" class="col-sm-3 control-label">Status</label>
                      <div class="col-sm-6">
                        <div class="radio">
                          <label>
                            <input type="radio" name="status" value="0" class="minimal" <?php echo is_checked($data->status, 0) ?>>
                            PROSES
                          </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <label>
                            <input type="radio" name="status" value="1" class="minimal" <?php echo is_checked($data->status, 1) ?>>
                            SUDAH SELESAI
                          </label>
                        </div>
                      </div>
                    </div>
                    <?php } else { ?>
                    <div class="form-group">
                      <label for="status" class="col-sm-3 control-label">Status</label>
                      <div class="col-sm-6">
                        <div class="radio">
                          <label>
                            <input type="radio" value="0" class="minimal" disabled <?php echo is_checked($data->status, 0) ?>>
                            PROSES
                          </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <label>
                            <input type="radio" value="1" class="minimal" disabled <?php echo is_checked($data->status, 1) ?>>
                            SUDAH SELESAI
                          </label>
                        </div>
                      </div>
                    </div>
                    <?php } ?>

		        </div>
		        <div class="modal-footer">
              <?php if(!$data->status) { ?>
              <a href="<?php echo site_url('permintaan/delete/'.$data->id) ?>" id="btnRemove" class="btn btn-sm btn-danger" style="float: left;" data-no_aju="<?php echo $data->no_aju ?>"><i class="fa fa-remove"></i> Hapus Pengajuan</a>
              <?php } ?>

              <?php if(!$data->status) { ?>
              <button type="submit" id="btnSave" class="btn btn-sm btn-primary"><i class="fa fa-save"></i>&nbsp; Simpan</button>&nbsp;
              <?php } ?>
              <button type="button" class="btn btn-default btn-sm pull-right" data-dismiss="modal" onclick="javascript:$.fancybox.close();" ><i class="glyphicon glyphicon-arrow-left"></i> Tutup</button>

		      	</div>

	      	<?php echo form_close(); ?>

      	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script>
    $(function() {
        $('#reset_password').click(function() {
            var me = $(this);
            if(me.prop('checked')) {
                $('#password').attr('disabled','').val('');
                $('#change_password').attr('disabled','').prop('checked', false);
            } else {
                $('#password').attr('disabled','').val('');
                $('#change_password').removeAttr('disabled').prop('checked', false);
            }
        });
        $('#change_password').click(function() {
            var me = $(this);
            if(me.prop('checked')) {
                $('#password').removeAttr('disabled').val('').removeAttr('placeholder').focus();
                $('#reset_password').attr('disabled','').prop('checked', false);
                $('#label_password').addClass('required');
            } else {
                $('#password').attr('disabled','').val('').attr('placeholder','************');
                $('#reset_password').removeAttr('disabled').prop('checked', false);
                $('#label_password').removeClass('required');
            }
        });
        $('#update_dn').click(function() {
            var me = $(this);
            if(me.prop('checked')) {
                $('#dn').removeAttr('readonly').attr('placeholder','CN=Display Name,OU=Department,OU=Company,DC=cladtek,DC=com').focus();
            } else {
                $('#dn').attr('readonly','').removeAttr('placeholder');
                if($('#dn').data('dn') === '') {
                    $('#dn').val('');
                }
            }
        });
    });
</script>