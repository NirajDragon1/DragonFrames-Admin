<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php
        if(isset($page_title) && !empty($page_title)){
            echo $page_title;
        }else if(isset($this->data['page_title']) && !empty($this->data['page_title'])){
            echo $this->data['page_title'];
        }else{
            echo 'CarePays';
        }
         ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url().SITE_CSS;?>bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().SITE_CSS;?>font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().SITE_CSS;?>ionicons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().SITE_CSS;?>morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().SITE_CSS;?>jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <?php /*<link href="<?php echo base_url().SITE_CSS;?>fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />*/ ?>
        <link href="<?php echo base_url().SITE_CSS;?>daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().SITE_CSS;?>bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().SITE_CSS;?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo base_url().SITE_CSS;?>AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().SITE_CSS;?>bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(SITE_CSS.'toastr.min.css');?>" rel="stylesheet" type="text/css" />

        <script src="<?php echo base_url().SITE_JS;?>jquery.min.js"></script>
        <script src="<?php echo base_url().SITE_JS;?>responsive-tabs-2.3.2.js"></script>
        
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
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <?php echo SITE_NM;?>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="<?php echo base_url('logout');?>" >
                                <i class="fa fa-sign-out"></i>
                                <span>Logout</span>
                            </a>
                            
                        </li>
                          <li class="dropdown user user-menu">
                            <a class="lnkModal" data-toggle="modal" data-target="#compose-modal"> Change Password</a>
                            
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- COMPOSE MESSAGE MODAL -->
        <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Change Password</h4>
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
                            <button type="button" id="fancyClos" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <script src="<?php echo base_url().SITE_JS;?>jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url().SITE_JS;?>bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url().SITE_JS;?>validator/jquery.validate.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo base_url().SITE_JS;?>toastr.min.js"></script>
       
        <script type="text/javascript">
            $(function(){
                $('#frmLogin').validate();
                $('#frmFrgtPwd').validate();
                $('#frmFrgtPwd').submit(function(e){
                    e.preventDefault();
                    var t = $(this);
                    if(t.valid()){
                        $.post('<?php echo base_url('forgot-password');?>', t.serialize(), function(r){
                            if(r == 1){
                                toastr['success']('Password Reset successfully');
                                $('#fancyClos').trigger('click');
                                
                            } else {
                                toastr['error']('Unable to reset your password. Please try again later')
                            }
                        })
                    }
                })
            });
            </script>
        
        <div class="wrapper row-offcanvas row-offcanvas-left">
            
