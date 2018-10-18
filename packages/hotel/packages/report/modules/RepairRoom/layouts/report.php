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
                    ADD: [[.address_hotel.]]
                    <br />
                    Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?>
                    <br />
                    Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
                </td>
                <td align="right">
                [[.date_print.]]:&nbsp;<?php echo date('H:i  d/m/Y');?>
                <br />
                [[.user_print.]]:&nbsp;<?php echo User::id();?>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td colspan="3" style="text-align:center;">
                    <font class="report_title specific" ><strong>[[.report_room_repair.]]</strong><br /><br /></font>
                    <span style="font-family:'Times New Roman', Times, serif;font-weight:normal; font-size:12px">                         
                    <strong>[[.date_from.]]&nbsp;[[|start_date_find|]]&nbsp;[[.date_to.]]&nbsp;[[|end_date_find|]]</strong>                   
                    </span> 
                </td>
            </tr>               
        </table>                              
    </td>
</tr>
</table>
<div style="width: 80%; margin: 0 auto;">
            <fieldset id="search">
            <legend>[[.find.]]</legend>
                <table style="margin: 0 auto;">
                    <td>[[.start_date.]]</td>
                    <td><input name="start_date" type="text" id="start_date" readonly="readonly" autocomplete="off" style="width: 100px; height: 17px;" /></td>
                    <td>[[.end_date.]]</td>
                    <td><input name="end_date" type="text" id="end_date" readonly="readonly" autocomplete="off" style="width: 100px; height: 17px;" /></td>
                    <td>[[.floor_view.]]</td>
                    <td><select name="floors_name" id="floors_name" style="width: 100px;height: 25px;"></select></td>            
                    <td><input name="search" id="search" style="border: none; border: 2px solid #b8e9fd; background: blue; color: #fff; cursor: pointer;padding:7px; border-radius: 10%;width: 100px" type="submit" value="[[.find.]]" /></td>                                        
                    <td class="btn-light-green" style="width: 100px;"><span id="btnExport" class="button" onclick="ExportExcel()">&nbsp; <i class="fa fa-download" aria-hidden="true"></i>&nbsp; [[.export_excel.]] &nbsp;</span></td>                   
                </table>
            </fieldset>
        </div>
<br /><br /><br />
<!---------REPORT----------->

<table id="room_repair" class="room_repair" border="1" bordercolor="green">
    <tr align="center" style=" font-weight: bold; background: #CCCCCC; height: 25px; border-collapse: collapse;border-collapse: collapse;">
        <td >[[.stt.]]</td>
        <td >[[.room_name.]]</td>
        <td >[[.room_type.]]</td>
        <td >[[.note.]]</td>
        <td >[[.start_date_repair.]]</td>
        <td >[[.end_date_repair.]]</td>
        <td style="border:black thin<strong></strong> solid;">[[.user_repair.]]</td>
    </tr>
    <?php $i=1;?>
    <!--LIST:items-->
    <tr align="center" style=" height: 25px; border-collapse: collapse;" class="hover">
        <td ><?php echo $i++;?></td>
        <td >[[|items.name|]]</td>
        <td >[[|items.room_level|]]</td>
        <td >[[|items.note|]]</td>
        <td >[[|items.start_date|]]</td>
        <td >[[|items.end_date|]]</td>
        <td >[[|items.user_repair|]]</td>    
    </tr>
    <!--/LIST:items-->
</table>
<br /><br />
        <table width="100%" style="font-family:'Times New Roman', Times, serif" id="footer">
        <tr>
            <td></td>
            <td></td>
            <td align="center">[[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
        </tr>
        <tr valign="top">
            <td width="15%" align="center"></td>
            <td width="15%" align="center"></td>
            <td width="30%" align="center">[[.creator.]]</td>
        </tr>
        </table>
</tale>   
</form>       
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
       , fileName: '[[.report_room_repair.]]'                       
    });
                 
}         
</script>