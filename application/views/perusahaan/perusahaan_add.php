<div class="modal fade bs-example-modal-lg in" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false" style="display: block; padding-right: 17px;">
    <div class="modal-dialog">
      	<div class="modal-content">

      	<?php echo form_open('perusahaan/add/save', array('name' => 'frmAddPerusahaan', 'class' => 'form-horizontal')); ?>

		        <div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:$.fancybox.close();"><span aria-hidden="true">&times;</span></button>
		          	<h4 class="modal-title" id="myLargeModalLabel">Ubah Saldo Perusahaan</h4>
		        </div>
		        <div class="modal-body">

		        	<div id="msgForm"></div>

                    <div class="form-group">
                      <label for="nama_perusahaan" class="col-sm-4 control-label">Nama Perusahaan</label>
                      <div class="col-sm-6">
                        <input type="text" name="nama_perusahaan" class="form-control input-sm text-capital"  />
                      </div>
                    </div>
	                <div class="form-group">
                      <label for="nama_penanggung_jawab" class="col-sm-4 control-label">Nama Penanggung Jawab</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm text-capital" name="penanggung_jawab" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="daftar_pengguna" class="col-sm-4 control-label">Nama Pengguna</label>
                      <div class="col-sm-6">
                        <select name="daftar_pengguna[]" class="form-control input-sm" multiple="true">
                          <option value=""></option>
                          <?php if(!empty($operasionalLists)) { ?>
                          <?php foreach($operasionalLists as $operasional) { ?>
                            <option value="<?php echo $operasional->id ?>"><?php echo $operasional->name ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                        <div class="small"><i>Klik Ctrl untuk memilih lebih dari satu pengguna.</i></div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="saldo" class="col-sm-4 control-label">Saldo</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" name="saldo" id="saldo" />
                      </div>
                    </div>

		        </div>
		        <div class="modal-footer">
              <button type="submit" id="btnSave" class="btn btn-sm btn-primary"><i class="fa fa-save"></i>&nbsp; Simpan</button>&nbsp;
              <button type="button" class="btn btn-default btn-sm pull-right" data-dismiss="modal" onclick="javascript:$.fancybox.close();" ><i class="glyphicon glyphicon-arrow-left"></i> Tutup</button>

		      	</div>

	      	<?php echo form_close(); ?>

      	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script>
  $(function() {
    $("#saldo").inputmask("999999999999999999", {"placeholder":""});
  });
</script>