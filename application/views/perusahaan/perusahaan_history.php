<div class="modal fade bs-example-modal-lg in" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false" style="display: block; padding-right: 17px;">
    <div class="modal-dialog modal-lg">
      	<div class="modal-content">


		        <div class="modal-header">
		          	<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:$.fancybox.close();"><span aria-hidden="true">&times;</span></button>
		          	<h4 class="modal-title" id="myLargeModalLabel">Riwayat Perubahan Saldo</h4>
		        </div>
		        <div class="modal-body">

            <?php echo form_open('perusahaan/history_data', array('name' => 'frmHistoryPerusahaan', 'id' => 'frmHistoryPerusahaan', 'class' => 'form-horizontal')); ?>
              <input type="hidden" name="perusahaan" value="<?php echo $perusahaan_id ?>" />
              <!-- <div class="input-group">
                <input type="text" name="cari_history" class="form-control input sm">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
              </div> -->

            <?php echo form_close(); ?>

		        	<div id="contentHistory" class="table-responsive no-padding" style="margin-top: 10px;">

              </div>

		        </div>
		        <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm pull-right" data-dismiss="modal" onclick="javascript:$.fancybox.close();" ><i class="glyphicon glyphicon-arrow-left"></i> Tutup</button>
		      	</div>


      	</div>
    </div>
</div>

<script>
  $(function() {
    loadDataHistory();
    function loadDataHistory() {
        $('#contentHistory').html(html_loading());
        var form = $('#frmHistoryPerusahaan');
        var arrForm = form.serializeArray();
        arrForm[0] = {'name':'<?php echo $this->security->get_csrf_token_name() ?>','value':'<?php echo $this->security->get_csrf_hash() ?>'};
        console.log(arrForm);
        $.ajax({
            url:form.attr('action'),
            type:form.attr('method'),
            data:jQuery.param(arrForm),
            success: function(rdata) {
                $('#contentHistory').html(rdata);
            }
        });
        return false;
    }
  });

  function serialize_history_form() {
      var form = $('form[name="frmHistoryPerusahaan"]');
      var arrForm = form.serializeArray();
      arrForm['<?php echo $this->security->get_csrf_token_name() ?>'] = '<?php echo $this->security->get_csrf_hash() ?>';
      console.log(arrForm);
      return jQuery.param(arrForm);
  }

</script>