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
                        <br />
                        <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" ><?php echo Portal::language('amenities_used_report');?><br /></font>
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
                                    <td><?php echo Portal::language('from');?></td>
                                	<td><input  name="from_date" id="from_date" style="width: 80px;" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                    <td><?php echo Portal::language('to');?></td>
                                	<td><input  name="to_date" id="to_date" style="width: 80px;" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
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


    <table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <th class="report_table_header" rowspan="2"><?php echo Portal::language('room_name');?></th>
            <th class="report_table_header" align="center" colspan="<?php echo sizeof($this->map['room_amenities'])*2;?>"><?php echo Portal::language('list_amenities_in_room');?></th>
        </tr>
        <tr>
            <?php if(isset($this->map['room_amenities']) and is_array($this->map['room_amenities'])){ foreach($this->map['room_amenities'] as $key1=>&$item1){if($key1!='current'){$this->map['room_amenities']['current'] = &$item1;?>
            <td align="center">
                <strong><?php echo $this->map['room_amenities']['current']['name'];?></strong>
                <!--<br />
                <span style="font-size: 10px ;"><?php echo $this->map['room_amenities']['current']['name'];?></span>-->
            </td>
            <?php }}unset($this->map['room_amenities']['current']);} ?>  
        </tr> 
        <?php if(isset($this->map['rooms']) and is_array($this->map['rooms'])){ foreach($this->map['rooms'] as $key2=>&$item2){if($key2!='current'){$this->map['rooms']['current'] = &$item2;?>
        <tr>
            <td>
                Room <?php echo $this->map['rooms']['current']['name'];?>
                <input name="<?php echo $this->map['rooms']['current']['id'];?>" id="<?php echo $this->map['rooms']['current']['id'];?>" type="hidden" value="<?php echo $this->map['rooms']['current']['id'];?>"/>
            </td>
            <?php if(isset($this->map['rooms']['current']['products']) and is_array($this->map['rooms']['current']['products'])){ foreach($this->map['rooms']['current']['products'] as $key3=>&$item3){if($key3!='current'){$this->map['rooms']['current']['products']['current'] = &$item3;?>
                <?php 
				if((isset($this->map['rooms']['current']['products']['current']['is_real'])))
				{?>
                <td align="center" style="color:red;">
                    <!--<input value="<?php echo Url::get($this->map['rooms']['current']['id'].'_'.$this->map['rooms']['current']['products']['current']['product_id']);?>" name="<?php echo $this->map['rooms']['current']['id'];?>_<?php echo $this->map['rooms']['current']['products']['current']['product_id'];?>" type="text" id="<?php echo $this->map['rooms']['current']['id'];?>_<?php echo $this->map['rooms']['current']['products']['current']['product_id'];?>" style="width: 50px;text-align: center;" class="input_number" onkeyup="check_value(this)" />-->
                    <span><?php echo Url::get($this->map['rooms']['current']['id'].'_'.$this->map['rooms']['current']['products']['current']['product_id']);?></span>
                </td> 
                 <?php }else{ ?>
                <td align="center"></td>
                
				<?php
				}
				?>
            <?php }}unset($this->map['rooms']['current']['products']['current']);} ?>
        </tr>
        <?php }}unset($this->map['rooms']['current']);} ?>
        <tr>
            <td align="center" class="report_sub_title" ><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
            <?php if(isset($this->map['room_amenities']) and is_array($this->map['room_amenities'])){ foreach($this->map['room_amenities'] as $key4=>&$item4){if($key4!='current'){$this->map['room_amenities']['current'] = &$item4;?>
            <td align="center" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params'][$this->map['room_amenities']['current']['id']]);?></strong></td>
            <?php }}unset($this->map['room_amenities']['current']);} ?> 
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
</script>