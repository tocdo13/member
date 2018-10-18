<style>
@media print
{
    #search{
        display: none;
    }   
}
.room_repair{    
    width: 80%;
    margin: 0 auto;
    border-collapse: collapse;
    font-size:12px;
}
.hover:hover{
    background: #CCCCCC;
}
</style>
<form id="find" class="find" name="find" method="post">
<table cellpadding="10" cellspacing="0" width="100%" id="head">
<tr>
    <td align="center">
        <table border="0" cellSpacing="0" cellpadding="5" width="100%" style="margin: 0 auto;">
            <tr valign="middle">
                <td width="100"></td>
                <td align="left">
                    <br />
                    <strong><?php echo HOTEL_NAME;?></strong>
                    <br />
                    ADD: <?php echo Portal::language('address_hotel');?>
                    <br />
                    Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?>
                    <br />
                    Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
                </td>
                <td align="right">
                <?php echo Portal::language('date_print');?>:&nbsp;<?php echo date('H:i  d/m/Y');?>
                <br />
                <?php echo Portal::language('user_print');?>:&nbsp;<?php echo User::id();?>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td colspan="3" style="text-align:center;">
                    <font class="report_title specific" ><strong><?php echo Portal::language('report_room_repair');?></strong><br /><br /></font>
                    <span style="font-family:'Times New Roman', Times, serif;font-weight:normal; font-size:12px">                         
                    <strong><?php echo Portal::language('date_from');?>&nbsp;<?php echo $this->map['start_date_find'];?>&nbsp;<?php echo Portal::language('date_to');?>&nbsp;<?php echo $this->map['end_date_find'];?></strong>                   
                    </span> 
                </td>
            </tr>               
        </table>                              
    </td>
</tr>
</table>
<div style="width: 80%; margin: 0 auto;">
            <fieldset id="search">
            <legend><?php echo Portal::language('find');?></legend>
                <table style="margin: 0 auto;">
                    <td><?php echo Portal::language('start_date');?></td>
                    <td><input  name="start_date" id="start_date" readonly="readonly" autocomplete="off" style="width: 100px; height: 17px;" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>"></td>
                    <td><?php echo Portal::language('end_date');?></td>
                    <td><input  name="end_date" id="end_date" readonly="readonly" autocomplete="off" style="width: 100px; height: 17px;" / type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>"></td>
                    <td><?php echo Portal::language('floor_view');?></td>
                    <td><select  name="floors_name" id="floors_name" style="width: 100px;height: 25px;"><?php
					if(isset($this->map['floors_name_list']))
					{
						foreach($this->map['floors_name_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('floors_name',isset($this->map['floors_name'])?$this->map['floors_name']:''))
                    echo "<script>$('floors_name').value = \"".addslashes(URL::get('floors_name',isset($this->map['floors_name'])?$this->map['floors_name']:''))."\";</script>";
                    ?>
	</select></td>            
                    <td><input name="search" id="search" style="border: none; border: 2px solid #b8e9fd; background: blue; color: #fff; cursor: pointer;padding:7px; border-radius: 10%;width: 100px" type="submit" value="<?php echo Portal::language('find');?>" /></td>                                        
                    <td class="btn-light-green" style="width: 100px;"><span id="btnExport" class="button" onclick="ExportExcel()">&nbsp; <i class="fa fa-download" aria-hidden="true"></i>&nbsp; <?php echo Portal::language('export_excel');?> &nbsp;</span></td>                   
                </table>
            </fieldset>
        </div>
<br /><br /><br />
<!---------REPORT----------->

<table id="room_repair" class="room_repair" border="1" bordercolor="green">
    <tr align="center" style=" font-weight: bold; background: #CCCCCC; height: 25px; border-collapse: collapse;border-collapse: collapse;">
        <td ><?php echo Portal::language('stt');?></td>
        <td ><?php echo Portal::language('room_name');?></td>
        <td ><?php echo Portal::language('room_type');?></td>
        <td ><?php echo Portal::language('note');?></td>
        <td ><?php echo Portal::language('start_date_repair');?></td>
        <td ><?php echo Portal::language('end_date_repair');?></td>
        <td style="border:black thin<strong></strong> solid;"><?php echo Portal::language('user_repair');?></td>
    </tr>
    <?php $i=1;?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr align="center" style=" height: 25px; border-collapse: collapse;" class="hover">
        <td ><?php echo $i++;?></td>
        <td ><?php echo $this->map['items']['current']['name'];?></td>
        <td ><?php echo $this->map['items']['current']['room_level'];?></td>
        <td ><?php echo $this->map['items']['current']['note'];?></td>
        <td ><?php echo $this->map['items']['current']['start_date'];?></td>
        <td ><?php echo $this->map['items']['current']['end_date'];?></td>
        <td ><?php echo $this->map['items']['current']['user_repair'];?></td>    
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
</table>
<br /><br />
        <table width="100%" style="font-family:'Times New Roman', Times, serif" id="footer">
        <tr>
            <td></td>
            <td></td>
            <td align="center"><?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
        </tr>
        <tr valign="top">
            <td width="15%" align="center"></td>
            <td width="15%" align="center"></td>
            <td width="30%" align="center"><?php echo Portal::language('creator');?></td>
        </tr>
        </table>
</tale>   
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			       
        <p>&nbsp;</p>
        <script>full_screen();</script>

<div style="page-break-before:always;page-break-after:always;"></div>
<script>
var CURRENT_YEAR = <?php echo date('Y')?>;
var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
var CURRENT_DAY = <?php echo date('d')?>;
jQuery('#start_date').datepicker({maxDate: new Date(CURRENT_YEAR,CURRENT_MONTH,CURRENT_DAY)});
jQuery('#end_date').datepicker({minDate: new Date(CURRENT_YEAR,CURRENT_MONTH,CURRENT_DAY)});
function ExportExcel()
{        
     jQuery("#room_repair").battatech_excelexport({        
        containerid: "room_repair"
       , datatype: 'table'
       , fileName: '<?php echo Portal::language('report_room_repair');?>'                       
    });
                 
}         
</script>