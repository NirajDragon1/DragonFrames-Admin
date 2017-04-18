
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
        <h1>All Users</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('users'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="javascript:void(0)">All Users</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <div class="box-title col-md-4">All Users</div>
                <div id="tblAdmin_filter" class="dataTables_filter box-title col-md-7 pull-right">Users : <?= $user_count ?></div>
                <!--<div class="col-md-4 text-right pad10"><a href="javascript:void(0)" class="text-light-blue user-add-modal" data-toggle="modal"><i class="fa fa-plus"></i> Add Users</a></div>-->
            </div>
            <div class="box-body table-responsive">
                <table id="tblAdmin" class="table table-striped table-bordered table-hover" width="100%"></table>
            </div>
            <div class="clearfix"></div>
        </div>
    </section><!-- /.content -->
</aside>
<div class="modal fade" id="add-user-box" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <?php echo form_open_multipart('users/add_users', array('id' => 'frmUserSave')); ?>
            <input name="isdelete" id="iIsDeleted" type="hidden" value="0">
            <input name="user_type_id" id="user_type_id" type="hidden" value="User">
            <input name="added_date" id="dAddedDate" type="hidden" value="<?php echo date("Y-m-d H:i:s"); ?>"> 
            <div class="modal-body">
                <div id="clonediv">
                    <div class="form-group ">
                        <label>First Name: </label><i style="color: red;">*</i>
                        <input name="first_name" id="first_name" type="text" class="form-control inputmargin required" placeholder="First Name">
                    </div>

                    <div class="form-group ">
                        <label>Last Name: </label><i style="color: red;">*</i>
                        <input name="last_name" id="last_name" type="text" class="form-control inputmargin required" placeholder="Last Name">
                    </div>

                    <div class="form-group ">
                        <label>Email: </label><i style="color: red;">*</i>
                        <input name="emailaddress" id="emailaddress" type="email" class="form-control inputmargin required" placeholder="Email Address">
                    </div>

                    <div class="form-group " id="upass">
                        <label>Password: </label><i style="color: red;">*</i>
                        <input name="password" id="vPassword" type="password" class="form-control inputmargin required" placeholder="Password">
                    </div>

                    <div class="form-group " id="ucpass">
                        <label>Confirm Password: </label><i style="color: red;">*</i>
                        <input name="ConfirmPassword" id="ConfirmPassword" type="password" class="form-control inputmargin required" placeholder="Confirm Password">
                    </div>
                    
                    <div class="form-group ">
                        <label>Mobile Number: </label><i style="color: red;">*</i>
                        <input name="phone" id="phone" type="text" class="form-control inputmargin required" placeholder="Mobile Number">
                    </div>

                    <div class="form-group ">
                        <label>Profile: </label>
                        <input name="profile_image" id="profile_image" type="file">
                    </div>
                    
                    <div class="form-group ">
                        <label>Address: </label><i style="color: red;">*</i>
                        <input name="address" id="address" type="text" class="form-control inputmargin required" placeholder="Address">
                    </div>
                    
                     <div class="form-group ">
                        <label>Zip Code: </label><i style="color: red;">*</i>
                        <input name="zipcode" id="zipcode" type="text" class="form-control inputmargin required" placeholder="Address">
                    </div>
                    
                     <div class="form-group ">
                        <label>City: </label><i style="color: red;">*</i>
                        <input name="city" id="city" type="text" class="form-control inputmargin required" placeholder="City">
                    </div>
                    
                     <div class="form-group ">
                        <label>State: </label><i style="color: red;">*</i>
                        <input name="state" id="state" type="text" class="form-control inputmargin required" placeholder="State">
                    </div>
                    
                     <div class="form-group ">
                        <label>Country: </label><i style="color: red;">*</i>
                        <input name="country" id="country" type="text" class="form-control inputmargin required" placeholder="Country">
                    </div>
                    
                    <div class="form-group">
                        <label>Is Verified: </label><i style="color: red;">*</i>
                        <select name="is_verified" id="is_verified" class="form-control required">
                            <option value="">Is Verified?</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status: </label><i style="color: red;">*</i>
                        <select name="isactive" id="isactive" class="form-control required">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">InActive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer clearfix">
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-grey" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {

        $("#emailaddress").on('blur', function () {
            var emailAddress = document.getElementById("emailaddress").value;
            $.ajax({
                type: 'post',
                url: "<?php echo site_url('users/checkEmailExistence'); ?>",
                data: {Email: emailAddress},
                success: function (data) {
                    if (data == "true")
                    {
                       
                    } else {
                        alert('This email id is already registered.');
                        $('#emailaddress').val('');
                        $('#emailaddress').focus();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    small_alert("Error", "error", "Internal Error");
                }

            });
        });
    })

    $(function () {
    
        $('#dDateofBirth').datepicker({format: 'dd-mm-yyyy', endDate: '1d'}).on('changeDate', function (ev) {
            $(this).datepicker('hide');
        });

        OTable = $('#tblAdmin').dataTable({
            bProcessing: true,
            bServerSide: true,
            sAjaxSource: "<?php echo base_url(); ?>users/get_list",
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
                {sName: "user_id", sTitle: 'ID', sWidth: '5%'},
                {sName: "emailaddress", sTitle: 'Email', sWidth: '15    %'},
                {sName: "first_name", sTitle: 'First Name', sWidth: '10%'},
                {sName: "last_name", sTitle: 'Last Name', sWidth: '10%'},
                {sName: "address", sTitle : 'Address',bSortable:false, sWidth:'25%' },
                {sName: "city", sTitle: 'City', sWidth: '5%'},
                {sName: "state", sTitle: 'State', sWidth: '5%'},
                {sName: "zipcode", sTitle: 'Zip', sWidth: '5%'},
                {sName: "phone", sTitle: 'Mobile', bSortable: false, sWidth: '10%'},
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


    $(document).on('click', '.user-add-modal', function (e) {
        e.preventDefault();
        $('#add-user-box form').attr('action', '<?php echo base_url('users/add_users'); ?>');
        $('#add-user-box').modal('show');
        $('#add-user-box .modal-title').html('Add User');
        $('#frmUserSave')[0].reset();
        $('#upass').show();
        $('#ucpass').show();
        $('#vEmail').removeAttr('readonly');
    });

    $(document).on('click', '.user-edit-modal', function (e) {
        e.preventDefault();
        $(".remove").remove();
        var t = $(this);
        $('#add-user-box').modal('show');
        $('#add-user-box .modal-title').html('Edit User');
        $('#frmUserSave')[0].reset();
        $('#add-user-box label.error').remove();
        $.getJSON(t.attr('href'), function (r) {
            $('#add-user-box form').attr('action', '<?php echo base_url('users/edit'); ?>/' + r.user_id);
            $('[name="first_name"]').val(r.first_name);
            $('[name="last_name"]').val(r.last_name);
            $('[name="emailaddress"]').val(r.emailaddress);
            $('[name="phone"]').val(r.phone);
            $('[name="address"]').val(r.address);
            $('[name="zipcode"]').val(r.zipcode);
            //$('[name="dDateofBirth"]').val(r.dDateofBirth);
            $('[name="country"]').val(r.country);
            $('[name="state"]').val(r.state);
            $('[name="city"]').val(r.city);
            $('[name="is_verified"]').val(r.is_verified);
            $('[name="isactive"]').val(r.isactive);
            $('#upass').hide();
            $('#ucpass').hide();
            $('#vEmail').attr('readonly', true);
        });
    });


    $(document).on('submit', '#frmUserSave', function (e) {
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
                    t.find('[type="submit"]').removeAttr('disabled');
                    $('#frmUserSave')[0].reset();
                    $('#add-user-box').modal('hide');
                    if (data.success) {
                        toastr['success'](data.msg);
                    }
                    else {
                        toastr['error'](data.msg);
                    }
                    OTable.fnDraw();
                },
                error: function (data) {
                    console.log(data);
                    toastr['error'](data.msg);
                }
            });
        }
    });
    
    function loadData(loadType, loadId, selId) {
        selId = selId || false;

        var dataString = 'loadType=' + loadType + '&loadId=' + loadId;

        $("#" + loadType + "_loader").show();
        $("#" + loadType + "_loader").fadeIn(400).html('Please wait... <img src="<?php echo base_url() . SITE_IMG; ?>loading.gif" />');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>users/loadData",
            data: dataString,
            cache: false,
            success: function (result) {
                $("#" + loadType + "_loader").hide();
                $("#" + loadType + "_dropdown").html("<option value=''>Select " + loadType.substr(0, 1).toUpperCase() + loadType.substr(1) + "</option>");
                $("#" + loadType + "_dropdown").append(result);
                $("#" + loadType + "_dropdown").val(selId);
            }
        });
    }
</script>

<?php $this->load->view('common/footer.php'); ?>
