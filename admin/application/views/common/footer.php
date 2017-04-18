</div><!-- ./wrapper -->

<!-- add new calendar event modal -->


<!-- jQuery 2.0.2 -->
<!-- jQuery UI 1.10.3 -->
<script src="<?php echo base_url() . SITE_JS; ?>jquery-ui-1.10.3.min.js" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url() . SITE_JS; ?>bootstrap.min.js" type="text/javascript"></script>
<!-- Morris.js charts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?php echo base_url() . SITE_JS; ?>plugins/morris/morris.min.js" type="text/javascript"></script>
<!-- Sparkline -->
<script src="<?php echo base_url() . SITE_JS; ?>plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- jvectormap -->
<script src="<?php echo base_url() . SITE_JS; ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url() . SITE_JS; ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
<!-- fullCalendar -->
<?php /*
  <script src="<?php echo base_url().SITE_JS;?>plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
 * */ ?>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url() . SITE_JS; ?>plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url() . SITE_JS; ?>plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url() . SITE_JS; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<!-- iCheck -->
<script src="<?php echo base_url() . SITE_JS; ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>

<script src="<?php echo base_url() . SITE_JS; ?>plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url(SITE_JS . 'plugins/datatables/dataTables.bootstrap.js'); ?>" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(SITE_JS . 'AdminLTE/app.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url(SITE_JS . 'validator/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url(SITE_JS . 'bootstrap-switch.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(SITE_JS . 'toastr.min.js'); ?>"></script>
<script type="text/javascript">
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-full-width",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
<?php
$flsh_msg = $this->session->userdata('flsh_msg');
if ($flsh_msg != '') {
    $flsh_msg_type = $this->session->userdata('flsh_msg_type');
    echo "toastr['$flsh_msg_type']('$flsh_msg');";
    unset_flash_msg();
}
?>
    function setTitle(aoData, a) {
        aoTitles = []; // this array will hold title-based sort info
        oSettings = a.fnSettings();  // the oSettings will give us access to the aoColumns info
        i = 0;
        for (ao in aoData) {
            name = aoData[ao].name;
            value = aoData[ao].value;

            if (name.substr(0, "iSortCol_".length) == "iSortCol_") {
                // get the column number from "ao"
                iCol = parseInt(name.replace("iSortCol_", ""));
                sName = "";
                if (oSettings.aoColumns[value])
                    sName = oSettings.aoColumns[value].sName;
                // create an entry in aoTitles (which will later be appended to aoData) for this column
                aoTitles.push({name: "iSortTitle_" + iCol, value: sName});
                i++;
            }
        }
        for (ao in aoTitles)
            aoData.push(aoTitles[ao]);
    }
    $(document).on('click', '.lnk-delete', function (e) {
        e.preventDefault();
        if (confirm("Are you sure you want to delete?")) {
            $.getJSON($(this).attr('href'), function (r) {
                if (r.success)
                    toastr['success']('Record deleted successfully');
                else
                    toastr['error']('Some error occured while deleting. Please try again later');
                OTable.fnDraw();
            });
        }
    });
    $(document).on('switchChange.bootstrapSwitch', '.make-switch', function (event, state) {
        var curState = state;
        var t = $(this);
        var st = curState == true ? 1 : 0;
        $.post(t.data('action'), {status: st}, function (r) {
            toastr['success']('Status updated successfully');
        //alert(st);
        //return false;
        if (st == '1' || st == '0') {
                setTimeout(myFunction, 1000)
            }
        });
    });
    
    function myFunction() {
        //location.reload();
    }
    
</script>
</body>
</html>