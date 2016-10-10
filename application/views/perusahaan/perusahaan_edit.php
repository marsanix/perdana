<div class="modal fade bs-example-modal-lg in" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false" style="display: block; padding-right: 17px;">
    <div class="modal-dialog">
      	<div class="modal-content">

      	<?php echo form_open('perusahaan/edit/'.$data->id.'/save', array('name' => 'frmEditPerusahaan', 'class' => 'form-horizontal')); ?>
            <input type="hidden" name="id" value="<?php echo $data->id ?>" />
		        <div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:$.fancybox.close();"><span aria-hidden="true">&times;</span></button>
		          	<h4 class="modal-title" id="myLargeModalLabel">Ubah Saldo Perusahaan</h4>
		        </div>
		        <div class="modal-body">

		        	<div id="msgForm"></div>

                    <div class="form-group">
                      <label for="nama_perusahaan" class="col-sm-4 control-label">Nama Perusahaan</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" name="nama_perusahaan" value="<?php echo $data->nama_perusahaan ?>" <?php echo (!$this->auth->isSuperAdmin())?'readonly':'' ?> />
                      </div>
                    </div>
	                <div class="form-group">
                      <label for="nama_penanggung_jawab" class="col-sm-4 control-label">Nama Penanggung Jawab</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" name="penanggung_jawab" value="<?php echo $data->penanggung_jawab ?>" <?php echo (!$this->auth->isSuperAdmin())?'readonly':'' ?> />
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="nama_pengguna" class="col-sm-4 control-label">Nama Pengguna</label>
                        <div class="col-sm-6">
                        <?php if($this->auth->isSuperAdmin()) { ?>

                        <?php
                        $assigned_idList = array();
                        if(!empty($data->daftar_nama_pengguna)) {
                            foreach($data->daftar_nama_pengguna as $assigned) {
                                $assigned_idList[] = intval($assigned->users_id);
                            }
                        }
                        ?>

                        <select name="daftar_pengguna[]" class="form-control input-sm" multiple="true">
                            <option value=""></option>
                            <?php if(!empty($operasionalLists)) { ?>
                            <?php foreach($operasionalLists as $operasional) { ?>
                                <option value="<?php echo $operasional->id ?>" <?php echo (in_array($operasional->id, $assigned_idList))?'selected':'' ?>><?php echo $operasional->name ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                        <div class="small"><i>Klik Ctrl untuk memilih lebih dari satu pengguna.</i></div>
                      <?php } else { ?>
                        <p class="form-control-static">
                        <?php
                        if(!empty($data->daftar_nama_pengguna)) {
                          // print_r($data->daftar_nama_pengguna);
                          $count = sizeof($data->daftar_nama_pengguna);
                          $i = 1;
                          foreach($data->daftar_nama_pengguna as $lists) {
                            $i++;
                            echo $lists->nama_pengguna.(($count == $i)?', ':'');
                          }
                        }
                        ?>
                        </p>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="saldo" class="col-sm-4 control-label">Saldo</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" name="saldo" value="<?php echo $data->saldo ?>" />
                      </div>
                    </div>

		        </div>
		        <div class="modal-footer">
              <?php if($this->auth->isSuperAdmin()) { ?>
              <a href="<?php echo site_url('perusahaan/delete/'.$data->id) ?>" id="btnRemove" class="btn btn-sm btn-danger" style="float: left;" data-nama_perusaahaan="<?php echo $data->nama_perusahaan ?>"><i class="fa fa-remove"></i> Hapus Perusahaan</a>
              <?php } ?>


              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="javascript:$.fancybox.close();" ><i class="glyphicon glyphicon-arrow-left"></i> Tutup</button>&nbsp;
                <button type="submit" id="btnSave" class="btn btn-sm btn-primary pull-right"><i class="fa fa-save"></i>&nbsp; Simpan</button>

		      	</div>

	      	<?php echo form_close(); ?>

      	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
