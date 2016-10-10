$(function() {

    $('#terms_edit_profil').change(function() {
        $(this).prop( "checked", function(i, val) {
          if(val) {
                $('#btnSaveEditProfil').removeAttr('disabled');
            } else {
                $('#btnSaveEditProfil').attr('disabled','');
            }
        });
    });

    $('#terms_change_password').change(function() {
        $(this).prop( "checked", function(i, val) {
          if(val) {
                $('#btnSaveChangePassword').removeAttr('disabled');
            } else {
                $('#btnSaveChangePassword').attr('disabled','');
            }
        });
    });

    $('#frmEditProfil').submit(function() {
        var form = $(this);
        if($('#terms_edit_profil').prop('checked') === false) {
            $('#btnSaveEditProfil').attr('disabled','');
        }
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(rdata) {

                if(rdata == '1') {
                    var msg = '<div class="alert alert-success alert-dismissable">';
                        msg += '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>';
                        msg += '<h4><i class="icon fa fa-check"></i> Berhasil!</h4>';
                        msg += 'Perubahan profil Anda berhasil tersimpan.<br /><i>Silahkan keluar kemudian masuk kembali untuk melihat hasil perubahan.</i>';
                        msg += '</div>';
                    $('#msgEditProfil').html(msg);
                } else {
                    $('#msgEditProfil').html(rdata);
                }

                if($('#terms_edit_profil').prop('checked')) {
                    $('#btnSaveEditProfil').removeAttr('disabled');
                }

            }
        });
        return false;
    });

     $('#frmChangePassword').submit(function() {
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(rdata) {
                var jData = $.parseJSON(rdata);
                $('#frmChangePassword input:hidden[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val(jData.token);
                $('#msgChangePassword').show();
                if(jData.status == '1') {
                    $('#old_password').val('');
                    $('#new_password').val('');
                    $('#new_password2').val('');
                    $('#btnSaveChangePassword').attr('disabled','');
                    var msg = '<div class="alert alert-success alert-dismissable">';
                        msg += '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>';
                        msg += '<h4><i class="icon fa fa-check"></i> Berhasil!</h4>';
                        msg += 'Perubahan password Anda berhasil disimpan.';
                        msg += '</div>';
                    $('#msgChangePassword').html(msg);
                } else {
                    $('#msgChangePassword').html(jData.error);
                    setTimeout(function() {
                        $('#msgChangePassword').hide(20);
                        $('#msgChangePassword').html('');
                    }, 8000);
                }

            }
        });
        return false;
    });

    $('.btn-image-profil').click(function() {
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

    $(document).on('submit', '#frmChangePhoto', function() {
        var form = $(this);
        $('#btnSaveChangePhoto').attr('disabled','');
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(rdata) {
                $('#msgFormChangePhoto').show();
                if(rdata == 1) {
                    $('#old_password').val('');
                    $('#new_password').val('');
                    $('#new_password2').val('');
                    $('#terms_change_password').prop('checked', false);
                    $('#btnSaveChangePassword').attr('disabled','');
                    var msg = '<div class="alert alert-success alert-dismissable">';
                        msg += '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>';
                        msg += 'Success, your password has been changed.';
                        msg += '</div>';
                    $('#msgFormChangePhoto').html(msg);
                } else {
                    $('#msgFormChangePhoto').html(rdata);
                    setTimeout(function() {
                        $('#msgFormChangePhoto').hide(20);
                        $('#msgFormChangePhoto').html('');
                    }, 8000);
                }

                $('#btnSaveChangePhoto').removeAttr('disabled');

            }
        });
        return false;
    });

});