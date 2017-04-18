<?php $this->load->view('common/header.php'); ?>
<?php $this->load->view('common/sidebar-left.php'); ?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/css/bootstrap-datepicker.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/js/bootstrap-datepicker.js"></script> 
<style>
.note {color: #666;background-color: #FF7;padding: 2px 5px;margin: 0px 0 0 0;display: block;}
.form-group {
    border-bottom: 1px solid #cccccc;
    padding-bottom: 10px;
}
.form-group-cell {
    float: left;
    margin: 2% 2% 2%;
    width: 29%;
}
</style>
<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side">                
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>All Frames</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('home'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="javascript:void(0)">All Frames</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <div class="box-title col-md-8">All Frames</div>
                <div class="col-md-4 text-right pad10"><a href="javascript:void(0)" class="text-light-blue background-add-modal" data-toggle="modal"><i class="fa fa-plus"></i> Add Frames</a></div>
            </div>
            <div class="box-body table-responsive">
                <table id="tblFrame" class="table table-striped table-bordered table-hover" width="100%"></table>
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
            <?php echo form_open_multipart('frames/add_frames', array('id' => 'frmFramesave')); ?>
            <input name="iIsDeleted" id="iIsDeleted" type="hidden" value="0">
            <input name="dAddedDate" id="dAddedDate" type="hidden" value="<?php echo date("Y-m-d H:i:s"); ?>">
            <input name="dModifiedDate" id="dModifiedDate" type="hidden" value="<?php echo date("Y-m-d H:i:s"); ?>">
            <div class="modal-body">
                <div id="clonediv ">
                    <div class="form-group ">
                        <label>Frame Title: </label><i style="color: red;">*</i>
                        <input name="vTitle" id="vTitle" type="text" class="form-control inputmargin required" placeholder="Frame Title">
                    </div>
                    
                    <div class="form-group ">
                        <label>Frame Description: </label>
                        <input name="vDescription" id="vDescription" type="text" class="form-control inputmargin required" placeholder="Frame Description">
                    </div>   
                    
                    <div class="form-group ">
                        <label>Frame Price: </label><i style="color: red;">*</i>
                        <input name="vPrice" id="vPrice" type="text" class="form-control inputmargin required" placeholder="Frame Price">
                    </div>
                    
                    <div class="form-group">
                        <label>Status: </label><i style="color: red;">*</i>
                        <select name="eStatus" id="eStatus" class="form-control required">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">InActive</option>
                        </select>
                    </div>
                   <div class="form-group ">
                        <label>Frame Image: </label>
                        <div class="MI"></div>
                        <input class="temp_requerd" name="vFrameImage" id="vFrameImage" type="file">
<!--                        <span><i class="note">#Note : Please upload image with Max Size "4MB" , Max Width "1600" , Max Height "1600"  !</i></span>-->
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
</div>

<!-- Image Validation -->
<script>
    var _URL = window.URL || window.webkitURL;

    $("#vFrameImage").change(function (e) {

        var primaryFile = document.getElementById("vFrameImage");
        if (primaryFile != "")
        {
            jQuery('.temp_requerd').each(function ()
            {
                jQuery(this).attr('class', 'required');
            })
        }
    });


    $("#a_320X568").change(function (e) {
        var primaryFile = document.getElementById("vFrameImage");
        var mainTemplate = primaryFile.value;
        var mainExtension = mainTemplate.split('.').pop();


        var secondryFile = document.getElementById("a_320X568");
        var secondryTemplate = secondryFile.value;
        var secondryExtension = secondryTemplate.split('.').pop();

        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function () {
                if (this.width != '320' && this.height != '568') {
                    alert('Image is not valid please check Height and Width');
                    $('#a_320X568').val('');
                }
                if (mainExtension != secondryExtension) {
                    alert('Please verify file extention');
                    $('#a_320X568').val('');
                }
            };
            img.onerror = function () {
                alert("not a valid file: " + file.type);
            };
            img.src = _URL.createObjectURL(file);
        }

    });
   

</script>

<script type="text/javascript">

    $(function () {
        $("#frmFramesave").validate({
            rules: {
                vPrice: { 
                    number: true, 
                    maxlength: 7, 
                   
                },
            },
            messages: {
                vPrice: { 
                    number: "Please enter a valid number.", 
                    maxlength: "Please enter no more than 7 characters.", 
                }
            },
           
        });
        OTable = $('#tblFrame').dataTable({
            bProcessing: true,
            bServerSide: true,
            sAjaxSource: "<?php echo base_url(); ?>Frames/get_list",
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
                {sName: "frame_id", sTitle: 'ID', sWidth: '5%'},
                {sName: "Title", sTitle: 'Frame Title', sWidth: '10%'},
                {sName: "frame_image", sTitle: 'Frame Image', sWidth: '10%'},
                {sName: "frame_description", sTitle: 'Frame Description', sWidth: '15%'},
                {sName: "frame_price", sTitle: 'Frame Price', sWidth: '10%'},
                {sName: "created_date", sTitle: 'Created Date', sWidth: '10%'},
                {sName: "status", sTitle: 'Status', bSortable: false, bSearchable: false, sWidth: '10%'},
                {sName: "operation", sTitle: 'Operation', bSortable: false, bSearchable: false, sWidth: '10%'}
            ],
            "aaSorting": [[0, 'desc']],
            fnServerParams: function (aoData) {
                setTitle(aoData, this)
            },
            fnDrawCallback: function (oSettings) {
                $('.make-switch').bootstrapSwitch();
            }
        });
    });

    $(document).on('click', '.background-add-modal', function (e) {
        e.preventDefault();
        $('#add-background-box form').attr('action', '<?php echo base_url('frames/add_frames'); ?>');
        $('#add-background-box').modal('show');
        $('#add-background-box .modal-title').html('Add Frame');
        $('#frmFramesave')[0].reset();

        var nowDate = new Date();
        var curDatetime = (nowDate.getFullYear() + '-' + ('0' + (nowDate.getMonth() + 1)).slice(-2) + '-' + ('0' + (nowDate.getDate())).slice(-2)) + ' ' + ('0' + (nowDate.getHours())).slice(-2) + ':' + ('0' + (nowDate.getMinutes())).slice(-2) + ':' + ('0' + (nowDate.getSeconds())).slice(-2);
        $('[name="dAddedDate"]').val(curDatetime);
        $('[name="dModifiedDate"]').val('');

        jQuery('.temp_requerd').each(function ()
        {
            jQuery(this).attr('class', 'required');
        })
        $('.MI').html("");
        
        document.getElementById("vFrameImage").className = "required";
        
        $("input[type='text']#vTitle").on('blur', function () {
            var vTitle=$(this).val();
            //alert(vTitle); return false;
            $.ajax({
                type: 'post',
                url: "<?php echo site_url('frames/checkframetitle'); ?>",
                dataType: "JSON",
                data: {vTitle: vTitle},
                success: function (return_data) {
                    
                    if (return_data.type == "1")
                    {
                       
                    } else if(return_data.type == "2") {
                        alert('Frame title already exists.');
                        $('#vTitle').val('');
                        $('#vTitle').focus();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    small_alert("Error", "error", "Internal Error");
                }

            });
        });

    });

    $(document).on('click', '.background-edit-modal', function (e) {
        e.preventDefault();
        var t = $(this);
        $('#add-background-box').modal('show');
        $('#add-background-box .modal-title').html('Edit Frame');
        $('#frmFramesave')[0].reset();
        $('#add-background-box label.error').remove();
        $.getJSON(t.attr('href'), function (r) {
            $('#add-background-box form').attr('action', '<?php echo base_url('frames/edit'); ?>/' + r.frame_id);
            $('[name="vTitle"]').val(r.frame_title);
            
             <?php //$Image = base_url() . '/uploads/frames/' ;?>
               
            var img_url= r.frame_image;
            
            $('.MI').html("<p><img height='80px' width='80px' src='"+img_url+"'></p>");
            document.getElementById("vFrameImage").className = "temp_requerd";
            
            $('[name="vDescription"]').val(r.frame_description);
            $('[name="vPrice"]').val(r.frame_price);
            $('[name="eStatus"]').val(r.status);
            
            var nowDate = new Date();
            var curDatetime = (nowDate.getFullYear() + '-' + ('0' + (nowDate.getMonth() + 1)).slice(-2) + '-' + ('0' + (nowDate.getDate())).slice(-2)) + ' ' + ('0' + (nowDate.getHours())).slice(-2) + ':' + ('0' + (nowDate.getMinutes())).slice(-2) + ':' + ('0' + (nowDate.getSeconds())).slice(-2);
            $('[name="created_date"]').val(r.dAddedDate);
            $('[name="modified_date"]').val(r.dModifiedDate);
        });
    });


    $(document).on('submit', '#frmFramesave', function (e) {
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
                        $('#frmFramesave')[0].reset();
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