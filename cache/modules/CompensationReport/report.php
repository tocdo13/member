<style>
/*full màn hình*/
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
                        <br />
                        <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('compensation_report');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                <?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['from_date'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['to_date'];?>
                            </span> 
                        </div>
                    </td>
                    
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>
<form  method="post"><div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="<?php echo Portal::language('export');?>" style="width:50px" /></div><input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
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
                                    <td><?php echo Portal::language('room_status');?></td>
                                	<td><select  name="room_status" id="room_status"><?php
					if(isset($this->map['room_status_list']))
					{
						foreach($this->map['room_status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_status',isset($this->map['room_status'])?$this->map['room_status']:''))
                    echo "<script>$('room_status').value = \"".addslashes(URL::get('room_status',isset($this->map['room_status'])?$this->map['room_status']:''))."\";</script>";
                    ?>
	</select></td>
                                    <td><?php echo Portal::language('from');?></td>
                                	<td><input  name="from_date" id="from_date" style="width: 80px;" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                    <td><?php echo Portal::language('to');?></td>
                                	<td><input  name="to_date" id="to_date" style="width: 80px;" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                                    <td><input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/></td>
                                </tr>
                            </table>
                                    <script>
                                        function check_validate_time(){
                                            vdate_from = jQuery('#from_date').val();
                                            vdate_to = jQuery('#to_date').val();
                                            if(vdate_to<vdate_from){
                                            alert("Date To must be greater Date From");
                                            jQuery('#to_date').css('border','1px solid red');
                                            jQuery('#to_date').val(vdate_from);
                                            
                                            return false;
                                            }
                                        }
                                    </script>
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
        jQuery("#from_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>,1 - 1, 1)});
    	jQuery("#to_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1)});
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
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" id="export">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="100px"><?php echo Portal::language('stt');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('invoice');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('room');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('room_status');?></th>
        <th class="report_table_header" width="150px"><?php echo Portal::language('total_before_tax');?></th>
        <th class="report_table_header" width="150px"><?php echo Portal::language('tax');?></th>                        
        <th class="report_table_header" width="150px"><?php echo Portal::language('total');?></th>
    </tr>
    
    
    <?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td colspan="4" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
		<td align="right" class="report_table_column"><strong class="change_numTr"><?php echo System::display_number($this->map['last_group_function_params']['total_before_tax']);?></strong></td>
        <td align="right" class="report_table_column">&nbsp;</td>
        <td align="right" class="report_table_column"><strong class="change_numTr"><?php echo System::display_number($this->map['last_group_function_params']['total']);?></strong></td>
        
	</tr>
	
				<?php
				}
				?>
    
    
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr bgcolor="white">
		<td align="center" class="report_table_column"><?php echo $this->map['items']['current']['row_num'];?></td>
        <td align="center" class="report_table_column" width="100">
            <a style="color: #039;" href="<?php echo Url::build('equipment_invoice&cmd=edit',array('id'=>$this->map['items']['current']['id'],));?>" target="_blank">EQ_<?php echo $this->map['items']['current']['position'];?></a>
        </td>
        <td align="center" class="report_table_column " ><?php echo $this->map['items']['current']['room_name'];?></td>
        <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['reservation_room_status'];?></td>
        <td align="right" class="report_table_column change_numTr" ><?php echo System::display_number($this->map['items']['current']['total_before_tax']); ?></td>
        <td align="center" class="report_table_column change_numTr" ><?php echo System::display_number($this->map['items']['current']['tax_rate']); ?>%</td>
        <td align="right" class="report_table_column change_numTr" ><?php echo System::display_number($this->map['items']['current']['total']); ?></td>
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
    
    <tr>
        <td align="right" colspan="4" class="report_sub_title" ><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>        
        <td align="right" class="report_table_column"><strong class="change_numTr"><?php echo System::display_number($this->map['group_function_params']['total_before_tax']);?></strong></td>
        <td align="right" class="report_table_column">&nbsp;</td>
        <td align="right" class="report_table_column"><strong class="change_numTr"><?php echo System::display_number($this->map['group_function_params']['total']);?></strong></td>
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
	<td width="33%" ><?php echo Portal::language('creator');?></td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center"><?php echo Portal::language('general_accountant');?></td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>

				<?php
				}
				?>
<div style="page-break-before:always;page-break-after:always;"></div>

 <?php }else{ ?>
<strong><?php echo Portal::language('no_data');?></strong>

				<?php
				}
				?>
<script>
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
    jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>