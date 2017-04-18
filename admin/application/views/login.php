<!DOCTYPE html>
<html class="">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $page_title; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url().SITE_CSS;?>bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo base_url().SITE_CSS;?>font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url().SITE_CSS;?>AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().SITE_CSS;?>toastr.min.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style>
            .modal{color: #333 !important}
            .lnkModal{cursor: pointer}
        </style>
    </head>
    <body class="">

        <div class="form-box" id="login-box">
            <div class="header">Sign In</div>
            <form action="<?php echo base_url().'login';?>" method="post" id="frmLogin">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" name="email" class="form-control required email" placeholder="Email" value="<?php echo set_value('email'); ?>"/>
                        <?php echo form_error('email'); ?>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control required" placeholder="Password" <?php echo set_value('password'); ?>/>
                        <?php echo form_error('password'); ?>
                    </div>          
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">Sign me in</button>  
                    <!--<p><a class="lnkModal" data-toggle="modal" data-target="#compose-modal"> Reset Password</a></p>-->
                    
                </div>
            </form>

        </div>


        <!-- COMPOSE MESSAGE MODAL -->
        <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Reset Password</h4>
                    </div>
                    <form action="javascript:void(0);" method="post" id="frmFrgtPwd">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Old Password: </label>
                                <input name="oldpassword" type="password" class="form-control required" placeholder="Old Password">
                            </div>
                             <div class="form-group">
                                <label>New Password: </label>
                                <input name="newpassword" type="password" class="form-control required" placeholder="New Password">
                            </div>
                             <div class="form-group">
                                <label>Confirm Password: </label>
                                <input name="confirmpassword" type="password" class="form-control required" placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="modal-footer clearfix">

                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- jQuery 2.0.2 -->
        <script src="<?php echo base_url().SITE_JS;?>jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url().SITE_JS;?>bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url().SITE_JS;?>validator/jquery.validate.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo base_url().SITE_JS;?>toastr.min.js"></script>
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
            //toastr['error']('test', '');
            <?php
            $flsh_msg = $this->session->userdata('flsh_msg');
            if($flsh_msg != ''){
                $flsh_msg_type = $this->session->userdata('flsh_msg_type');
                echo "toastr['$flsh_msg_type']('$flsh_msg');";
                unset_flash_msg();
            }
            ?>
            $(function(){
                $('#frmLogin').validate();
                $('#frmFrgtPwd').validate();
                $('#frmFrgtPwd').submit(function(e){
                    e.preventDefault();
                    var t = $(this);
                    if(t.valid()){
                        $.post('<?php echo base_url('forgot-password');?>', t.serialize(), function(r){
                            if(r == 1)
                                toastr['success']('Password Reset success')
                            else
                                toastr['error']('Unable to reset your password. Please try again later')

                        })
                    }
                })
            });
        </script>
    </body>
</html>
