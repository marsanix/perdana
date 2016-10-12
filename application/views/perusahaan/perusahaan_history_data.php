<table class="table table-hover table-condensed table-striped">
  <thead>
  <tr class="center">
    <th>No.</th>
    <th>Waktu</th>
    <th>Deskripsi</th>
  </tr>
  </thead>
  <tbody>
  <?php if(!empty($historyList)) { ?>
  <?php $no = (1 + $page); foreach($historyList as $history) { ?>
  <tr>
    <td class="center"><?php echo $no ?></td>
    <td class="center"><?php echo FormatDateTime($history->datetime, 8) ?></td>
    <td><?php echo $history->description ?></td>
  </tr>
  <?php $no++; } ?>
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

    });
</script>