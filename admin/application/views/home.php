<?php $this->load->view('common/header.php'); ?>
<?php $this->load->view('common/sidebar-left.php'); ?>
<link href="<?php echo base_url().SITE_CSS;?>bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url().SITE_JS;?>bootstrap-datepicker.js"></script> 

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Dashboard
                    <small>Control panel</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Dashboard</li>
                </ol>
            </section>

            <!-- Responsive Tabs -->
            <ul class="nav nav-tabs responsive customnavset" id="myTab">
                <li class="active"><a href="#Summary">Summary</a></li>
                <li><a href="#current_search">Current Searches</a></li>
                <li><a href="#InNegotiations">In Negotiations</a></li>
                <li><a href="#MostRP">Most Recent Purchases</a></li>
            </ul>
            <div class="tab-content responsive">
                    <div class="tab-pane active" id="Summary"></div>
                    
                    <div class="tab-pane" id="current_search">
                        <section class="content">
                            <div class="box">
                                <div class="box-header">
                                    <div class="box-title col-md-8">Today's Appointment Search</div>
                                </div>
                                <div class="box-body table-responsive">
                                    <table id="tblAdmin" class="table table-striped table-bordered table-hover" width="100%"></table>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </section>
                    </div>

                    
                    <div class="modal fade" id="details_new" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content-pop">
                                <section class="content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>    
                                    <div class="box-body table-responsive">
                                        <table id="ABtblAdmin" class="table table-striped table-bordered table-hover" width="100%"></table>
                                    </div>
                                    <div class="clearfix"></div>
                                </section>
                            </div>
                        </div>
                    </div>


                <div class="tab-pane" id="InNegotiations">
                    <section class="content">
                        <div class="box">
                            <div class="box-header">
                                <div class="box-title col-md-8">In Negotation</div>
                            </div>
                            <div class="box-body table-responsive">
                                <table id="tblNegoAdmin" class="table table-striped table-bordered table-hover" width="100%"></table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </section>
                </div>
                
                <div class="tab-pane" id="MostRP">
                    <section class="content">
                        <div class="box">
                            <div class="box-header">
                                <div class="box-title col-md-8">Most Recent Purchase</div>
                            </div>
                            <div class="box-body table-responsive">
                                <table id="tblPurchaseAdmin" class="table table-striped table-bordered table-hover" width="100%"></table>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- Responsive Tabs -->

            <!-- Main content -->
            
    </aside>

