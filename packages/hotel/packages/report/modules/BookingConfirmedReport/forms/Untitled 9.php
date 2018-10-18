<?php
    unset($_GET['bar_reservation_id']); 
?>
<style>
	a.room,a.room:visited{
		height:100px;
		width:100px;
		border:3px solid #999999;
	}
	a.room.OCCUPIED,a.room.OCCUPIED:visited{
		height:100px;
		width:100px;
		border:3px solid #FC0;
	}
    a.room.OVERCHECKIN,a.room.OVERCHECKIN:visited{
		height:100px;
		width:100px;
		border:3px solid #BF4C4F;
	}
	a.room.BOOKED,a.room.BOOKED:visited{
		height:100px;
		width:100px;
		background-color:#E6F1FF;
	
		border:3px solid #09F;
	}
	a.room.AVAILABLE,a.room.AVAILABLE:visited{
	}
	.room-bound{
		width:125px;
		margin:5px;
		border:0px;
	}
	.extra-info-bound{
		float:left;
		width:15px;
		height:75px;
		overflow:hidden;
	}
	.small-room{
		float:left;
		width:6px;
	}
	.small-room.checkout{
		background:#FF99CC;
		border:1px solid #CCCCCC;
		height:8px;
		width:7px;
		margin:1px;
	}  
	.small-room.booked{
		background:#4986E7;
		border:1px solid #CCCCCC;
		height:8px;
		width:7px;
		margin:1px;
	}
	.form-title{
		background:url(packages/hotel/skins/default/images/table_map_icon.png) no-repeat 0px 50%;
	}
	.tabs{
		width:100%;	
		color:#33F;
		position:relative;z-index: 10;
	}
	.tabs li{	
		float:left;
		text-decoration:none;
		list-style:none;
		background:#f0f0f0;
		text-align:center;
		color:#555555;
		padding: 8px 12px;
		font-family:arial;
		font-weight:800;
		cursor:pointer;
		margin-right:3px;
		border-top: 1px solid #808080;
		border-right: 1px solid #808080;
		border-left: 1px solid #808080;
		height:23px;
	}
	.tabs li:hover{
		text-decoration:none;
		background:#fff;
		border-top: 3px solid #4FA2E4;
		color: #4FA2E4;padding-top:6px;
	}
	.tabs .active{
		background:#fff;
		color:#4FA2E4;
		border-bottom:none;
		border-top: 3px solid #4FA2E4;
		padding-top:6px;
	}
	.content{
		width:100%;
		min-height:100px;	
	}
	.room-bound{
		list-style:none;	
	}
    .tabs{
        margin-top: 20px;
    }
    #table_group_ul{
        width: 80px;
    }
    #table_group_ul li{
        list-style: none;
        border-top: 2px solid #555555; 
        border: 1px solid #0000FF;
		margin-bottom:4px;
        background: #f0f0f0;
        height: 40px;
        text-align: center;
        line-height: 40px;
        font-size: 10px;
        color: #555555; 
    }
    #table_group_ul li:hover{
        border-top: 2px solid #4fa2e4; 
        border: 1px solid #4fa2e4; 
        background: #ffffff;    
        color: #4fa2e4;
        cursor: pointer;
    }
    #table_group_ul li.active{
        border-top: 2px solid #4fa2e4; 
        border: 1px solid #4fa2e4; 
        background: #ffffff;    
        color: #4fa2e4;
        cursor: pointer;
    }
