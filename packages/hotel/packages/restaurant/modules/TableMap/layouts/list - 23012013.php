<style>
	a.room,a.room:visited{
		height:80px;
		width:80px;
		background:url(packages/hotel/skins/default/images/table.png) no-repeat 0 100%;
	}
	a.room.OCCUPIED,a.room.OCCUPIED:visited{
		height:80px;
		width:80px;
		border:3px solid #FFF;
		border-top:3px solid #FC0;
	}
	a.room.BOOKED,a.room.BOOKED:visited{
		height:80px;
		width:80px;
		background-color:#E6F1FF;
		border:3px solid #FFF;
		border-top:3px solid #09F;
	}
	a.room.AVAILABLE,a.room.AVAILABLE:visited{
		border:3px solid #FFF;
	}
	.room-bound{
		border:1px solid #FFF;
		width:105px;
	}
	.extra-info-bound{
		float:left;
		width:10px;
		height:75px;
		overflow:hidden;
	}
	.small-room{
		float:left;
		width:6px;
	}
	.small-room.checkout{
		background:#FF99CC;
		border:1px solid #FF0099;
		height:8px;
		width:7px;
		margin:1px;
	}  
	.small-room.booked{
		background:#4986E7;
		border:1px solid #CAE4FF;
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
</style>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>
<div id="table_map">
<div style="padding:5px;min-height:750px;">
<div align="right"></div>	
<form name="EditTableMapForm" method="post">
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
    <input value="[[.full_screen.]]" name="full_screen_button" type="button" id="full_screen_button" onclick="switchFullScreen();" class="button-large fullscreen" style="float:right;"/>
    <!--LIST:bars-->
    <div id="content_[[|bars.id|]]" class="content" style="float:left; background:#fff;border:1px solid #808080;position: relative;top: -1px;">
	<table cellpadding="2" cellspacing="0" width="100%" class="table_floor" rules="rows" style="border-color:#f0f0f0;">
		<!--LIST:bars.floors-->		
		<tr>
			<td width="80px" nowrap valign="center" style="margin-left:5px; color:#808080;"><b>[[|bars.floors.name|]]</b></td>   
			<td width="99%"> 
				<!--LIST:bars.floors.bar_tables-->
				<div class="room-bound" id="[[|bars.floors.bar_tables.id|]]">
                <?php $herf = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.''.[[=bars.floors.bar_tables.href=]];
				/*if([[=bars.floors.bar_tables.class=]] == 'AVAILABLE'){
					$herf .= '&cmd=add&arrival_time='.$_REQUEST['in_date'].'';
				}else{
					$herf .= '&cmd=edit&id=';	
				}*/
				?>
				<a href="javascript:void(0)" onclick="window.location='<?php echo $herf;?>&table_id=[[|bars.floors.bar_tables.id|]]&bar_id='+jQuery('#bar_id').val()" class="room [[|bars.floors.bar_tables.class|]]">
					<span style="font-size:12px;font-weight:bold;color:#039;text-transform:uppercase;margin-top:5px;">[[|bars.floors.bar_tables.name|]]</span><br />
					<!--IF:cond([[=bars.floors.bar_tables.arrival_time=]])-->
					<span style="font-size:9px;font-weight:normal;color:#F00">[[|bars.floors.bar_tables.arrival_time|]]</span>-<span style="font-size:9px;font-weight:normal;color:#F00">[[|bars.floors.bar_tables.departure_time|]]</span><br />
                    <!--IF:cond_status([[=bars.floors.bar_tables.status=]]=='OCCUPIED')-->
					<span style="font-size:9px;font-weight:normal;">[[.code.]] <strong>[[|bars.floors.bar_tables.code|]]:</strong> <?php echo System::display_number([[=bars.floors.bar_tables.total=]]);?></span>
                    <!--/IF:cond_status-->
					<!--/IF:cond-->
					<!--IF:cond_([[=bars.floors.bar_tables.agent_name=]])-->
					<br /><span style="font-size:9px;font-weight:normal;">[[|bars.floors.bar_tables.agent_name|]]</span>
					<!--/IF:cond_-->
				</a>
				<div class="extra-info-bound">
				<!--LIST:bars.floors.bar_tables.status_tables_others-->
				<a target="_blank" title="[[.code.]]: [[|bars.floors.bar_tables.status_tables_others.id|]]" href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'edit','id'=>[[=bars.floors.bar_tables.status_tables_others.id=]]));?>" class="small-room <?php echo strtolower([[=bars.floors.bar_tables.status_tables_others.status=]]);?>"></a>
				<!--/LIST:bars.floors.bar_tables.status_tables_others-->
				</div>
				</div>
			<!--/LIST:bars.floors.bar_tables-->
			</td>
		</tr>		
		<!--/LIST:bars.floors-->	
	</table>
    </div>
   <!--/LIST:bars-->
   <input name="bar_id" type="hidden" id="bar_id"/>
</form>	
</div>
</div>
<script>
	setTimeout('location.reload()',120000)
    jQuery(document).ready(function(){ 
           
          // Sự kiện khi nhấn vào các tab của menu 
          jQuery(".tab").click(function () { 
		    // tắt tất cả các tab 
               jQuery(".active").removeClass("active"); 
               
			    // bật tab đang click lên 
               jQuery(this).addClass("active"); 
               jQuery('#bar_id').val(jQuery(this).attr('id'));
			   var id = jQuery(this).attr('id');
               // tạo hiệu ứng trượt lên trên cho nội dung của tab đang click 
               jQuery(".content").fadeOut();
               // Nếu là tab đầu tiên thì set hiệu ứng là trượt xuống dưới 
               var content_show = jQuery(this).attr('lang'); 
			   jQuery.ajax({
				url:"form.php?block_id=<?php echo Module::block_id();?>",
				type:"POST",
				data:{bar_id:id,type:'BAR_ID'},
				success:function(html){
					//alert(html);
					jQuery("#"+content_show).fadeIn();
				}
			});             
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
	jQuery('#in_date').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	/*setTimeout("window.location='<?php echo Url::build_current(array('in_date'));?>'",60000);*/
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