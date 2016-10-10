<table class="table table-bordered table-striped table-condensed table-hover">
<thead>
  <tr class="center middle">
    <th style="width: 70px">No</th>
    <th>Nama Pengguna</th>
    <th>Username</th>
    <th>Tipe Pengguna</th>
    <th>Status</th>
    <th class="col-sm-1">#</th>
  </tr>
</thead>
<tbody>
  <?php if(!empty($usersList)) { ?>
  <?php $no = 1 + $page; foreach($usersList as $users) { ?>
  <tr class="rows-users">
    <td class="center"><?php echo $no ?>.</td>
    <td><?php echo $users->name ?></td>
    <td><?php echo $users->username ?></td>
    <td class="center"><?php echo $users->groups_name ?></td>
    <td class="center"><?php echo ($users->disable)?'Tidak Aktif':'Aktif' ?></td>
    <td class="center">
      <a href="<?php echo site_url('users/edit/'.$users->id) ?>" class="dialog-fancybox"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
      <a href="<?php echo site_url('users/delete/'.$users->id) ?>" data-username="<?php echo $users->username ?>" class="rows-users-delete" style="color: #FF562F;"><i class="fa fa-trash"></i></a>
    </td>
  </tr>
  <?php $no++; } ?>
  <?php } else { ?>
  <tr>
    <td colspan="6">Tidak ada data</td>
  </tr>
  <?php } ?>
  </tbody>
</table>

<div class="box-footer clearfix">
<nav><?php echo $pagination ?></nav>
</div>

<script>
    $(function() {
        $.ajax({
            url:'<?php echo site_url('trace/get_token') ?>',
            type: 'get',
            success: function(rdata) {
                if(rdata != '') {
                    $('input:hidden[name="<?php echo $this->security->get_csrf_token_name() ?>"]').val(rdata);
                }
            }
        });

        $('tr.rows-users').hover(
            function(){
                $(".rows-users-view", this).fadeIn(150);
            },
            function(){
                $(".rows-users-view", this).fadeOut(150);
            }
        );


    });
</script>