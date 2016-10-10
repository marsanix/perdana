<div class="modal fade bs-example-modal-lg in" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false" style="display: block; padding-right: 17px;">
    <div class="modal-dialog">
      	<div class="modal-content">

      	<?php echo form_open('users/edit/'.$data->id.'/save', array('name' => 'frmAddUsers', 'class' => 'form-horizontal')); ?>
            <input type="hidden" name="id" value="<?php echo $data->id ?>" />
		        <div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:$.fancybox.close();"><span aria-hidden="true">&times;</span></button>
		          	<h4 class="modal-title" id="myLargeModalLabel">Ubah Data User Pengguna</h4>
		        </div>
		        <div class="modal-body">

		        	<div id="msgForm"></div>

                    <div class="form-group">
                      <label for="name" class="col-sm-4 control-label">Nama Pengguna</label>
                      <div class="col-sm-6">
                        <input type="text" name="name" class="form-control input-sm" value="<?php echo $data->name ?>" />
                      </div>
                    </div>
	                <div class="form-group">
                      <label for="username" class="col-sm-4 control-label">Username</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" name="username" value="<?php echo $data->username ?>" disabled />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="password" class="col-sm-4 control-label">Password</label>
                      <div class="col-sm-6">
                        <input type="password" class="form-control input-sm" name="password" id="password" placeholder="************" disabled />
                        <label class="checkbox" style="margin-left: 20px; margin-top: -5px; font-weight: normal;">
                          <input type="checkbox" name="change_password" id="change_password" value="1">Ubah password
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="groups" class="col-sm-4 control-label">Tipe Pengguna</label>
                      <div class="col-sm-6">
                        <select name="groups" class="form-control input-sm">
                          <option value=""></option>
                          <?php if(!empty($groupsLists)) { ?>
                          <?php foreach($groupsLists as $groups) { ?>
                            <option value="<?php echo $groups->id ?>" <?php echo is_selected($groups->id, $data->groups_id) ?>><?php echo $groups->name ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="saldo" class="col-sm-4 control-label">Status</label>
                      <div class="col-sm-6" style="margin-left: 20px;">
                        <label class="radio">
                          <input type="radio" name="disable" value="0" <?php echo is_checked($data->disable, 0) ?> />Aktif
                        </label>
                        <label class="radio">
                          <input type="radio" name="disable" value="1" <?php echo is_checked($data->disable, 1) ?> />Tidak Aktif
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
    $('#change_password').click(function() {
        var me = $(this);
        if(me.prop('checked')) {
            $('#password').removeAttr('disabled').val('').removeAttr('placeholder').focus();
        } else {
            $('#password').attr('disabled','').val('').attr('placeholder','************');
        }
    });
  });
</script>