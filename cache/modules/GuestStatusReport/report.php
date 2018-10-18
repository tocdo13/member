<!---------HEADER----------->
<?php 
				if(($this->map['page_no']==$this->map['start_page'] or $this->map['page_no'] == 0 ))
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
                        <strong><?php echo Portal::language('housekeeping');?></strong> 
                        
                        <br />
                        <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('guest_status_report');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                <?php echo Portal::language('date');?>&nbsp;<?php echo $this->map['date'];?>
                            </span> 
                        </div>
                    </td>
                    
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>


<!---------SEARCH----------->
<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td><?php echo Portal::language('line_per_page');?></td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="<?php echo $this->map['line_per_page'];?>" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td><?php echo Portal::language('no_of_page');?></td>
                                    <td><input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td><?php echo Portal::language('from_page');?></td>
                                    <td><input name="start_page" type="text" id="start_page" value="<?php echo $this->map['start_page'];?>" size="4" maxlength="4" style="text-align:right"/></td>
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
                                    <td><?php echo Portal::language('date');?></td>
                                	<td><input  name="date" id="date" style="width: 80px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>"></td>
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
jQuery(document).ready(
    function()
    {
        jQuery("#date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>,1 - 1, 1)});
    }
);
</script>

				<?php
				}
				?>



<?php 
				if((!isset($this->map['has_no_data'])))
				{?>

<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="60px" class="report_table_header"><?php echo Portal::language('room');?></th>
		<th width="100px" class="report_table_header"><?php echo Portal::language('room_level');?></th>
		<th width="80px" class="report_table_header"><?php echo Portal::language('status');?></th>
		<th width="150px" class="report_table_header"><?php echo Portal::language('guest_name');?></th>
		<th width="60px" class="report_table_header"><?php echo Portal::language('gender');?></th>
		<th align="center" width="100px" class="report_table_header"><?php echo Portal::language('country');?></th>
		<th width="100px" class="report_table_header"><?php echo Portal::language('arrival_time');?></th>
		<th width="100px" class="report_table_header"><?php echo Portal::language('departure_time');?></th>
        <th width="100px" class="report_table_header"><?php echo Portal::language('note1');?></th>
	</tr>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
	<tr bgcolor="white">
		<td align="center"  ><?php echo $this->map['items']['current']['room_name'];?></td>
		<td align="center" ><?php echo $this->map['items']['current']['room_level_name'];?></td>
		<td align="center" ><?php echo $this->map['items']['current']['status'];?></td>
        <td align="left"><?php echo $this->map['items']['current']['fullname'];?></td>	
	    <td align="center"><?php echo $this->map['items']['current']['gender'];?></td>
		<td align="center"><?php echo $this->map['items']['current']['country_name'];?></td>
        <td align="center" ><?php echo $this->map['items']['current']['time_in']?date('h\h:i - d/m/y',$this->map['items']['current']['time_in']):'';?></td>
		<td align="center" ><?php echo  $this->map['items']['current']['time_out']?date('h\h:i - d/m/y',$this->map['items']['current']['time_out']):'';?></td>
        <td align="center" ><?php echo  $this->map['items']['current']['change_room_from_rr']?'Đổi từ phòng '.$this->map['items']['current']['change_room_from_rr']:'';?></td>        
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
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