<div class="modal fade" id="details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content"> 
            <div class="user">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title-user"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>First Name</label>
                            <label>:</label>
                            <label id="UserFirstName"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Last Name</label>
                            <label>:</label>
                            <label id="UserLastName"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Address</label>
                            <label>:</label>
                            <label id="UserAddress"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label>City</label>
                            <label>:</label>
                            <label id="UserCity"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>State</label>
                            <label>:</label>
                            <label id="UserState"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Zip Code</label>
                            <label>:</label>
                            <label id="UserZip"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Phone No</label>
                            <label>:</label>
                            <label id="UserPhone"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <label>:</label>
                            <label id="UserEmail"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Registered On</label>
                            <label>:</label>
                            <label id="UserRegistration"></label>
                        </div>
                    </div>
            </div>
        </div>
        
            <div class="insurnced">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Insured's ID Number</label>
                            <label>:</label>
                            <label id="IDNumbe"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Insured's Name</label>
                            <label>:</label>
                            <label id="InsureName"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Insured's Address</label>
                            <label>:</label>
                            <label id="InsureAddress"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label>City</label>
                            <label>:</label>
                            <label id="City"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>State</label>
                            <label>:</label>
                            <label id="State"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Zip Code</label>
                            <label>:</label>
                            <label id="Zip"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Phone No</label>
                            <label>:</label>
                            <label id="Phone"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Insured's Policy Group Or FECA Number</label>
                            <label>:</label>
                            <label id="FECANumber"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Insured's DOB</label>
                            <label>:</label>
                            <label id="DOB"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Insurance Plan Name Or Program Name</label>
                            <label>:</label>
                            <label id="InsurancePlanName"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Employer's Name Or School Name</label>
                            <label>:</label>
                            <label id="EmployerName"></label>
                        </div>
                    </div>
            </div>
        </div>
        <div class="modal-content-new">
            <div class="modal-header">
                <h4 class="modal-title-new"></h4>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Patient’s First Name</label>
                            <label>:</label>
                            <label id="PatientFirstName"></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Patient’s Last Name</label>
                            <label>:</label>
                            <label id="PatientLastName"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Patient’s DOB</label>
                            <label>:</label>
                            <label id="PatientDOB"></label>
                        </div>
                    <div class="form-group col-md-6">
                        <label>Patient’s Email Address</label>
                        <label>:</label>
                        <label id="PatientEmailAddress"></label>
                    </div>
                    </div><div class="row">
                    <div class="form-group col-md-6">
                        <label>Patient’s Street Address</label>
                        <label>:</label>
                        <label id="PatientStreetAddress"></label>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Patient’s City</label>
                        <label>:</label>
                        <label id="PatientCity"></label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Patient’s State</label>
                        <label>:</label>
                        <label id="PatientState"></label>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Patient’s Zip Code</label>
                        <label>:</label>
                        <label id="PatientZipCode"></label>
                    </div>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-6">
                        <label>Patient’s Phone</label>
                        <label>:</label>
                        <label id="PatientPhone"></label>
                    </div>
                </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="add-note" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <form action="javascript:void(0);" method="post" id="addbuyernote">
                <div class="modal-body">
                    <div id="clonediv">
                        <div class="form-group">
                            <label>Contact By: </label></br>
                            <input name="contacted_by" id="contacted_by" autocomplete="off" type="text" class="form-control inputmargin required" placeholder="Contact By" >
                        </div>
                        <div class="form-group">
                            <label>Contact Date: </label>
                            <input name="contacted_date" id="contacted_date" type="text" class="form-control inputmargin required" data-provide="datepicker" placeholder="Contact Date">
                        </div>
                        <div class="form-group">
                            <label>Note: </label>
                            <textarea name="note" id="note" class="form-control required" placeholder="Please enter Note"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer clearfix">
                    <button type="submit" class="btn btn-success buyer_save">Save</button>
                    <button type="button" class="btn btn-grey" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div>
</div>

<div class="modal fade" id="trt-details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="treat_details"></div>
        </div><!-- /.modal-content -->
    </div>
</div>

<script type="text/javascript">
var nowDate = new Date();
var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

