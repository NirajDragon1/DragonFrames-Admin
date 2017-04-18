
<?php $this->load->view('common/header.php'); ?>
<?php $this->load->view('common/sidebar-left.php'); ?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/css/bootstrap-datepicker.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/js/bootstrap-datepicker.js"></script> 
<style type="text/css">
    .subcription_container li{clear:both;}
   .subcription_container input {float: left;margin-right: 5px;}
    .subcription_container span{float: left;}
    .note {color: #666;background-color: #FF7;padding: 2px 5px;margin: 0px 0 0 0;display: block;}
</style>
<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side">                
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>All Transactions</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('payment'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="javascript:void(0)">All Transactions</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <div class="box-title col-md-4">All Transactions</div>
                <div id="tblAdmin_filter" class="dataTables_filter box-title col-md-7 pull-right">Transactions : <?= $payment_count ?></div>
                <!--<div class="col-md-4 text-right pad10"><a href="javascript:void(0)" class="text-light-blue user-add-modal" data-toggle="modal"><i class="fa fa-plus"></i> Add Users</a></div>-->
            </div>
            <div class="box-body table-responsive">
                <table id="tblAdmin" class="table table-striped table-bordered table-hover" width="100%"></table>
            </div>
            <div class="clearfix"></div>
        </div>
    </section><!-- /.content -->
</aside>

<script type="text/javascript">
    $(function () {
    
        OTable = $('#tblAdmin').dataTable({
            bProcessing: true,
            bServerSide: true,
            paging: true,
            sAjaxSource: "<?php echo base_url(); ?>payment/get_list",
            fnServerData: function (sSource, aoData, fnCallback) {
                $.ajax({
                    dataType: 'json',
                    type: "POST",
                    url: sSource,
                    data: aoData,
                    success: fnCallback
                });
            },
            aoColumns: [
                {sName: "order_id", sTitle: 'Order ID', sWidth: '10%'},
                {sName: "photo_frame", sTitle: 'Photo Frame', bSortable: false, sWidth: '10%'},
                {sName: "photo_frame2", sTitle: 'Photo', bSortable: false, sWidth: '10%'},
                {sName: "first_name", sTitle: 'First Name', sWidth: '10%'},
                {sName: "last_name", sTitle: 'Last Name', sWidth: '10%'},
                {sName: "emailaddress", sTitle: 'Email', sWidth: '15%'},
                {sName: "phone", sTitle: 'Mobile', bSortable: false, sWidth: '10%'},
                {sName: "transaction_id", sTitle : 'Transaction id',sWidth:'15%' },
                {sName: "frametitle", sTitle: 'Frame Title', sWidth: '15%'},
                {sName: "price", sTitle: 'Price', sWidth: '5%'},
            ],
            "aaSorting": [[0, 'asc']],
            "aLengthMenu": [[250, 500, 750, 1000], [250, 500, 750, 1000]],
            fnServerParams: function (aoData) {
                setTitle(aoData, this)
            },
            fnDrawCallback: function (oSettings) {
                $('.make-switch').bootstrapSwitch();
            }
        });
    });
</script>

<?php $this->load->view('common/footer.php'); ?>
