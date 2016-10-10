$(function() {
  //$('#frmCariPermintaan').toggle('hide');
  $(document).on('click','#toggleCariPermintaan', function() {
    $('#frmCariPermintaan').toggle();
  });

  $('#jenis_transaksi option:not(option:first)').attr('style','color:#555;');
  $(document).on('keyup change', '#jenis_transaksi', function() {
    if($(this).find('option:selected').val() != '') {
      $('#jenis_transaksi').attr('style','color:#555;');
    } else {
      $('#jenis_transaksi').attr('style','color:#9c9c9c;');
    }
  });

  //iCheck for checkbox and radio inputs
  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
  });
  //Red color scheme for iCheck
  $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
    checkboxClass: 'icheckbox_minimal-red',
    radioClass: 'iradio_minimal-red'
  });
  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
  });

  $('#frmAddPermintaan input[type=text]').addClass('text-capital');

  $(document).on('submit','form[name="frmAddPermintaan"]', function() {
    $('#frmAddPermintaanLoading').html('<img src="<?php echo assets_url('img/loading-bar.gif') ?>" alt="Loading..." />');
    $('#btnSave').attr('disabled','');
    var form = $(this);
    $.ajax({
      url: form.attr('action'),
      type: form.attr('method'),
      // dataType: 'json',
      data: form.serialize(),
      success: function(rdata) {
        if(rdata != '') {
          var jData = $.parseJSON(rdata);
          $('input:hidden[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val(jData.token);
          if(jData.status == '1') {
            $('#frmAddPermintaanLoading').html('');
            // $('#btnSave').removeAttr('disabled');
            $('#btnBack').attr('style','display: none;');
            $('#btnReset').attr('style','display: none;');
            $('#btnSave').attr('style','display: none;');

            var jumlah_booking = $('#jumlah_booking').val() ;

            form.find('input').val('');
            form.find('input[type=checkbox]').removeAttr('checked');
            form.find('.icheckbox_minimal-blue').removeClass('checked');
            form.find('select').prop('selectedIndex', 0).attr('style','color:#9c9c9c;');

            var selesai = '<div style="text-align: center;">';
                selesai += '<p style="font-weight: bold; font-size: large;">Permintaan Dana Telah di Ajukan Sebesar</p>';
                selesai += '<p style="font-size: large;">IDR '+ jumlah_booking +'</p>';
                selesai += '<div style="height: 30px;"></div>';

                selesai += '<div><a href="<?php echo site_url('permintaan') ?>" class="btn btn-primary">Lihat Riwayat Permintaan</a>&nbsp;&nbsp;&nbsp;&nbsp;';
                selesai += '<a href="<?php echo site_url('login/out') ?>" class="btn btn-danger">Keluar</a></td>';
                selesai += '</div>';
                selesai += '<div style="height: 30px;"></div>';
                selesai += '<p style="font-weight: bold;">SALDO</p>';
                selesai += '<p style="font-weight: bold;">'+jData.saldo+'</p>';
                selesai += '</div>';
            $('#frmAddPermintaan .box-body').html(selesai);
            $('.box .box-header').remove();


            /*
            auto_redirect_after_save(jData.insert_id);

            var msg = '<div class="alert alert-success alert-dismissible" role="alert">';
                msg += '<h4><i class="icon fa fa-check"></i> Permintaan dana berhasil di booking.</h4>';
                msg += 'Otomatis pindah ke halaman detail permintaan data Anda terhitung dari <span id="countdown_redirect">5</span>, atau &nbsp;<a href="'+jData.ticket_url+'" class="btn btn-xs btn-primary">Klik untuk pindah sekarang!</a>';
                msg += '</div>';
            $('#msgForm').html(msg);
            $('#msgForm').show();

            var msgFormTop = $('#msgForm').position().top ;
            var scrollAmount = msgFormTop - 200 ;

            $('#msgForm').animate({scrollTop: scrollAmount},1000);
            */

          } else {
            $('#msgForm').html(jData.error);
            $('#frmAddPermintaanLoading').html('');
            $('#btnSave').removeAttr('disabled');
            setTimeout(function() {
              $('#msgForm').hide(20);
              $('#msgForm').html('');
            }, 10000);
          }
        } else {
          $('#msgForm').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Invalid respond from processing, please contact Administrator.</div>');
        }
      }
    });
    return false;
  });

  function auto_redirect_after_save(insert_id) {
    var countdown = 4;
    setInterval(function() {
      if(countdown == 0) {
        window.location = '<?php echo base_url('pemintaan/view/') ?>'+insert_id+'<?php echo $this->config->item('url_suffix') ?>';
      }
      $('#countdown_redirect').html(countdown);
      countdown = countdown - 1;
    }, 1200);
  }

  $(".calendar").datepicker({
    autoclose: true,
    changeMonth: true,
    changeYear: true,
    format: 'dd/mm/yyyy',
    cursor: 'pointer',
    keyboardNavigation: true,
    Default: true,
    day: '<?php echo date('dd') ?>',
    defaultDate: '<?php echo CurrentDateTime(CurrentDate(), 7) ?>',
  });
  $(".calendar").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});

  $("#grt").inputmask("9999999999", {"placeholder": ""});
  $("#jumlah_booking").inputmask("999999999999999999", {"placeholder":""});

});