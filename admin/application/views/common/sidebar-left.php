<?php
    //$admin_menus = get_lft_sidebar(); 
    $admin_menus[] = array('id'=>'1','parent_id'=>'0','name'=>'Manage Users','icon'=>'glyphicon glyphicon-user','link'=>'users','orders'=>'1','submenu'=>array('1'=>array('name'=>'All Users','link'=>'users','icon'=>'fa fa-lock')));
    $admin_menus[] = array('id'=>'2','parent_id'=>'0','name'=>'View Transaction','icon'=>'glyphicon glyphicon-usd','link'=>'payment','orders'=>'2','submenu'=>array('1'=>array('name'=>'All Transactions','link'=>'payment','icon'=>'fa fa-lock')));
    $admin_menus[] = array('id'=>'3','parent_id'=>'0','name'=>'Manage Frames','icon'=>'glyphicon glyphicon-picture','link'=>'frames','orders'=>'3','submenu'=>array());
    $admin_menus[] = array('id'=>'4','parent_id'=>'0','name'=>'Manage Tax','icon'=>'glyphicon glyphicon-euro','link'=>'tax','orders'=>'4','submenu'=>array());
    $current_page = str_replace('/', '-', $this->uri->uri_string());
//    echo "<pre>";print_r($admin_menus);die;
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <!--<li class="<?php echo $current_page == 'home' ? 'active' : '' ;?>">
                <a href="<?php echo base_url() . 'home'; ?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>-->
            <?php foreach ($admin_menus as $v) { 
                $cls = '';
		$id_of= str_replace('/', '-', $v['link']);
            ?>
            <li class="<?php echo !empty($v['submenu']) ? 'treeview' : ''; ?>" id="<?php echo $id_of; ?>">
                    <a href="<?php echo $v['link'] == '' ? 'javascript:void(0);' : base_url() . $v['link']; ?>">
                        <i class="<?php echo $v['icon']; ?>"></i> <span><?php echo $v['name']; ?></span> <?php echo!empty($v['submenu']) ? '<i class="fa pull-right fa fa-angle-left"></i>' : ''; ?>
                    </a>
                    <?php
                    if (!empty($v['submenu'])) { ?>
                        <ul class="treeview-menu"  id="<?php if(($current_page == 'users-subscription_history' || $current_page == 'categories') ){ echo $id_of;  } ?> ">
                            <?php
                            foreach ($v['submenu'] as $s) {
				$id_of= str_replace('/', '-', $s['link']);
                            ?>
                            <li id="<?php echo $id_of; ?>" ><a href="<?php echo base_url() . $s['link']; ?>"><i class="fa fa-angle-double-right"></i><?php echo $s['name']; ?></a></li>
                        <?php } ?>
                        </ul>
                <?php } ?>
                </li>                            
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
<script type="text/javascript">
    $(function(){
        $('#<?php echo $current_page; ?>').addClass('active');
        var parent_li = $('#<?php echo $current_page; ?>').parents('.treeview');
        parent_li.addClass('active');        
        var down_arrow = parent_li.find('.fa-angle-left');
        down_arrow.removeClass('fa-angle-left');
        down_arrow.addClass('fa-angle-down');
    });
</script>
