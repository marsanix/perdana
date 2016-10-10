<div class="modal fade bs-example-modal-lg in" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false" style="display: block; padding-right: 17px;">
    <div class="modal-dialog">
      	<div class="modal-content">

      	<?php echo form_open('users/add/save', array('name' => 'frmAddUsers', 'class' => 'form-horizontal')); ?>

		        <div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:$.fancybox.close();"><span aria-hidden="true">&times;</span></button>
		          	<h4 class="modal-title" id="myLargeModalLabel">Tambah User Pengguna</h4>
		        </div>
		        <div class="modal-body">

		        	<div id="msgForm"></div>

                    <div class="form-group">
                      <label for="name" class="col-sm-4 control-label">Nama Pengguna</label>
                      <div class="col-sm-6">
                        <input type="text" name="name" class="form-control input-sm"  />
                      </div>
                    </div>
	                <div class="form-group">
                      <label for="username" class="col-sm-4 control-label">Username</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" name="username" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="password" class="col-sm-4 control-label">Password</label>
                      <div class="col-sm-6">
                        <input type="password" class="form-control input-sm" name="password" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="groups" class="col-sm-4 control-label">Tipe Pengguna</label>
                      <div class="col-sm-6">
                        <select name="groups" class="form-control input-sm">
                          <option value=""></option>
                          <?php if(!empty($groupsLists)) { ?>
                          <?php foreach($groupsLists as $groups) { ?>
                            <option value="<?php echo $groups->id ?>"><?php echo $groups->name ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="saldo" class="col-sm-4 control-label">Status</label>
                      <div class="col-sm-6" style="margin-left: 20px;">
                        <label class="radio">
                          <input type="radio" name="disable" value="0" checked />Aktif
                        </label>
                        <label class="radio">
                          <input type="radio" name="disable" value="1" />Tidak Aktif
                        </label>
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