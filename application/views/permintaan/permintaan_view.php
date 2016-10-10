<style>
    .form-horizontal .control-label {
        padding-top: 2px;
    }
    .form-control-static {
        min-height: 20px;
        padding-top: 2px;
        padding-bottom: 2px;
        border-bottom: 1px dotted #ccc;
    }
</style>

<div class="modal fade bs-example-modal-lg in" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false" style="display: block; padding-right: 17px;">
    <div class="modal-dialog modal-lg">
      	<div class="modal-content">

      	<?php echo form_open('permintaan/view/'.$data->id, array('name' => 'frmViewPermintaan', 'class' => 'form-horizontal')); ?>
		        <div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:$.fancybox.close();"><span aria-hidden="true">&times;</span></button>
		          	<h4 class="modal-title" id="myLargeModalLabel">Detail Riwayat</h4>
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
                      <div class="col-sm-3">
                        <p class="form-control-static"><b><?php echo $data->no_aju ?></b></p>
                      </div>
                    </div>
	                <div class="form-group">
                      <label for="nama_kapal" class="col-sm-3 control-label">Nama Kapal</label>
                      <div class="col-sm-3">
                        <p class="form-control-static"><?php echo $data->nama_kapal ?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="grt" class="col-sm-3 control-label">GRT</label>
                      <div class="col-sm-3">
                        <p class="form-control-static"><?php echo $data->grt ?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="perkiraan_kegiatan_from" class="col-sm-3 control-label">Perkiraan Lama Kegiatan</label>
                      <div class="col-sm-4">
                        <p class="form-control-static">Dari&nbsp;&nbsp;<?php echo FormatDateTime($data->perkiraan_kegiatan_from, 7) ?>&nbsp;&nbsp;Sampai&nbsp;&nbsp;<?php echo FormatDateTime($data->perkiraan_kegiatan_to, 7) ?></p>
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="pemilik_kapal" class="col-sm-3 control-label">Pemilik Kapal</label>
                        <div class="col-sm-8">
                            <p class="form-control-static"><?php echo $data->pemilik_kapal ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="posisi_kapal" class="col-sm-3 control-label">Posisi Kapal</label>
                      <div class="col-sm-3">
                        <p class="form-control-static"><?php echo $data->posisi_kapal ?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="jenis_transaksi" class="col-sm-3 control-label">Jenis Transaksi</label>
                      <div class="col-sm-3">
                        <p class="form-control-static"><?php echo $data->nama_jenis_transaksi ?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="jenis_jasa" class="col-sm-3 control-label">Jenis Jasa</label>
                      <div class="col-sm-3">
                        <p class="form-control-static"><?php echo $data->nama_jenis_jasa ?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="jenis_jasa" class="col-sm-3 control-label">Jumlah Booking</label>
                      <div class="col-sm-3">
                        <p class="form-control-static"><?php echo FormatCurrency($data->jumlah, 2) ?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="status" class="col-sm-3 control-label">Status</label>
                      <div class="col-sm-8">
                        <p class="form-control-static"><?php echo (!$data->status)?'PROSES':'SELESAI' ?></p>
                      </div>
                    </div>

		        </div>
		        <div class="modal-footer">
			        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="javascript:$.fancybox.close();"><i class="glyphicon glyphicon-arrow-left"></i> Tutup</button>
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