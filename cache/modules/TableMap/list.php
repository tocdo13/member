<?php
    unset($_GET['bar_reservation_id']); 
?>
<style>
	a.room,a.room:visited{
		height:90px;
		width:90px;
        padding: 5px;
		border:3px solid #999999;
	}
	a.room.OCCUPIED,a.room.OCCUPIED:visited{
		height:90px;
		width:90px;
        padding: 5px;
		border:3px solid #FC0;
	}
    a.room.OVERCHECKIN,a.room.OVERCHECKIN:visited{
		height:90px;
		width:90px;
        padding: 5px;
		border:3px solid #BF4C4F;
	}
	a.room.BOOKED,a.room.BOOKED:visited{
		height:90px;
		width:90px;
		background-color:#E6F1FF;
	    padding: 5px;
		border:3px solid #09F;
	}
	a.room.AVAILABLE,a.room.AVAILABLE:visited{
	}
	.room-bound
    {
		width:125px;
		margin:10px;
		border:0px;
        position: relative;
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
		height:40px;
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
    .flatbookedtable {
        width: 20px; height: 20px; border: 2px solid #11403b; border-radius: 50%; background: #FFFFFF; box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .05), 0 1px 4px 0 rgba(0, 0, 0, .08), 0 3px 1px -2px rgba(0, 0, 0, .2); 
        position: absolute; 
        z-index: 99; 
        top: -10px; left: -10px;
        transition: all 0.3s ease-in-out;
        -ms-transform: scale(1,1); /* IE 9 */
        -webkit-transform: scale(1,1); /* Safari */
        transform: scale(1,1);
    }
    .flatbookedtable:hover {
        -ms-transform: scale(1.5,1.5); /* IE 9 */
        -webkit-transform: scale(1.5,1.5); /* Safari */
        transform: scale(1.5,1.5);
    }
</style>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>
<div class="w3-container">
    <table cellpadding="5" cellspacing="5">
        <tr>
            <td style="width: 20px;"><a onclick="return false;" class="room AVAILABLE" style="width: 20px; height: 20px;"></a></td>
            <td><label style="padding-right: 20px;"><?php echo Portal::language('available_table');?> <span style="font-weight: bold; color: blue;">( <?php echo $this->map['available'];?> )</span></label></td>
            <td style="width: 20px;"><a onclick="return false;" class="room BOOKED" style="width: 20px; height: 20px;"></a></td>
            <td><label style="padding-right: 20px;"><?php echo Portal::language('booled_table');?>  <span style="font-weight: bold; color: blue;">( <?php echo $this->map['booked'];?> )</span></label></td>
            <td style="width: 20px;"><a onclick="return false;" class="room OCCUPIED" style="width: 20px; height: 20px;"></a></td>
            <td><label style="padding-right: 20px;"><?php echo Portal::language('occupied_table');?>  <span style="font-weight: bold; color: blue;">( <?php echo $this->map['checkin'];?> )</span></label></td>
            <td style="width: 20px;"><a onclick="return false;" class="room OVERCHECKIN" style="width: 20px; height: 20px;"></a></td>
            <td><label style="padding-right: 20px;"><?php echo Portal::language('overcheckin_table');?>  <span style="font-weight: bold; color: blue;">( <?php echo $this->map['over_checkin'];?> )</span></label></td>
            <td>
                <div style="width: 20px; height: 20px; border: 2px solid #11403b; border-radius: 50%; background: #FFFFFF; box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .05), 0 1px 4px 0 rgba(0, 0, 0, .08), 0 3px 1px -2px rgba(0, 0, 0, .2);">
                    <a onclick="return false;">
                        <img src="packages/hotel/packages/restaurant/modules/TableMap/bar.png" style="width: 15px; margin: 2.5px;" />
                    </a>
                </div>
            </td>
            <td><label><?php echo Portal::language('booked_table_flat');?></label></td>
        </tr>
    </table>
</div>

<form name="EditTableMapForm" method="post">
    
    <div class="w3-container">
        <div class="w3-row">
            <table cellpadding="5" cellspacing="5">
                <tr>
                    <td><label for="in_date"><?php echo Portal::language('date');?>:</label></td>
                    <td><input  name="in_date" id="in_date" class="w3-input w3-border" style="width: 150px; text-align: center;" readonly="" onchange="EditTableMapForm.submit();"/ type ="text" value="<?php echo String::html_normalize(URL::get('in_date'));?>"></td>
                </tr>
            </table>
        </div>
        <div class="w3-row">
            <ul class="tabs"> 
                <?php if(isset($this->map['list_bar']) and is_array($this->map['list_bar'])){ foreach($this->map['list_bar'] as $key1=>&$item1){if($key1!='current'){$this->map['list_bar']['current'] = &$item1;?>   
                    <?php 
				if(($this->map['list_bar']['current']['id']==Session::get('bar_id')))
				{?>
                    <li class="tab active" lang="content_<?php echo $this->map['list_bar']['current']['id'];?>" id="<?php echo $this->map['list_bar']['current']['id'];?>"><?php echo $this->map['list_bar']['current']['name'];?></li>
                     <?php }else{ ?>
                    <li class="tab" lang="content_<?php echo $this->map['list_bar']['current']['id'];?>" id="<?php echo $this->map['list_bar']['current']['id'];?>"><?php echo $this->map['list_bar']['current']['name'];?></li>
                    
				<?php
				}
				?>
                <?php }}unset($this->map['list_bar']['current']);} ?>     
            </ul> 
        </div>
        <?php if(isset($this->map['bars']) and is_array($this->map['bars'])){ foreach($this->map['bars'] as $key2=>&$item2){if($key2!='current'){$this->map['bars']['current'] = &$item2;?>  
            <?php 
				if(($this->map['bars']['current']['id'] == Session::get('bar_id')))
				{?>
                <div id="content_<?php echo $this->map['bars']['current']['id'];?>" class="content" style="float:left; background:#fff;border:1px solid #808080; position: relative;top: -1px; padding: 10px;">
                	<ul id="bound_table_list_<?php echo $this->map['bars']['current']['id'];?>" class="bound_product_list" style="padding-right:90px;min-height:450px;">  
            		  <?php if(isset($this->map['bars']['current']['floors']) and is_array($this->map['bars']['current']['floors'])){ foreach($this->map['bars']['current']['floors'] as $key3=>&$item3){if($key3!='current'){$this->map['bars']['current']['floors']['current'] = &$item3;?>		
            				<?php if(isset($this->map['bars']['current']['floors']['current']['bar_tables']) and is_array($this->map['bars']['current']['floors']['current']['bar_tables'])){ foreach($this->map['bars']['current']['floors']['current']['bar_tables'] as $key4=>&$item4){if($key4!='current'){$this->map['bars']['current']['floors']['current']['bar_tables']['current'] = &$item4;?>
                				<li class="room-bound" id="<?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['id'];?>">
                                    <?php if($this->map['bars']['current']['floors']['current']['bar_tables']['current']['class']!='AVAILABLE'){ ?>
                                    <div class="flatbookedtable">
                                        <?php $herf_flat = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.''.$this->map['bars']['current']['floors']['current']['bar_tables']['current']['href_flat'];  ?>
                                        <a href="javascript:void(0)" onclick="window.location='<?php echo $herf_flat;?>&table_id=<?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['id'];?>&bar_area_id=<?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['bar_area_id'];?>&bar_id='+jQuery('#bar_id').val()">
                                            <img src="packages/hotel/packages/restaurant/modules/TableMap/bar.png" style="width: 15px; margin: 2.5px;" />
                                        </a>
                                    </div>
                                    <?php } ?>
                                    <?php $herf = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.''.$this->map['bars']['current']['floors']['current']['bar_tables']['current']['href'];  ?>
                    				<a href="javascript:void(0)" onclick="window.location='<?php echo $herf;?>&table_id=<?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['id'];?>&bar_area_id=<?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['bar_area_id'];?>&bar_id='+jQuery('#bar_id').val()" class="room <?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['class'];?>">
                    					<span style="font-size:12px;font-weight:bold;color:#039;text-transform:uppercase;margin-top:5px;"><?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['name'];?></span><br />
                    					<?php 
				if(($this->map['bars']['current']['floors']['current']['bar_tables']['current']['arrival_time']))
				{?>
                    					<span style="font-size:11px;font-weight:normal;color:#F00"><?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['arrival_time'];?></span>-<span style="font-size:11px;font-weight:normal;color:#F00"><?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['departure_time'];?></span><br />
                                        <?php 
				if(($this->map['bars']['current']['floors']['current']['bar_tables']['current']['status']=='OCCUPIED'))
				{?>
                    					<span style="font-size:11px;font-weight:normal;"><?php echo System::display_number($this->map['bars']['current']['floors']['current']['bar_tables']['current']['total']);?></span>
                                        
				<?php
				}
				?>
                                        
                                        <?php 
				if(($this->map['bars']['current']['floors']['current']['bar_tables']['current']['status']=='OVERCHECKIN'))
				{?>
                    					<span style="font-size:11px;font-weight:normal;"><?php echo System::display_number($this->map['bars']['current']['floors']['current']['bar_tables']['current']['total']);?></span>
                                        
				<?php
				}
				?>
                                        
                    					
				<?php
				}
				?>
                    					<?php 
				if(($this->map['bars']['current']['floors']['current']['bar_tables']['current']['agent_name']))
				{?>
                    					<br /><span style="font-size:11px;font-weight:normal;"><?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['agent_name'];?></span>
                    					
				<?php
				}
				?>
                                                            <?php 
				if((isset($this->map['bars']['current']['floors']['current']['bar_tables']['current']['receiver_name'])))
				{?>
                    					<br /><span style="font-size:11px;font-weight:normal;"><?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['receiver_name'];?></span>
                    					
				<?php
				}
				?>
                                                            <?php 
				if((isset($this->map['bars']['current']['floors']['current']['bar_tables']['current']['receiver_phone'])))
				{?>
                    					<br /><span style="font-size:11px;font-weight:normal; color: #096969" id="receiver_phone"><?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['receiver_phone'];?></span>
                    					
				<?php
				}
				?>
                                                            <?php 
				if((isset($this->map['bars']['current']['floors']['current']['bar_tables']['current']['customer_name']) && $this->map['bars']['current']['floors']['current']['bar_tables']['current']['customer_name'] != $this->map['bars']['current']['floors']['current']['bar_tables']['current']['agent_name']))
				{?>
                    					<br /><span style="font-size:11px;font-weight:normal;"><?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['customer_name'];?></span>
                    					
				<?php
				}
				?>
                                        <br /><span style="font-size:11px;font-weight:normal;"><?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['room_name'];?></span>
                                        <br /><span style="font-size:11px;font-weight:normal;"><?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['traveller_name'];?></span>
                    				</a>
                    				<div class="extra-info-bound">
                        				<?php if(isset($this->map['bars']['current']['floors']['current']['bar_tables']['current']['status_tables_others']) and is_array($this->map['bars']['current']['floors']['current']['bar_tables']['current']['status_tables_others'])){ foreach($this->map['bars']['current']['floors']['current']['bar_tables']['current']['status_tables_others'] as $key5=>&$item5){if($key5!='current'){$this->map['bars']['current']['floors']['current']['bar_tables']['current']['status_tables_others']['current'] = &$item5;?>
                        				<a target="_blank" title="<?php echo Portal::language('code');?>: <?php echo $this->map['bars']['current']['floors']['current']['bar_tables']['current']['status_tables_others']['current']['id'];?>" href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>$this->map['bars']['current']['floors']['current']['bar_tables']['current']['status_tables_others']['current']['id'],'bar_area_id'=>$this->map['bars']['current']['floors']['current']['bar_tables']['current']['status_tables_others']['current']['bar_area_id'],'table_id'=>$this->map['bars']['current']['floors']['current']['bar_tables']['current']['status_tables_others']['current']['table_id'],'bar_id'));?>" class="small-room <?php echo strtolower($this->map['bars']['current']['floors']['current']['bar_tables']['current']['status_tables_others']['current']['status']);?>"></a>
                        				<?php }}unset($this->map['bars']['current']['floors']['current']['bar_tables']['current']['status_tables_others']['current']);} ?>    
                    				</div>
                				</li>
                			<?php }}unset($this->map['bars']['current']['floors']['current']['bar_tables']['current']);} ?>
                		<?php }}unset($this->map['bars']['current']['floors']['current']);} ?>	
                     </ul>
                     <div style="width:70px; float:right; position: absolute; top: 0px; right: 10px;">    
                        <ul id="table_group_ul">
                            <?php if(isset($this->map['groups']) and is_array($this->map['groups'])){ foreach($this->map['groups'] as $key6=>&$item6){if($key6!='current'){$this->map['groups']['current'] = &$item6;?>
                            <?php if(Url::get('package_id')){ ?>
                            <li value="<?php echo $this->map['groups']['current']['name'];?>" id="<?php echo $this->map['groups']['current']['id'];?>" onclick="window.location='?page=table_map&bar_id=<?php echo Session::get('bar_id');?>&in_date=<?php echo Url::get('in_date');?>&group=<?php echo $this->map['groups']['current']['id'];?>&package_id=<?php echo Url::get('package_id');?>&reservation_room_id=<?php echo Url::get('reservation_room_id');?>';"><?php echo $this->map['groups']['current']['name'];?></li>
                            <?php  }else{ ?>
                            <li value="<?php echo $this->map['groups']['current']['name'];?>" id="<?php echo $this->map['groups']['current']['id'];?>" onclick="window.location='?page=table_map&bar_id=<?php echo Session::get('bar_id');?>&in_date=<?php echo Url::get('in_date');?>&group=<?php echo $this->map['groups']['current']['id'];?>';"><?php echo $this->map['groups']['current']['name'];?></li>
                            <?php } ?>
                            <?php }}unset($this->map['groups']['current']);} ?>
                        </ul>  
                    </div>
                </div>  
            
				<?php
				}
				?>
       <?php }}unset($this->map['bars']['current']);} ?>
       <input  name="bar_id" id="bar_id"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('bar_id'));?>"><input  name="page_no" type="hidden" id="page_no"/><input  name="name_group" id="name_group"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('name_group'));?>">
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
<script>  
	var no_of_page = <?php echo (Url::get('page_no')?Url::get('page_no'):1);?>;
    //giap.ln comment 12-1-2015 
  /*  var group = jQuery('#name_group').val();
    console.log(group);
    if(group!=''){
        jQuery('#'+group).addClass('active');
    }*/
    //end giapln
	var total_page = <?php echo $this->map['total_page'];?>; 
    
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
		jQuery("#full_screen_button").attr('value','<?php echo Portal::language('exit_full_screen');?>');
	}
	function switchFullScreen(){
		if(jQuery.cookie('table_fullScreen')==1){
			jQuery("#table_map").attr('class','');
			jQuery("#full_screen_button").attr('value','<?php echo Portal::language('full_screen');?>');
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
    if(USE_DISPLAY && USE_DISPLAY==1 && isset($this->map['arr_product_js'])){
?>
    <script src='http://<?php echo IP_NODEJS_SERVER; ?>:<?php echo PORT_NODEJS_SERVER; ?>/socket.io/socket.io.js'></script> 		  
    <script>
        var arr_eating = <?php echo $this->map['arr_product_js'];?>;
        //console.log(arr_eating);
        var socket = io.connect('http://<?php echo IP_NODEJS_SERVER; ?>:<?php echo PORT_NODEJS_SERVER; ?>'); // Thanh add -- K?t n?i server Websocket
        <?php
            if(isset($this->map['close'])){
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
            if(isset($this->map['close'])){
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