</style>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>
<div id="table_map">
<div align="right"><input value="" name="full_screen_button" type="button" id="full_screen_button" onclick="switchFullScreen();" class="button-large fullscreen" style="float:right;"/></div>
<br />	
<div style="padding:5px;min-height:750px;">
<form name="EditTableMapForm" method="post">
    <div style="height: 600px;">
	<!--<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="30%" class="form-title">[[.table_map.]]</td>
			<td width="70%" align="right"><select name="table_group" id="table_group" onchange="ChanngeGroup();"  style="display:none;"></select> &nbsp;[[.date.]]: <input name="in_date" type="text" id="in_date" size="8" onchange="EditTableMapForm.submit();" class="date-input"></td>
        </tr>
    </table><br />-->
    <ul class="tabs"> 
    <!--LIST:list_bar-->   
    <?php if([[=list_bar.id=]]==Session::get('bar_id')){?>
        <li class="tab active" lang="content_[[|list_bar.id|]]" id="[[|list_bar.id|]]">[[|list_bar.name|]]</li>
        <?php }else{?>
        <li class="tab" lang="content_[[|list_bar.id|]]" id="[[|list_bar.id|]]">[[|list_bar.name|]]</li>
        <?php }?>  
    <!--/LIST:list_bar-->     
    </ul>    
    <strong>[[.date.]]: </strong><input name="in_date" type="text" id="in_date" size="8" onchange="EditTableMapForm.submit();">
    <!--LIST:bars-->  
    <!--IF:cond_bar([[=bars.id=]] == Session::get('bar_id'))-->
    <div id="content_[[|bars.id|]]" class="content" style="float:left; background:#fff;border:1px solid #808080;position: relative;top: -1px;">
	<ul id="bound_table_list_[[|bars.id|]]" class="bound_product_list" style="padding-right:90px;min-height:450px;">  
		<!--LIST:bars.floors-->		
				<!--LIST:bars.floors.bar_tables-->
				<li class="room-bound" id="[[|bars.floors.bar_tables.id|]]">
                <?php 
                $herf = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.''.[[=bars.floors.bar_tables.href=]];    
				?>
				<a href="javascript:void(0)" onclick="window.location='<?php echo $herf;?>&table_id=[[|bars.floors.bar_tables.id|]]&bar_area_id=[[|bars.floors.bar_tables.bar_area_id|]]&bar_id='+jQuery('#bar_id').val()" class="room [[|bars.floors.bar_tables.class|]]">
					<span style="font-size:12px;font-weight:bold;color:#039;text-transform:uppercase;margin-top:5px;">[[|bars.floors.bar_tables.name|]]</span><br />
					<!--IF:cond([[=bars.floors.bar_tables.arrival_time=]])-->
					<span style="font-size:11px;font-weight:normal;color:#F00">[[|bars.floors.bar_tables.arrival_time|]]</span>-<span style="font-size:11px;font-weight:normal;color:#F00">[[|bars.floors.bar_tables.departure_time|]]</span><br />
                    <!--IF:cond_status([[=bars.floors.bar_tables.status=]]=='OCCUPIED')-->
					<span style="font-size:11px;font-weight:normal;"><?php echo System::display_number([[=bars.floors.bar_tables.total=]]);?></span>
                    <!--/IF:cond_status-->
                    
                    <!--IF:cond_status([[=bars.floors.bar_tables.status=]]=='OVERCHECKIN')-->
					<span style="font-size:11px;font-weight:normal;"><?php echo System::display_number([[=bars.floors.bar_tables.total=]]);?></span>
                    <!--/IF:cond_status-->
                    
					<!--/IF:cond-->
					<!--IF:cond_([[=bars.floors.bar_tables.agent_name=]])-->
					<br /><span style="font-size:11px;font-weight:normal;">[[|bars.floors.bar_tables.agent_name|]]</span>
					<!--/IF:cond_-->
                                        <!--IF:cond_(isset([[=bars.floors.bar_tables.receiver_name=]]))-->
					<br /><span style="font-size:11px;font-weight:normal;">[[|bars.floors.bar_tables.receiver_name|]]</span>
					<!--/IF:cond_-->
                                        <!--IF:cond_(isset([[=bars.floors.bar_tables.receiver_phone=]]))-->
					<br /><span style="font-size:11px;font-weight:normal; color: #096969" id="receiver_phone">[[|bars.floors.bar_tables.receiver_phone|]]</span>
					<!--/IF:cond_-->
                                        <!--IF:cond_(isset([[=bars.floors.bar_tables.customer_name=]]) && [[=bars.floors.bar_tables.customer_name=]] != [[=bars.floors.bar_tables.agent_name=]])-->
					<br /><span style="font-size:11px;font-weight:normal;">[[|bars.floors.bar_tables.customer_name|]]</span>
					<!--/IF:cond_-->
                    <br /><span style="font-size:11px;font-weight:normal;">[[|bars.floors.bar_tables.room_name|]]</span>
                    <br /><span style="font-size:11px;font-weight:normal;">[[|bars.floors.bar_tables.traveller_name|]]</span>
				</a>
				<div class="extra-info-bound">
				<!--LIST:bars.floors.bar_tables.status_tables_others-->
				<a target="_blank" title="[[.code.]]: [[|bars.floors.bar_tables.status_tables_others.id|]]" href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>[[=bars.floors.bar_tables.status_tables_others.id=]],'bar_area_id'=>[[=bars.floors.bar_tables.status_tables_others.bar_area_id=]],'table_id'=>[[=bars.floors.bar_tables.status_tables_others.table_id=]],'bar_id'));?>" class="small-room <?php echo strtolower([[=bars.floors.bar_tables.status_tables_others.status=]]);?>"></a>
				<!--/LIST:bars.floors.bar_tables.status_tables_others-->    
				</div>
				</li>
			<!--/LIST:bars.floors.bar_tables-->
		<!--/LIST:bars.floors-->	
         </ul>
         <div style="width:70px; float:right; position: absolute; top: 0px; right: 10px;">    
            <ul id="table_group_ul">
                <!--LIST:groups-->
                <?php
                    if(Url::get('package_id'))
                    {
                        ?>
                        <li value="[[|groups.name|]]" id="[[|groups.id|]]" onclick="window.location='?page=table_map&bar_id=<?php echo Session::get('bar_id');?>&in_date=<?php echo Url::get('in_date');?>&group=[[|groups.id|]]&package_id=<?php echo Url::get('package_id');?>&reservation_room_id=<?php echo Url::get('reservation_room_id');?>';">[[|groups.name|]]</li>
                        <?php 
                    } 
                    else
                    {
                        ?>
                        <li value="[[|groups.name|]]" id="[[|groups.id|]]" onclick="window.location='?page=table_map&bar_id=<?php echo Session::get('bar_id');?>&in_date=<?php echo Url::get('in_date');?>&group=[[|groups.id|]]';">[[|groups.name|]]</li>
                        <?php 
                    }
                ?>
                
                <!--/LIST:groups-->
            </ul>  
        </div>
    </div>  
    <!--/IF:cond_bar-->
   <!--/LIST:bars-->
   <input name="bar_id" type="hidden" id="bar_id"/>
   <input  name="page_no" type="hidden" id="page_no"/>
    <input name="name_group" type="hidden" id="name_group"/>
    </div>
   </form>	
