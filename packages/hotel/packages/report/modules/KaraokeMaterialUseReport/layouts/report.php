<!--IF:first_page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->

<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr>
                    <td align="left" width="35%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
    				<td></td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>
                    </td>
    			</tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td></td>
                    <td>
                        <div style="width:75%; text-align:center;">
                            <font class="report_title specific" >[[.material_used_report.]]<br /><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[.from_date.]]&nbsp;[[|from_date|]] &nbsp;[[.to_date.]]&nbsp;[[|to_date|]]
                            </span> 
                        </div>
                    </td>
                    <td></td>
                </tr>	
    		</table>
        </td></tr>
    </table>		
</div>




<!---------SEARCH----------->
<table width="100%"  bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td>[[.line_per_page.]]</td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td>[[.from_page.]]</td>
                                	<td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id" onchange="SearchForm.submit();"></select></td>
                                    <?php }?>
                                    <td>[[.karaoke.]]</td>
                                    <td><select name="karaoke_id" id="karaoke_id" style="width: 100px;"></select></td>
                                    <td>[[.from_date.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" onchange="changevalue();" class="date"/></td>
                                    <td>[[.to_date.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" onchange="changefromday();a" class="date"/></td>
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

<!--/IF:first_page-->

<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
.date{width: 70px!important;}
@media print
{
    #search{display:none}
}
</style>
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
    }
);
</script>



<!--IF:check(isset([[=items=]]))-->

<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC">
    <tr valign="middle" bgcolor="#EFEFEF">
        <th class="report_table_header" style="width: 10px;">[[.stt.]]</th>
        <th class="report_table_header" style="width: 50px;">[[.product_id.]]</th>
        <th class="report_table_header" style="width: 80px;">[[.product_name.]]</th>
        <th class="report_table_header" style="width: 50px;">[[.quantity_used.]]</th>
        <th class="report_table_header" style="width: 80px;">[[.unit.]]</th>
        <!--<th class="report_table_header" style="width: 80px;">[[.type.]]</th>-->
    </tr>

    <!--LIST:items-->

	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="center" class="report_table_column">[[|items.stt|]]</td>
        <td nowrap align="center" class="report_table_column" >[[|items.product_id|]]</td>
        <td nowrap align="left" class="report_table_column" >[[|items.product_name|]]</td>
        <td nowrap align="center" class="report_table_column" >[[|items.quantity|]]</td>
        <td nowrap align="center" class="report_table_column" >[[|items.unit_name|]]</td>
        <!--<td nowrap align="center" class="report_table_column" >[[|items.type|]]</td>-->
	</tr>
	<!--/LIST:items-->
</table>
<br/>
<center>[[.page.]] [[|page_no|]]/[[|total_page|]]</center>
<br/>

<!--ELSE-->
<strong>[[.no_data.]]</strong>

<!--/IF:check-->

<!---------FOOTER----------->


<!--IF:end_page([[=real_page_no=]]==[[=real_total_page=]])-->

<table width="100%" cellpadding="10">
<tr>
	<td></td>
	<td ></td>
	<td > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >[[.general_accountant.]]</td>
	<td width="33%" >[[.director.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<!--/IF:end_page-->
<div style="page-break-before:always;page-break-after:always;"></div>
<script>
    function changevalue(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('to_date').value =$('from_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('to_date').value =$('from_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('to_date').value =$('from_date').value;
                }
            }
        }
    }
    function changefromday(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('from_date').value= $('to_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('from_date').value = $('to_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('from_date').value =$('to_date').value;
                }
            }
        }
    }
</script>