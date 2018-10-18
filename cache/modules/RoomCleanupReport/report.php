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
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('room_cleanup_report');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                <?php echo Portal::language('date');?>&nbsp;<?php echo $this->map['date'];?>&nbsp;
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
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="<?php echo $this->map['no_of_page'];?>" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td><?php echo Portal::language('user');?></td>
                                	<td><select  name="user_id" id="user_id"><?php
					if(isset($this->map['user_id_list']))
					{
						foreach($this->map['user_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('user_id',isset($this->map['user_id'])?$this->map['user_id']:''))
                    echo "<script>$('user_id').value = \"".addslashes(URL::get('user_id',isset($this->map['user_id'])?$this->map['user_id']:''))."\";</script>";
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
        jQuery("#date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
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
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="50px" rowspan="2"><?php echo Portal::language('stt');?></th>
        <th class="report_table_header" width="100px" rowspan="2"><?php echo Portal::language('room');?></th>
        <th class="report_table_header" width="100px" rowspan="2"><?php echo Portal::language('total');?></th>
        <th class="report_table_header" width="100px" colspan="5"><?php echo Portal::language('cleanup');?></th>
    </tr>
    <tr>
        <th class="report_table_header" width="100px"><?php echo Portal::language('status');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('start_time');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('end_time');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('user');?></th>
        <th class="report_table_header" width="200px"><?php echo Portal::language('note');?></th>
    </tr>
    
    
    
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr bgcolor="white">
        <td align="center" class="report_table_column"  rowspan="<?php echo $this->map['items']['current']['total_clean'];?>"><?php echo $this->map['items']['current']['stt'];?></td>
        <td align="center" class="report_table_column"  rowspan="<?php echo $this->map['items']['current']['total_clean'];?>"><?php echo $this->map['items']['current']['room_name'];?></td>
        <td align="center" class="report_table_column"  rowspan="<?php echo $this->map['items']['current']['total_clean'];?>"><?php echo $this->map['items']['current']['total_clean'] -1 ; ?></td>
    </tr>
        <?php if(isset($this->map['items']['current']['cleanup']) and is_array($this->map['items']['current']['cleanup'])){ foreach($this->map['items']['current']['cleanup'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['cleanup']['current'] = &$item2;?>
        <tr>
            <td align="left" class="report_table_column"><?php echo $this->map['items']['current']['cleanup']['current']['status'];?></td>
            <td align="left" class="report_table_column">
                <?php echo $this->map['items']['current']['cleanup']['current']['start_time']? date('H\h:i d/m/Y',$this->map['items']['current']['cleanup']['current']['start_time']):'' ?>
            </td>
            <td align="left" class="report_table_column">
                <?php echo $this->map['items']['current']['cleanup']['current']['end_time']? date('H\h:i d/m/Y',$this->map['items']['current']['cleanup']['current']['end_time']):'' ?>
            </td>
            <td align="left" class="report_table_column"><?php echo $this->map['items']['current']['cleanup']['current']['user_id'];?></td>
            <td align="left" class="report_table_column"><?php echo $this->map['items']['current']['cleanup']['current']['note'];?></td>
        </tr>
        <?php }}unset($this->map['items']['current']['cleanup']['current']);} ?>
	
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