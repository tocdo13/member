<style>
	a.room,a.room:visited{
		height:80px;
		width:100px;
		border:3px solid #999999;
	}
	a.room.OCCUPIED,a.room.OCCUPIED:visited{
		height:80px;
		width:100px;
		border:3px solid #FC0;
	}
	a.room.BOOKED,a.room.BOOKED:visited{
		height:80px;
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
    <!--LIST:list_karaoke-->   
    <?php if([[=list_karaoke.id=]]==Session::get('karaoke_id')){?>
        <li class="tab active" lang="content_[[|list_karaoke.id|]]" id="[[|list_karaoke.id|]]">[[|list_karaoke.name|]]</li>
        <?php }else{?>
        <li class="tab" lang="content_[[|list_karaoke.id|]]" id="[[|list_karaoke.id|]]">[[|list_karaoke.name|]]</li>
        <?php }?>  
    <!--/LIST:list_karaoke-->     
    </ul>    
    <strong>[[.date.]]: </strong><input name="in_date" type="text" id="in_date" size="8" onchange="EditTableMapForm.submit();">
    <!--LIST:karaokes-->  
    <!--IF:cond_karaoke([[=karaokes.id=]] == Session::get('karaoke_id'))-->
    <div id="content_[[|karaokes.id|]]" class="content" style="float:left; background:#fff;border:1px solid #808080;position: relative;top: -1px;">
	<ul id="bound_table_list_[[|karaokes.id|]]" class="bound_product_list" style="padding-right:90px;min-height:450px;">  
		<!--LIST:karaokes.floors-->		
				<!--LIST:karaokes.floors.karaoke_tables-->
				<li class="room-bound" id="[[|karaokes.floors.karaoke_tables.id|]]">
                <?php $herf = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.''.[[=karaokes.floors.karaoke_tables.href=]];
				/*if([[=karaokes.floors.karaoke_tables.class=]] == 'AVAILABLE'){
					$herf .= '&cmd=add&arrival_time='.$_REQUEST['in_date'].'';
				}else{
					$herf .= '&cmd=edit&id=';	
				}*/
				?>
				<a href="javascript:void(0)" onclick="window.location='<?php echo $herf;?>&table_id=[[|karaokes.floors.karaoke_tables.id|]]&karaoke_id='+jQuery('#karaoke_id').val()" class="room [[|karaokes.floors.karaoke_tables.class|]]">
					<span style="font-size:12px;font-weight:bold;color:#039;text-transform:uppercase;margin-top:5px;">[[|karaokes.floors.karaoke_tables.name|]]</span><br />
					<!--IF:cond([[=karaokes.floors.karaoke_tables.arrival_time=]])-->
					<span style="font-size:11px;font-weight:normal;color:#F00">[[|karaokes.floors.karaoke_tables.arrival_time|]]</span>-<span style="font-size:11px;font-weight:normal;color:#F00">[[|karaokes.floors.karaoke_tables.departure_time|]]</span><br />
                    <!--IF:cond_status([[=karaokes.floors.karaoke_tables.status=]]=='OCCUPIED')-->
					<span style="font-size:11px;font-weight:normal;"><?php echo System::display_number([[=karaokes.floors.karaoke_tables.total=]]);?></span>
                    <!--/IF:cond_status-->
					<!--/IF:cond-->
					<!--IF:cond_([[=karaokes.floors.karaoke_tables.agent_name=]])-->
					<br /><span style="font-size:11px;font-weight:normal;">[[|karaokes.floors.karaoke_tables.agent_name|]]</span>
					<!--/IF:cond_-->
				</a>
				<div class="extra-info-bound">
				<!--LIST:karaokes.floors.karaoke_tables.status_tables_others-->
				<a target="_blank" title="[[.code.]]: [[|karaokes.floors.karaoke_tables.status_tables_others.id|]]" href="<?php echo Url::build('karaoke_touch',array('cmd'=>'edit','id'=>[[=karaokes.floors.karaoke_tables.status_tables_others.id=]],'karaoke_id'));?>" class="small-room <?php echo strtolower([[=karaokes.floors.karaoke_tables.status_tables_others.status=]]);?>"></a>
				<!--/LIST:karaokes.floors.karaoke_tables.status_tables_others-->    
				</div>
				</li>
			<!--/LIST:karaokes.floors.karaoke_tables-->
		<!--/LIST:karaokes.floors-->	
         </ul>
         <div style="width:70px; float:right; position: absolute; top: 0px; right: 10px;">    
            <ul id="table_group_ul">
                <!--LIST:groups-->
                <li value="[[|groups.name|]]" id="[[|groups.id|]]" onclick="window.location='?page=karaoke_table_map&karaoke_id=<?php echo Url::get('karaoke_id');?>&in_date=<?php echo Url::get('in_date');?>&group=[[|groups.id|]]';">[[|groups.name|]]</li>
                <!--/LIST:groups-->
            </ul>  
        </div>
    </div>  
    <!--/IF:cond_karaoke-->
   <!--/LIST:karaokes-->
   <input name="karaoke_id" type="hidden" id="karaoke_id"/>
   <input  name="page_no" type="hidden" id="page_no"/>
    <input name="name_group" type="hidden" id="name_group"/>
    </div>
   </form>	
</div>
</div>
<script>  
	var no_of_page = <?php echo (Url::get('page_no')?Url::get('page_no'):1);?>; 
    var group = jQuery('#name_group').val();
    if(group!=''){
        jQuery('#'+group).addClass('active');
    }
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
		  		jQuery('#karaoke_id').val(jQuery(this).attr('id'));
		  		EditTableMapForm.submit();
			});    
      	jQuery('.room-bound').bind('contextmenu', function(e){
			e.preventDefault();
			window.location='?page=karaoke_touch&cmd=add&arrival_time=<?php echo $_REQUEST['in_date'];?>&karaoke_id=<?php echo Session::get('karaoke_id');?>&table_id='+this.id;
		});
       });
	jQuery('.tab').each(function(){
		if(jQuery(this).attr('class')!='tab active'){
			jQuery('#'+jQuery(this).attr('lang')).css('display','none');		
		}
	});
	
	jQuery('#in_date').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	/*setTimeout("window.location='<?php echo Url::build_current(array('in_date'));?>'",60000);*/
	function ChangeKaraoke(){
		var karaoke_id = '<?php if(Url::get('karaoke_id')) {echo Url::get('karaoke_id');} else {echo '';}?>';	
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