$(function(){
    $('#contacted_date').datepicker({ startDate: today }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    OTable= $('#tblAdmin').dataTable( {
        bProcessing: true,
        bServerSide: true,
        sAjaxSource: "<?php echo base_url();?>home/get_list",
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
            { sName: "search_on", sTitle : 'Created Date', sWidth:'10%' },
            { sName: "speciality", sTitle : 'Speciality', sWidth:'5%' },
            { sName: "city", sTitle : 'Address', sWidth:'15%' },
            { sName: "appointment_type", sTitle : 'Appointment Type', sWidth:'10%' },
            { sName: "symptom_name", sTitle : 'Symptom', sWidth:'10%' },
            { sName: "appointment_date", sTitle : 'Appointment Date', sWidth:'10%' },
            { sName: "appointment_start_time", sTitle : 'Start Time', sWidth:'5%' },
            { sName: "appointment_end_time", sTitle : 'End Time', sWidth:'5%' },
            { sName: "emailaddress", sTitle : 'User ID', sWidth:'10%' },
            { sName: "visitor_details", sTitle : 'Unique visitor details', sWidth:'10%' },
            { sName: "contact_details", sTitle : 'Contact details', sWidth:'10%' },
            { sName: "supplier_available", sTitle : 'Supplier Available', sWidth:'10%' },
            { sName: "assign_to", sTitle : 'Assigned to', sWidth:'10%' },
            { sName: "note", sTitle : 'Note', sWidth:'10%' },
        ],
        "aaSorting": [[0,'desc']],
        fnServerParams: function(aoData){setTitle(aoData, this)},
        fnDrawCallback: function( oSettings ) {
            $('.make-switch').bootstrapSwitch();
        }       
    });

    /* View Log */

    var cou = '1';
    $(document).on('click','.view_log',function(e){
        if(cou > 1){
            $('#ABtblAdmin').dataTable().fnClearTable();
            $('#ABtblAdmin').dataTable().fnDestroy();
        
        }
        cou = 2;

        var log_id = $(this).attr('ng_id');
        $('#details_new').modal('show');
        $('.modal-content-pop').show();

        OSTable= $('#ABtblAdmin').dataTable( {
            bProcessing: true,
            bServerSide: true,
            sAjaxSource: "<?php echo base_url();?>home/view_log/"+log_id,
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
                { sName: "id", sTitle : 'ID', bSortable:false,bSearchable:false, sWidth:'10%' },
                { sName: "price", sTitle : 'Price', bSortable:false,bSearchable:false, sWidth:'5%' },
                { sName: "counter_offer", sTitle : 'Counter Offer', bSortable:false,bSearchable:false, sWidth:'15%' },
                { sName: "responce", sTitle : 'Responce By', bSortable:false,bSearchable:false, sWidth:'10%' },
                { sName: "final", sTitle : 'Status', bSortable:false,bSearchable:false, sWidth:'10%' },
            ],
            "aaSorting": [[0,'desc']],
            fnServerParams: function(aoData){setTitle(aoData, this)},
            fnDrawCallback: function( oSettings ) {
                $('.make-switch').bootstrapSwitch();
                $('.modal-content-pop div.row').remove();
            }       
        });
    });

    /* End */

    /* In Negotation  */
    OPTable= $('#tblNegoAdmin').dataTable( {
        bProcessing: true,
        bServerSide: true,
        sAjaxSource: "<?php echo base_url();?>home/get_negotation_list",
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
            { sName: "id", sTitle : '#ID', sWidth:'10%' },
            { sName: "treatment_name", sTitle : 'Name', sWidth:'5%' },
            { sName: "treatment_type", sTitle : 'Type', sWidth:'15%' },
            { sName: "speciality", sTitle : 'Speciality', sWidth:'10%' },
            { sName: "npi", sTitle : 'Doctor NPI', sWidth:'10%' },
            { sName: "city", sTitle : 'Address', sWidth:'10%' },
            { sName: "appointment_date", sTitle : 'Date Time', sWidth:'60%' },
            { sName: "price", sTitle : 'Price', sWidth:'60%' },
            { sName: "counter_offer", sTitle : 'Counter Offer', sWidth:'60%' },
            { sName: "offer", sTitle : 'Admin Offer',bSortable:false,bSearchable:false, sWidth:'60%' },
            { sName: "responce_by", sTitle : 'Responce By', bSortable:false,bSearchable:false, sWidth:'60%' },
            { sName: "emailaddress", sTitle : 'User Email', sWidth:'10%' },
            { sName: "status", sTitle : 'Status', bSortable:false,bSearchable:false, sWidth:'10%' },
            { sName: "view_log", sTitle : 'View Log', bSortable:false,bSearchable:false, sWidth:'10%' },
        ],
        "aaSorting": [[0,'desc']],
        fnServerParams: function(aoData){setTitle(aoData, this)},
        fnDrawCallback: function( oSettings ) {
            $('.make-switch').bootstrapSwitch();
        }       
    });

    /* Recent Purchase */
    OPTable= $('#tblPurchaseAdmin').dataTable( {
        bProcessing: true,
        bServerSide: true,
        sAjaxSource: "<?php echo base_url();?>home/get_recent_purchase_list/1",
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
            { sName: "ap_id", sTitle : '#ID', sWidth:'10%' },
            { sName: "speciality", sTitle : 'Specialty', sWidth:'10%' },
            { sName: "symptom_name", sTitle : 'Reason for Visit', sWidth:'5%' },
            { sName: "appointment_type", sTitle : 'Appointment Type', sWidth:'15%' },
            { sName: "appointment_date", sTitle : 'Appointment Date and Time', sWidth:'10%' },
            { sName: "npi", sTitle : 'Location', bSortable:false,sWidth:'10%' },
            { sName: "emailaddress", sTitle : 'User', sWidth:'10%' },
            { sName: "patient_firstname", sTitle : 'Patient Name', sWidth:'60%' },
            { sName: "buyer_insurance_name", sTitle : 'Insurance', sWidth:'60%' },
            { sName: "treatment_name", sTitle : 'Treatment Name and CPT', sWidth:'60%' },
            { sName: "npi", sTitle : 'Doctor NPI', sWidth:'60%' },
            { sName: "average_price", sTitle : 'Average Charges', sWidth:'60%' },
            { sName: "ap_counter_offer", sTitle : 'CarePays Price', sWidth:'10%' },
            { sName: "ap_counter_offer", sTitle : 'Payable Amount', sWidth:'10%' },
            { sName: "amount_saved", sTitle : 'Amount saved', bSortable:false,bSearchable:false, sWidth:'10%' },
            { sName: "actions", sTitle : 'Actions', bSortable:false,bSearchable:false, sWidth:'10%' },
        ],
        "aaSorting": [[0,'desc']],
        fnServerParams: function(aoData){setTitle(aoData, this)},
        fnDrawCallback: function( oSettings ) {
            $('.make-switch').bootstrapSwitch();
        }       
    });

    /*setTimeout(function(){
        $('#tblAdmin').dataTable().fnClearTable();
        $('#tblAdmin').dataTable().fnDestroy();
        refresh_search()
    },  50000);*/
    
     /*setTimeout(function(){
        $('#tblAdmin').dataTable().fnClearTable();
        $('#tblAdmin').dataTable().fnDestroy();
        refresh_negotation()
    },  50000);*/

