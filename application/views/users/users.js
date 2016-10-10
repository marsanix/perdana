$(function() {

    loadData();

    $('#frmSearchData').submit(function() {
        $('#tableContent').html(html_loading());
        var form = $(this);
        $.ajax({
            url:form.attr('action'),
            type:form.attr('method'),
            data:form.serialize(),
            success: function(rdata) {
                $('#tableContent').html(rdata);
            }
        });
        return false;
    });

    $(document).on('submit','form[name="frmSearchData"]', function() {
        loadData();
        return false;
    });

    $('#btnRefreshSearch').click(function() {
        $('#cari').val('');
        $('#frmSearchData').submit();
    });

    $('#per_page, #cari_groups').change(function() {
        $('#frmSearchData').submit();
    });

    $(document).on('click','#toggleCariUsers', function() {
        $('#frmSearchData').toggle();
    });

    function loadData() {
        $('#tableContent').html(html_loading());
        var form = $('#frmSearchData');
        var arrForm = form.serializeArray();
        arrForm[0] = {'name':'<?php echo $this->security->get_csrf_token_name() ?>','value':'<?php echo $this->security->get_csrf_hash() ?>'};
        console.log(arrForm);
        $.ajax({
            url:form.attr('action'),
            type:form.attr('method'),
            data:jQuery.param(arrForm),
            success: function(rdata) {
                $('#tableContent').html(rdata);
            }
        });
        return false;
    }

    $(document).on('click','.dialog-fancybox', function() {
        var me = $(this);

        $.fancybox({
            href: me.attr('href'),
            type: 'ajax',
            ajax: {
                type: "get"
            },
            scrolling: 'auto',
            closeBtn: false,
            openEffect: 'none',
            closeEffect: 'none',
            openSpeed: 'fast',
            closeSpeed: 'fast'
        });
        $('.fancybox-skin').removeClass('fancybox-skin');
        return false;
    });

    $(document).on('submit','form[name="frmAddUsers"]', function() {
        $('#frmAddUsersLoading').html('<img src="<?php echo assets_url('img/loading-bar.gif') ?>" alt="Loading..." />');
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
                $('#frmAddUsersLoading').html('');
                $('#btnRemove').attr('disabled','').attr('href','javascript:void(0);').attr('style','display:none');

                var msg = '<div class="alert alert-success alert-dismissible" role="alert">';
                    msg += '<h4><i class="icon fa fa-check"></i> Berhasil!</h4>';
                    msg += 'Pendaftaran users baru berhasil tersimpan.';
                    msg += '</div>';
                $('#msgForm').html(msg);
                $('#msgForm').show();

                $('#frmSearchData').submit();

                var msgFormTop = $('#msgForm').position().top ;
                var scrollAmount = msgFormTop - 200 ;

                $('#msgForm').animate({scrollTop: scrollAmount},1000);

              } else {
                $('#msgForm').html(jData.error);
                $('#frmAddUsersLoading').html('');
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

    $(document).on('submit','form[name="frmEditUsers"]', function() {
    $('#frmAddUsersLoading').html('<img src="<?php echo assets_url('img/loading-bar.gif') ?>" alt="Loading..." />');
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
            $('#frmAddUsersLoading').html('');
            $('#btnRemove').attr('disabled','').attr('href','javascript:void(0);').attr('style','display:none');

            var msg = '<div class="alert alert-success alert-dismissible" role="alert">';
                msg += '<h4><i class="icon fa fa-check"></i> Berhasil!</h4>';
                msg += 'Perubahan data/saldo berhasil tersimpan.';
                msg += '</div>';
            $('#msgForm').html(msg);
            $('#msgForm').show();

            $('#frmSearchData').submit();

            var msgFormTop = $('#msgForm').position().top ;
            var scrollAmount = msgFormTop - 200 ;

            $('#msgForm').animate({scrollTop: scrollAmount},1000);

          } else {
            $('#msgForm').html(jData.error);
            $('#frmAddUsersLoading').html('');
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

    $(document).on('click', '.rows-users-delete', function() {
        var tanya = confirm('Apakah Anda yakin akan menghapus data users ini?');
        var me = $(this);
        var nama_users = me.data('nama_users');
        if(tanya) {
            $.ajax({
                url:me.attr('href'),
                type:'get',
                success: function(rdata) {
                    if(rdata == '1') {
                        $.fancybox.close();
                        $('#frmSearchData').submit();

                        var msg = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                            msg += '<h4><i class="icon fa fa-check"></i> Berhasil!</h4>';
                            msg += 'Data users ' + nama_users + ' berhasil di hapus.' ;
                            msg += '</div>';
                        $('#msgSearchForm').html(msg);
                        setTimeout(function() {
                          $('#msgSearchForm').hide(20);
                          $('#msgSearchForm').html('');
                        }, 10000);
                    }
                }
            });
            return false;
        }
        return false;
    });

    $(document).on('click','#btnRemove', function() {
        var tanya = confirm('Apakah Anda yakin akan menghapus data users ini?');
        var me = $(this);
        var nama_users = me.data('nama_users');
        if(tanya) {
            $.ajax({
                url:me.attr('href'),
                type:'get',
                success: function(rdata) {
                    if(rdata == '1') {
                        $.fancybox.close();
                        $('#frmSearchData').submit();

                        var msg = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                            msg += '<h4><i class="icon fa fa-check"></i> Berhasil!</h4>';
                            msg += 'Data users ' + nama_users + ' berhasil di hapus.' ;
                            msg += '</div>';
                        $('#msgSearchForm').html(msg);
                        setTimeout(function() {
                          $('#msgSearchForm').hide(20);
                          $('#msgSearchForm').html('');
                        }, 10000);
                    }
                }
            });
            return false;
        }
        return false;
    });

});

function serialize_form() {
    var form = $('form[name="frmSearchData"]');
    var arrForm = form.serializeArray();
    arrForm['<?php echo $this->security->get_csrf_token_name() ?>'] = '<?php echo $this->security->get_csrf_hash() ?>';
    console.log(arrForm);
    return jQuery.param(arrForm);
}