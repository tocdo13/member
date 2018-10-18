<style>
/*full m�n h�nh*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<!--IF:first_page([[=page_no=]]<=1)-->
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
                        <strong>[[.template_code.]]</strong>
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.amenities_used_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[.from.]]&nbsp;[[|from_date|]]&nbsp;[[.to.]]&nbsp;[[|to_date|]]
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
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>                                    
                                    <td>[[.from.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" style="width: 80px;" onchange="changevalue();" /></td>
                                    <td>[[.to.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" style="width: 80px;" onchange="changefromday();" /></td>
                                    <td><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
                                </tr>
                            </table>
                            
                        </form>
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
<!--/IF:first_page-->



<!---------REPORT----------->	
<!--IF:check_data(!isset([[=has_no_data=]]))-->


    <table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <th class="report_table_header" rowspan="2">[[.room_name.]]</th>
            <th class="report_table_header" align="center" colspan="<?php echo sizeof([[=room_amenities=]])*2;?>">[[.list_amenities_in_room.]]</th>
        </tr>
        <tr>
            <!--LIST:room_amenities-->
            <td align="center">
                <strong>[[|room_amenities.name|]]</strong>
                <!--<br />
                <span style="font-size: 10px ;">[[|room_amenities.name|]]</span>-->
            </td>
            <!--/LIST:room_amenities-->  
        </tr> 
        <!--LIST:rooms-->
        <tr>
            <td>
                Room [[|rooms.name|]]
                <input name="[[|rooms.id|]]" id="[[|rooms.id|]]" type="hidden" value="[[|rooms.id|]]"/>
            </td>
            <!--LIST:rooms.products-->
                <!--IF:cond_minibar(isset([[=rooms.products.is_real=]]))-->
                <td align="center" style="color:red;">
                    <!--<input value="<?php echo Url::get([[=rooms.id=]].'_'.[[=rooms.products.product_id=]]);?>" name="[[|rooms.id|]]_[[|rooms.products.product_id|]]" type="text" id="[[|rooms.id|]]_[[|rooms.products.product_id|]]" style="width: 50px;text-align: center;" class="input_number" onkeyup="check_value(this)" />-->
                    <span><?php echo Url::get([[=rooms.id=]].'_'.[[=rooms.products.product_id=]]);?></span>
                </td> 
                <!--ELSE-->
                <td align="center"></td>
                <!--/IF:cond_minibar-->
            <!--/LIST:rooms.products-->
        </tr>
        <!--/LIST:rooms-->
        <tr>
            <td align="center" class="report_sub_title" ><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
            <!--LIST:room_amenities-->
            <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]][[[=room_amenities.id=]]]);?></strong></td>
            <!--/LIST:room_amenities--> 
        </tr>
    </table>
    



<!---------FOOTER----------->
<center><div style="font-size:11px;">
  [[.page.]] [[|page_no|]]/[[|total_page|]]</div>
</center>
<br/>

<!--IF:end_page(([[=page_no=]]==[[=total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td ></td>
	<td align="center" > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center">[[.general_accountant.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<!--/IF:end_page-->
<div style="page-break-before:always;page-break-after:always;"></div>

<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_data-->
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