function refresh_search(){
        $('#tblAdmin').dataTable( {
            bRetrieve: true, 
            bProcessing: true,
            bServerSide: true,
            sAjaxSource: "<?php echo base_url();?>home/get_list",
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
            { sName: "search_on", sTitle : 'Created Date', sWidth:'10%' },
            { sName: "speciality", sTitle : 'Speciality', sWidth:'5%' },
            { sName: "city", sTitle : 'Address', sWidth:'15%' },
            { sName: "appointment_type", sTitle : 'Appointment Type', sWidth:'10%' },
            { sName: "symptom_name", sTitle : 'Symptom', sWidth:'10%' },
            { sName: "appointment_date", sTitle : 'Appointment Date', sWidth:'10%' },
            { sName: "appointment_start_time", sTitle : 'Start Time', sWidth:'5%' },
            { sName: "appointment_end_time", sTitle : 'End Time', sWidth:'5%' },
            { sName: "emailaddress", sTitle : 'User ID', sWidth:'10%' },
            { sName: "visitor_details", sTitle : 'Unique visitor details', sWidth:'10%' },
            { sName: "contact_details", sTitle : 'Contact details', sWidth:'10%' },
            { sName: "supplier_available", sTitle : 'Supplier Available', sWidth:'10%' },
            { sName: "assign_to", sTitle : 'Assigned to', sWidth:'10%' },
            { sName: "note", sTitle : 'Note', sWidth:'10%' },
            ],
            "aaSorting": [[0,'desc']],
            fnServerParams: function(aoData){setTitle(aoData, this)},
            fnDrawCallback: function( oSettings ) {
                $('.make-switch').bootstrapSwitch();
            }       
        });

        setTimeout(function(){
            $('#tblAdmin').dataTable().fnClearTable();
            $('#tblAdmin').dataTable().fnDestroy();
            refresh_search()
        },  50000)
    }

    function refresh_negotation(){
        OPTable= $('#tblNegoAdmin').dataTable( {
            bProcessing: true,
            bServerSide: true,
            sAjaxSource: "<?php echo base_url();?>home/get_negotation_list",
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
                { sName: "id", sTitle : '#ID', sWidth:'10%' },
                { sName: "treatment_name", sTitle : 'Name', sWidth:'5%' },
                { sName: "treatment_type", sTitle : 'Type', sWidth:'15%' },
                { sName: "speciality", sTitle : 'Speciality', sWidth:'10%' },
                { sName: "npi", sTitle : 'Doctor NPI', sWidth:'10%' },
                { sName: "city", sTitle : 'Address', sWidth:'10%' },
                { sName: "appointment_date", sTitle : 'Date Time', sWidth:'60%' },
                { sName: "price", sTitle : 'Price', sWidth:'60%' },
                { sName: "counter_offer", sTitle : 'Counter Offer', sWidth:'60%' },
                { sName: "offer", sTitle : 'Admin Offer',bSortable:false,bSearchable:false, sWidth:'60%' },
                { sName: "responce_by", sTitle : 'Responce By', bSortable:false,bSearchable:false, sWidth:'60%' },
                { sName: "emailaddress", sTitle : 'User Email', sWidth:'10%' },
                { sName: "status", sTitle : 'Status', bSortable:false,bSearchable:false, sWidth:'10%' },
                { sName: "view_log", sTitle : 'View Log', bSortable:false,bSearchable:false, sWidth:'10%' },
            ],
            "aaSorting": [[0,'desc']],
            fnServerParams: function(aoData){setTitle(aoData, this)},
            fnDrawCallback: function( oSettings ) {
                $('.make-switch').bootstrapSwitch();
            }       
        });

        setTimeout(function(){
            $('#tblNegoAdmin').dataTable().fnClearTable();
            $('#tblNegoAdmin').dataTable().fnDestroy();
            refresh_negotation();
        },  2*60*1000)
    }

});

