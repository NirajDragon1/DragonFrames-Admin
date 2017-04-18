
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
        <h1>All Tax</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('tax'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="javascript:void(0)">All Tax</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <div class="box-title col-md-4">All Tax</div>
                <div id="tblAdmin_filter" class="dataTables_filter box-title col-md-7 pull-right">Tax : <?= $tax_count ?></div>
                <!--<div class="col-md-4 text-right pad10"><a href="javascript:void(0)" class="text-light-blue user-add-modal" data-toggle="modal"><i class="fa fa-plus"></i> Add Users</a></div>-->
            </div>
            <div class="box-body table-responsive">
                <table id="tblAdmin" class="table table-striped table-bordered table-hover" width="100%"></table>
            </div>
            <div class="clearfix"></div>
        </div>
    </section><!-- /.content -->
</aside>
<div class="modal fade" id="add-background-box" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <?php echo form_open_multipart('tax/add_taxes', array('id' => 'frmTaxsave')); ?>
            <div class="modal-body">
                <div id="clonediv ">
                    <div class="form-group ">
                        <label>Title: </label><i style="color: red;">*</i>
                        <input name="vTitle" id="vTitle" type="text" class="form-control inputmargin required" placeholder="Title">
                    </div>
                    
                    <div class="form-group ">
                        <label>Value: </label>
                        <input name="vValue" id="vValue" type="text" class="form-control inputmargin required" placeholder="Value">
                    </div>   
                </div>
                </div>
              <div class="modal-footer clearfix" style="border: none;">
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-grey" data-dismiss="modal">Cancel</button>
            </div>
            </div>
          
         <div style="clear:both"></div>
            </form>
        </div><!-- /.modal-content -->
    </div>
<script type="text/javascript">
    $(function () {
    
        OTable = $('#tblAdmin').dataTable({
            bProcessing: true,
            bServerSide: true,
            bFilter:false,
            bPaginate: false,
            sAjaxSource: "<?php echo base_url(); ?>tax/get_list",
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
                {sName: "taxes_id", sTitle: 'tax ID', sWidth: '10%', bSortable: false},
                {sName: "title", sTitle: 'Title', bSortable: false, sWidth: '10%'},
                {sName: "value", sTitle: 'Value', sWidth: '10%', bSortable: false,},
                {sName: "operation", sTitle: 'Operation', bSortable: false, bSearchable: false, sWidth: '10%'}
            ],
            "aaSorting": [[0, 'asc']],
            "iDisplayLength": 250,
            "aLengthMenu": [[250, 500, 750, 1000], [250, 500, 750, 1000]],
            fnServerParams: function (aoData) {
                setTitle(aoData, this)
            },
            fnDrawCallback: function (oSettings) {
                $('.make-switch').bootstrapSwitch();
            }
        });
    });
    $(document).on('click', '.background-edit-modal', function (e) {
        e.preventDefault();
        var t = $(this);
        
        $('#add-background-box').modal('show');
        $('#add-background-box .modal-title').html('Edit Tax');
        $('#frmTaxsave')[0].reset();
        $('#add-background-box label.error').remove();
        $.getJSON(t.attr('href'), function (r) {
            $('#add-background-box form').attr('action', '<?php echo base_url('tax/edit'); ?>/' + r.taxes_id);
            $('[name="vTitle"]').val(r.title);
            $('[name="vValue"]').val(r.value);
        });
    });


    $(document).on('submit', '#frmTaxsave', function (e) {
        e.preventDefault();
        var t = $(this);
        if (t.valid()) {
            t.find('[type="submit"]').attr('disabled', 'disabled');

            $.ajax({
                url: t.attr('action'),
                type: t.attr("method"),
                dataType: "JSON",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.success == true) {
                        t.find('[type="submit"]').removeAttr('disabled');
                        $('#frmTaxsave')[0].reset();
                        $('#add-background-box').modal('hide');
                        toastr['success'](data.msg);
                        OTable.fnDraw();
                    } else {
                        t.find('[type="submit"]').removeAttr('disabled');
                        toastr['error'](data.msg);
                    }
                },
                error: function (data) {
                    console.log(data);
                    toastr['error'](data.msg);
                }
            });
        }
    });

</script>

<?php $this->load->view('common/footer.php'); ?>
