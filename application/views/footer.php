
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2016 <a href="http://www.marsanix.com" target="_blank">MarsanixWeb</a>.</strong> All rights
    reserved.
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo assets_url('bootstrap/js/bootstrap.min.js') ?>"></script>
<!-- Morris.js charts -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> -->
<!-- <script src="<?php echo assets_url('plugins/morris/morris.min.js') ?>"></script> -->
<!-- Sparkline -->
<!-- <script src="<?php echo assets_url('plugins/sparkline/jquery.sparkline.min.js') ?>"></script> -->
<!-- jvectormap -->
<!-- <script src="<?php echo assets_url('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') ?>"></script> -->
<!-- <script src="<?php echo assets_url('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') ?>"></script> -->
<!-- jQuery Knob Chart -->
<!-- <script src="<?php echo assets_url('plugins/knob/jquery.knob.js') ?>"></script> -->
<!-- daterangepicker -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script> -->
<!-- <script src="<?php echo assets_url('plugins/daterangepicker/daterangepicker.js') ?>"></script> -->
<!-- datepicker -->
<!-- <script src="<?php echo assets_url('plugins/datepicker/bootstrap-datepicker.js') ?>"></script> -->
<!-- Bootstrap WYSIHTML5 -->
<!-- <script src="<?php echo assets_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') ?>"></script> -->
<!-- Slimscroll -->
<script src="<?php echo assets_url('plugins/slimScroll/jquery.slimscroll.min.js') ?>"></script>
<!-- FastClick -->
<script src="<?php echo assets_url('plugins/fastclick/fastclick.js') ?>"></script>
<!-- iCheck 1.0.1 -->
<!-- <script src="<?php echo assets_url('plugins/iCheck/icheck.min.js') ?>"></script> -->
<!-- AdminLTE App -->
<script src="<?php echo assets_url('js/app.min.js') ?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="<?php echo assets_url('js/pages/dashboard.js') ?>"></script> -->
<!-- AdminLTE for demo purposes -->
<!-- <script src="<?php echo assets_url('js/demo.js') ?>"></script> -->

<?php if (isset($js_link) && sizeof($js_link) > 0) { ?>
    <?php foreach ($js_link as $jslink) { ?>
        <script src="<?php echo $jslink ?>"></script>
    <?php } ?>
<?php } ?>

<?php if (isset($js_script) && sizeof($js_script) > 0) { ?>
    <script type="text/javascript">
    <?php foreach ($js_script as $jsscript) { ?>
        <?php // if(file_exists(FCPATH.str_replace('/','\\',APPPATH.'views\\'.$jsscript))) { ?>
        <?php echo $jsscript ?>
        <?php // }  ?>
    <?php } ?>
    </script>
<?php } ?>

<script type="text/javascript">
function html_loading() {
    return '<div class="center" style="padding-top: 50px; padding-bottom: 50px;"><img src="<?php echo assets_url('img/loading.gif') ?>" alt="Loading..." /></div>';
}
</script>

</body>
</html>