$(document).on('click','.lnkdetails',function(e){
    $('').insertBefore('.modal-title-new');
    e.preventDefault();
    var t = $(this);
    $.getJSON(t.attr('href'),function(r){
        if(r.patient_firstname === null || r.patient_firstname === '' || r.patient_firstname === 'undefined'){
            alert('No data available.');
        }else{
            $('#details').modal('show');
            $('.modal-content-new').show();
            $('#details .modal-title-new').html('Patient Details');
            //Patient Information
            $('#PatientFirstName').html(r.patient_firstname);
            $('#PatientLastName').html(r.patient_lastname);
            $('#PatientDOB').html(r.patient_dob);
            $('#PatientEmailAddress').html(r.patient_email_address);
            if(r.patient_address_line_2 != '' && r.patient_address_line_2 != 'undefined'){
                $('#PatientStreetAddress').html(r.patient_address_line_1+', '+r.patient_address_line_2);    
            }else{
                $('#PatientStreetAddress').html(r.patient_address_line_1);    
            }
            
            $('#PatientCity').html(r.patient_city);
            $('#PatientState').html(r.patient_state);
            $('#PatientZipCode').html(r.patient_zip);
            $('#PatientPhone').html(r.patient_phone_number);
            
            //Insured Information
            if(r.buyer_insurance_id === null || r.buyer_insurance_id === '' || r.buyer_insurance_id === 'undefined'){
                $('.insurnced').hide();
                if(r.emailaddress === null || r.emailaddress === '' || r.emailaddress === 'undefined'){
                    $('').insertBefore('.modal-title-new');
                    $('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>').insertBefore('.modal-title-new');
                }
            }else{
                $('.insurnced').show();
                $('#details .modal-title').html('Insurance Details');
                $('#IDNumbe').html(r.buyer_insurance_id);
                if( r.buyer_insurance_lastname === null && r.buyer_insurance_lastname === '' && r.buyer_insurance_lastname === 'undefined'){
                    $('#InsureName').html(r.buyer_insurance_firstname);    
                }else{
                    $('#InsureName').html(r.buyer_insurance_firstname+' '+r.buyer_insurance_lastname);
                }
                
                if(r.buyer_insurance_address_2 === null || r.buyer_insurance_address_2 === '' || r.buyer_insurance_address_2 === 'undefined'){
                    $('#InsureAddress').html(r.buyer_insurance_address_1);    
                }else{
                    $('#InsureAddress').html(r.buyer_insurance_address_1+', '+r.buyer_insurance_address_2);
                }
                $('#City').html(r.buyer_insurance_city);
                $('#State').html(r.buyer_insurance_state);
                $('#Zip').html(r.buyer_insurance_zip_code);
                $('#Phone').html(r.buyer_insurance_phone);
                $('#FECANumber').html();
                $('#DOB').html(r.buyer_insurance_dob);
                $('#InsurancePlanName').html(r.plan_type);
                $('#EmployerName').html(r.name_of_employer);
            }

            if(r.emailaddress === null || r.emailaddress === '' || r.emailaddress === 'undefined'){
                $('.user').hide();
            }else{
                $('#details .modal-title-user').html('User Details');
                $('#UserFirstName').html(r.firstname);
                $('#UserLastName').html(r.lastname);
                if(r.address2 === null || r.address2 === '' || r.address2 === 'undefined'){
                    $('#UserAddress').html(r.address1);
                }else{
                    $('#UserAddress').html(r.address1+', '+r.address2);
                }
                $('#UserCity').html(r.city);
                $('#UserState').html(r.state);
                $('#UserZip').html(r.zip);
                $('#UserPhone').html(r.phone);
                $('#UserEmail').html(r.emailaddress);
                $('#UserRegistration').html(r.enter_datetime);
            }
        }
    });
});

