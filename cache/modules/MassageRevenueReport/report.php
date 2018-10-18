<style>
/*full m�n h�nh*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<?php 
				if(($this->map['page_no']<=1))
				{?>
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="left" width="80%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong><?php echo Portal::language('template_code');?></strong>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('message_revenue_report_spa');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                <?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['from_time'];?>&nbsp;<?php echo $this->map['from_date'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['to_time'];?>&nbsp;<?php echo $this->map['to_date'];?>
                            </span> 
                        </div>
                    </td>
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
.specific {font-size: 19px !important;}
</style>


<!---------SEARCH----------->
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td><?php echo Portal::language('line_per_page');?></td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="<?php echo $this->map['line_per_page'];?>" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td><?php echo Portal::language('no_of_page');?></td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="2" style="text-align:right"/></td>
                                    <!--
                                    <td><?php echo Portal::language('from_page');?></td>
                                	<td><input name="start_page" type="text" id="start_page" value="<?php echo $this->map['start_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                    -->
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td><?php echo Portal::language('hotel');?></td>
                                	<td><select  name="portal_id" id="portal_id"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    <?php }?>
                                    <td><?php echo Portal::language('room');?></td>
                                	<td><select  name="room_id" id="room_id"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><?php echo Portal::language('from');?></td>
                                	<td><input  name="from_date" id="from_date" style="width: 80px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                    <td><?php echo Portal::language('to');?></td>
                                	<td><input  name="to_date" id="to_date" style="width: 80px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                                    <td><?php echo Portal::language('from_time');?></td>
                                    <td><input  name="from_time" id="from_time" style="width: 50px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_time'));?>"></td>
                                    <td><?php echo Portal::language('to_time');?></td>
                                    <td><input  name="to_time" id="to_time" style="width: 50px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_time'));?>"></td>
                                    <td><input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/></td>
                                </tr>
                            </table>
                        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>

<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>
<script type="text/javascript">
    var from_hours = '<?php echo $this->map['from_time'];?>';
    var to_hours = '<?php echo $this->map['to_time'];?>';
    jQuery('#from_time').val(from_hours);
    jQuery('#to_time').val(to_hours);
jQuery(document).ready(
    function()
    {
        jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
    	jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
        jQuery('#from_time').mask("99:99");
        jQuery('#to_time').mask("99:99");
    }
);
</script>

				<?php
				}
				?>



<!---------REPORT----------->	
<?php 
				if((!isset($this->map['has_no_data'])))
				{?>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="50px"><?php echo Portal::language('stt');?></th>
        <th class="report_table_header" width="50px"><?php echo Portal::language('code');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('date');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('total_amount');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('tip_amount');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('guest_used');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('user');?></th>
        <th class="report_table_header" width="200px"><?php echo Portal::language('note');?></th>
    </tr>
    
    
    <?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td align="right" colspan="3" class="report_sub_title"><b><?php echo Portal::language('last_page_summary');?></b></td>
		<td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['last_group_function_params']['total_amount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['last_group_function_params']['total_tip']);?></strong></td>
        <td colspan="3" class="report_sub_title" align="right">&nbsp;</td>
	</tr>
	
				<?php
				}
				?>
    
    <?php 
	$i=0;
	$total_amount = 0;
	?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr bgcolor="white">
        <td align="center" class="report_table_column"><?php echo $this->map['items']['current']['stt'];?></td>
        <td align="center" class="report_table_column">
            <a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>$this->map['items']['current']['id']));?>" target="_blank"><?php echo $this->map['items']['current']['id'];?></a>
        </td>
        <td align="center" class="report_table_column"><?php echo $this->map['items']['current']['time'];?></td>
        <td align="right" class="report_table_column"><?php echo $this->map['items']['current']['total_amount'];?></td>
        <td align="right" class="report_table_column"><?php echo $this->map['items']['current']['tip_amount'];?></td>
        <td align="center" class="report_table_column"><?php echo $this->map['items']['current']['full_name'];?></td>
        <td align="center" class="report_table_column"><?php echo $this->map['items']['current']['user_id'];?></td>
        <td align="center" class="report_table_column"><?php echo $this->map['items']['current']['note'];?></td>
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
    <tr>
        <td align="right" colspan="3" class="report_sub_title" ><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>        
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['total_amount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['total_tip']);?></strong></td>
        <td colspan="3" class="report_sub_title" align="right">&nbsp;</td>
    </tr>
</table>


<!---------FOOTER----------->
<center><div style="font-size:11px;">
  <?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></div>
</center>
<br/>

<?php 
				if((($this->map['page_no']==$this->map['total_page'])))
				{?>
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td ></td>
	<td align="center" > <?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >&nbsp;</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center"><?php echo Portal::language('creator');?></td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>

				<?php
				}
				?>
<!---<div style="page-break-before:always;page-break-after:always;"></div>--->

 <?php }else{ ?>
<strong><?php echo Portal::language('no_data');?></strong>

				<?php
				}
				?>