</div>
</div>
<script>  
	var no_of_page = <?php echo (Url::get('page_no')?Url::get('page_no'):1);?>;
    //giap.ln comment 12-1-2015 
  /*  var group = jQuery('#name_group').val();
    console.log(group);
    if(group!=''){
        jQuery('#'+group).addClass('active');
    }*/
    //end giapln
	var total_page = [[|total_page|]]; 
    
	if(total_page<=1){
		jQuery('#bound_icon_next').css('display','none');     	
	}
	if(no_of_page==total_page){
		jQuery('#img_next').html('<img src="packages/hotel/skins/default/images/iosstyle/icon_next_mark.jpg" />');
	}
	if(no_of_page==1){
		jQuery('#img_prev').html('<img src="packages/hotel/skins/default/images/iosstyle/icon_prev_mark.jpg" />');	
	}
	setTimeout('location.reload()',120000)
    jQuery(document).ready(function(){ 
          jQuery(".tab").click(function () { 
		  		jQuery('#bar_id').val(jQuery(this).attr('id'));
		  		EditTableMapForm.submit();
			});    
      	jQuery('.room-bound').bind('contextmenu', function(e){
			e.preventDefault();
			window.location='?page=touch_bar_restaurant&cmd=add&arrival_time=<?php echo $_REQUEST['in_date'];?>&bar_id=<?php echo Session::get('bar_id');?>&table_id='+this.id;
		});
       });
	jQuery('.tab').each(function(){
		if(jQuery(this).attr('class')!='tab active'){
			jQuery('#'+jQuery(this).attr('lang')).css('display','none');		
		}
	});
	
	jQuery('#in_date').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	/*setTimeout("window.location='<?php //echo Url::build_current(array('in_date'));?>'",60000);*/
	function ChangeBar(){
		var bar_id = '<?php if(Url::get('bar_id')) {echo Url::get('bar_id');} else {echo '';}?>';	
		EditTableMapForm.submit();
	}
	function ChanngeGroup(){
		var table_group = '<?php if(Url::get('table_group')) {echo Url::get('table_group');} else {echo '';}?>';
		EditTableMapForm.submit();	
		jQuery('#table_group').val(table_group);	
	}
	function FullScreen(){
		jQuery("#table_map").attr('class','full_screen');
		jQuery("#full_screen_button").attr('value','[[.exit_full_screen.]]');
	}
	function switchFullScreen(){
		if(jQuery.cookie('table_fullScreen')==1){
			jQuery("#table_map").attr('class','');
			jQuery("#full_screen_button").attr('value','[[.full_screen.]]');
			jQuery.cookie('table_fullScreen',0);
		}else{
			FullScreen();
			jQuery.cookie('table_fullScreen',1);
		}
	}
	if(jQuery.cookie('table_fullScreen')==1){
		FullScreen();
	}		
</script>
<!--Thanh add phan ket noi socket-->
<?php 
    if(USE_DISPLAY && USE_DISPLAY==1 && isset([[=arr_product_js=]])){
?>
    <script src='http://<?php echo IP_NODEJS_SERVER; ?>:<?php echo PORT_NODEJS_SERVER; ?>/socket.io/socket.io.js'></script> 		  
    <script>
        var arr_eating = [[|arr_product_js|]];
        //console.log(arr_eating);
        var socket = io.connect('http://<?php echo IP_NODEJS_SERVER; ?>:<?php echo PORT_NODEJS_SERVER; ?>'); // Thanh add -- K?t n?i server Websocket
        <?php
            if(isset([[=close=]])){
        ?>
            var move_order = "ok";
        <?php
        }
        ?>
            socket.emit('call_eating',arr_eating);  
    </script>
<?php
    }
?>
<?php
            if(isset([[=close=]])){
        ?>
        <script>
            window.setTimeout(function(){
                parent.close_window_fun();
            },2000);
         </script>    
        <?php        
            }
        ?>   
        
<!--end-->