$(document).on('click','.addnote',function(e){
    $('.buyer_save').show();
    $('#contacted_by').val('');
    $('#contacted_date').val('');
    $('#note').val('');
    $("#contacted_date").datepicker("enable");  
    $('#contacted_by').removeAttr( "readonly" );
    $('#contacted_date').removeAttr('readonly');
    $('#note').removeAttr('readonly');

    
    e.preventDefault();
    var t = $(this);
    $('#add-note').modal('show');
    $('#add-note .modal-title').html('Add Buyer note');
    $('#add-note label.error').remove();
    var url = t.attr('href');
    $('#add-note form').attr('action',url);
});

$(document).on('submit','#addbuyernote', function(e){
    e.preventDefault();
    var t = $(this);
    if(t.valid()){
        t.find('[type="submit"]').attr('disabled','disabled');
        $.post(t.attr('action'), t.serialize(), function(r){
            t.find('[type="submit"]').removeAttr('disabled');
            if(r.success){
                $('#add-note').modal('hide');
                toastr['success'](r.msg);
                OTable.fnDraw();
            }else{
                toastr['error'](r.msg);
            }
        },'json');
    }
});

$(document).on('click','.viewnote',function(e){
    $('.buyer_save').hide();
    e.preventDefault();
    var t = $(this);
    $('#add-note').modal('show');
    $('#add-note label.error').remove();
    $("#contacted_date").datepicker("destroy");

    $.getJSON(t.attr('href'),function(r){
        $('#contacted_by').val(r.contacted_by);
        $('#contacted_date').val(r.contacted_date);
        $('#note').val(r.note);
        $('#contacted_by').attr('readonly','readonly');
        $('#contacted_date').attr('readonly','readonly');
        $('#note').attr('readonly','readonly');
        if(r.firstname === null || r.firstname === '' || r.firstname === 'undefined'){
            $('#add-note .modal-title').html('View Buyer note');
        }else{
            $('#add-note .modal-title').html('View Buyer note - '+r.firstname);
        }
    });
});

