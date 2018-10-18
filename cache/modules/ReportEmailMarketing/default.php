<?php 
				if(($this->map['page_no']==$this->map['start_page'] or $this->map['page_no'] == 0 ))
				{?>
<!------------------------------ HEADER ---------------------------------->
<script src="packages/core/includes/js/jquery/datepicker.js"></script>
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
                    
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title email_report"><?php echo Portal::language('email_report_maketing');?><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                
                            </span> 
                        </div>
                    </td>
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>


<form method="POST" name="email_report" id="email_report">
<!------------------------------ SEARCH ---------------------------------->
    <div id="search_email" style="width: 100%; margin: 0 auto; font-size: 11px;">
        <label><?php echo Portal::language('line_per_page');?></label><input  name="line_per_page" id="line_per_page" value="100" size="4" maxlength="20" style="text-align:right"/ type ="text" value="<?php echo String::html_normalize(URL::get('line_per_page'));?>">
        <label><?php echo Portal::language('no_of_page');?></label><input  name="total_page" id="total_page" value="500" size="4" maxlength="2" style="text-align:right"/ type ="text" value="<?php echo String::html_normalize(URL::get('total_page'));?>">
        <!-- <label><?php echo Portal::language('from_page');?></label><input  name="start_page" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/ type ="text" value="<?php echo String::html_normalize(URL::get('start_page'));?>">-->
        <label><?php echo Portal::language('from_date');?></label><input  name="date_from" id="date_from" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>">
        <label><?php echo Portal::language('to_date');?></label><input  name="date_to" id="date_to" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>">
        <label><?php echo Portal::language('type_email');?></label><select  name="group_event" id="group_event"><?php
					if(isset($this->map['group_event_list']))
					{
						foreach($this->map['group_event_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('group_event',isset($this->map['group_event'])?$this->map['group_event']:''))
                    echo "<script>$('group_event').value = \"".addslashes(URL::get('group_event',isset($this->map['group_event'])?$this->map['group_event']:''))."\";</script>";
                    ?>
	</select>
        <label><?php echo Portal::language('email_status');?></label><select  name="email_status" id="email_status"><?php
					if(isset($this->map['email_status_list']))
					{
						foreach($this->map['email_status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('email_status',isset($this->map['email_status'])?$this->map['email_status']:''))
                    echo "<script>$('email_status').value = \"".addslashes(URL::get('email_status',isset($this->map['email_status'])?$this->map['email_status']:''))."\";</script>";
                    ?>
	</select>
        <input  name="search" id="search" value="search" / type ="submit" value="<?php echo String::html_normalize(URL::get('search'));?>">
    </div>

				<?php
				}
				?>    
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			

<!------------------------------ REPORT ------------------------------------>
<?php 
				if((!isset($this->map['has_no_data'])))
				{?>
<table style="width: 100%; margin: 0 auto;"  cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" class="table-bound">
    <tr bgcolor="#EFEFEF">
        <td><?php echo Portal::language('STT');?></td>
        <td><?php echo Portal::language('customer');?></td>
        <td><?php echo Portal::language('email');?></td>
        <td><?php echo Portal::language('phone');?></td>
        <td><?php echo Portal::language('date_send');?></td>
        <td ><?php echo Portal::language('status');?></td>
    </tr>
    <?php 
    $k=0;
    ?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>    
<!--IF:first_page($this->map['page_no']!=1)-->    
    <tr>
        <td><?php echo ++$k; ?></td>
        <td>
        <?php if(!empty($this->map['items']['current']['tra_id'])){ ?>
         <a target="_blank" href="?page=traveller&cmd=edit&id=<?php echo $this->map['items']['current']['tra_id'];?>">
         <?php }else{ ?>
          <a target="_blank" href="?page=customer&cmd=edit&id=<?php echo $this->map['items']['current']['cus_id'];?>">
          <?php } ?>
         <?php echo $this->map['items']['current']['full_name'];?></a>
        </td>
        <td><?php echo $this->map['items']['current']['email'];?></td>
        <td><?php echo $this->map['items']['current']['phone'];?></td>
        <td><?php echo Date_time::convert_orc_date_to_date($this->map['items']['current']['date_send'])?></td>
        <td>
        <?php if($this->map['items']['current']['status']==0) echo 'Pending';
              elseif($this->map['items']['current']['status']==2) echo 'error';
              else echo 'sent' ?>
        </td>
    </tr>
  
    <?php }}unset($this->map['items']['current']);} ?>
</table>
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

				<?php
				}
				?>
<script>
jQuery("#date_from").datepicker();
jQuery("#date_to").datepicker();
</script>