/* View User Details */

$(document).on('click','.viewuser',function(e){
    $('.user').show();
    $('').insertBefore('.modal-title-new');
    e.preventDefault();
    var t = $(this);
    $.getJSON(t.attr('href'),function(r){
        $('#details').modal('show');
        $('.insurnced').hide();
        $('.modal-content-new').hide();
        $('#details .modal-title-user').html('User Details');
        $('#UserFirstName').html(r.firstname);
        $('#UserLastName').html(r.lastname);
        if(r.address2 === null || r.address2 === '' || r.address2 === 'undefined'){
            $('#UserAddress').html(r.address1);
        }else{
            $('#UserAddress').html(r.address1+', '+r.address2);
        }
        $('#UserCity').html(r.city);
        $('#UserState').html(r.state);
        $('#UserZip').html(r.zip);
        $('#UserPhone').html(r.phone);
        $('#UserEmail').html(r.emailaddress);
        $('#UserRegistration').html(r.enter_datetime);
    });
});

/* View patients details */

$(document).on('click','.viewpatientdetails',function(e){
    $('.modal-title-new').prev('.close').remove();
    $('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>').insertBefore('.modal-title-new');
    $('#details').modal('show');
    $('.insurnced').hide();
    $('.user').hide();
    $('.modal-content-new').show();
    $('#details .modal-title-new').html('Patient Details');
    //Patient Information
    $('#PatientFirstName').html($(this).attr('patient_firstname'));
    $('#PatientLastName').html($(this).attr('patient_lastname'));
    $('#PatientDOB').html($(this).attr('patient_dob'));
    $('#PatientEmailAddress').html($(this).attr('patient_email_address'));
    $('#PatientStreetAddress').html($(this).attr('address'));    
    $('#PatientCity').html($(this).attr('patient_city'));
    $('#PatientState').html($(this).attr('patient_state'));
    $('#PatientZipCode').html($(this).attr('patient_zip'));
    $('#PatientPhone').html($(this).attr('patient_phone_number'));
});

$(document).on('click','.send_offer',function(e){
    var id = $(this).attr('negotation_id');
    var offer = $("#offer_price_"+id).val();
    if(offer == '' || offer == null || offer == 'undefined'){
        alert('Please enter counter offer price');
    }else{
        var url = "<?php echo base_url(); ?>/home/update_offer";
        $.post(url, { offer_price: offer , id: id} , function(r){
            if(r.success){
                toastr['success'](r.msg);
                OPTable.fnDraw();
            }else{
                toastr['error'](r.msg);
            }
        },'json');
    }

});


/* Treatment Details */

$(document).on('click','.viewtrtdetails',function(e){
    var trt = $(this).attr('long_description');
    $('#trt-details').modal('show');
    $('.treat_details').html(trt);
});

$(document).on('click','.send_final',function(e){
    var id = $(this).attr('negotation_id');
    var offer = $("#offer_price_"+id).val();
    if(offer == '' || offer == null || offer == 'undefined'){
        alert('Please enter counter offer');
    }else{
        var url = "<?php echo base_url(); ?>/home/update_offer";
        $.post(url, { offer_price: offer , id: id, final_offer:'1'} , function(r){
            if(r.success){
                toastr['success'](r.msg);
                OPTable.fnDraw();
            }else{
                toastr['error'](r.msg);
            }
        },'json');
    }
});

$( '#myTab a' ).click( function ( e ) {
      e.preventDefault();
        $( this ).tab( 'show' );
      });

      $( '#moreTabs a' ).click( function ( e ) {
        e.preventDefault();
        $( this ).tab( 'show' );
      });
fakewaffle.responsiveTabs(['xs', 'sm']);
// Responsive tabs JS
 

</script>
<?php $this->load->view('common/footer.php'); ?>