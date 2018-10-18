<style>
.simple-layout-middle{
		width:100%;	
	}
@media print
{
    .search{display: none;}
}
</style>
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
                        [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo User::id();?>
                    </td>
    			</tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.material_used_report.]]<br /><br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                                [[.from_date.]]&nbsp;[[|from_date|]] &nbsp;[[.to_date.]]&nbsp;[[|to_date|]]
                            </span> 
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;"><div style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="[[.export.]]" style="width: 50px;"  /></div></td>
                </tr>	
    		</table>
        </td></tr>
    </table>		
</div>




<!---------SEARCH----------->
<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search" class="search">
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
                                    <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id" onchange="SearchForm.submit();"></select></td>
                                    <?php //}?>
                                    <td>[[.category.]]</td>
                                    <td><select name="category_id" id="category_id" style="width: 100px;"></select></td>
                                    
                                    <td>[[.from_date.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" onchange="changevalue();" class="date"/></td>
                                    <td><input name="from_time" type="text" id="from_time" style="width: 50px;" /></td>
                                    <td>[[.to_date.]]</td>
                                    <td><input name="to_time" type="text" id="to_time" style="width: 50px;"/></td>
                                	<td><input name="to_date" type="text" id="to_date" onchange="changefromday();" class="date"/></td>
                                    <td><input type="submit" name="do_search" value="  [[.report.]]  " onclick="return check_bar()"/></td>
                                </tr>
                            </table>
                            <table style="margin: 0 auto;" >
                                <tr>
                                 		<td align="right"> <input name="checked_all" type="checkbox" id="checked_all" /></td> 
                                        <td align="left"><b><label for="checked_all">[[.select_all_bar.]]</label></b></td>
                                 </tr>
                                 <!--LIST:bars-->
                                 <tr >
                                 	<!--IF:cond(Session::is_set('bar_id') and Session::get('bar_id')==[[=bars.id=]])-->
                                    	<td align="right"> <input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" checked="checked"  /></td>
                                    <!--ELSE-->
                                      <td align="right"><input  name="bar_id_[[|bars.id|]]"  type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" <?php if(isset($_REQUEST['bar_id_'.[[=bars.id=]]])) echo 'checked="checked"' ?> class="check_box"  /></td>
                                    <!--/IF:cond-->
                                      <td><label for="bar_id_[[|bars.id|]]">[[|bars.name|]]</label></td>
                        		 </tr>
                                 <!--/LIST:bars-->
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
        jQuery('#from_time').mask("99:99");
        jQuery('#to_time').mask("99:99");
        
    }
);

jQuery("#checked_all").click(function ()
{
    var check  = this.checked;
    jQuery(".check_box").each(function()
    {
        this.checked = check;
    });
});
</script>



<!--IF:check(isset([[=items=]]))-->

<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" id="export">
    <tr valign="middle" bgcolor="#EFEFEF">
        <th class="report_table_header" style="width: 10px;">[[.stt.]]</th>
        <th class="report_table_header" style="width: 50px;">[[.product_id.]]</th>
        <th class="report_table_header" style="width: 80px;">[[.product_name.]]</th>
        <th class="report_table_header" style="width: 50px;">[[.quantity_used.]]</th>
        <th class="report_table_header" style="width: 80px;">[[.unit.]]</th>
        <!--<th class="report_table_header" style="width: 80px;">[[.type.]]</th>-->
    </tr>
    <?php $stt_product = 1; ?>
    <!--LIST:category-->
        <tr style="background: #dddddd;">
            <td colspan="5" style="font-size: 15px; font-weight: bold;">[[.category.]]: [[|category.name|]]</td>
        </tr>
        <!--LIST:items-->
            <?php if([[=category.id=]]==[[=items.category_id=]]){ ?>
    	<tr bgcolor="white">
    		<td nowrap="nowrap" valign="top" align="center" class="report_table_column"><?php echo $stt_product++; ?></td>
            <td nowrap align="center" class="report_table_column" >[[|items.product_id|]]</td>
            <td nowrap align="left" class="report_table_column" >[[|items.product_name|]]</td>
            <td nowrap align="center" class="report_table_column" >[[|items.quantity|]]</td>
            <td nowrap align="center" class="report_table_column" >[[|items.unit_name|]]</td>
            <!--<td nowrap align="center" class="report_table_column" >[[|items.type|]]</td>-->
    	</tr>
            <?php } ?>
    	<!--/LIST:items-->
    <!--/LIST:category-->
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
    //start:KID 
    function test_checked()
    {
        var check  = false;
        jQuery(".check_box").each(function (){

            if(this.checked)
                check = true;
        });
        return check;
    }
    
    function check_bar()
    {
        //start:KID them doan nay vao check valid time
        var hour_from = (jQuery("#from_time").val().split(':'));
        var hour_to = (jQuery("#to_time").val().split(':'));
        var date_from_arr = jQuery("#from_date").val();
        var date_to_arr = jQuery("#to_date").val();
        //end:KID them doan nay vao check valid time
        var validate = test_checked();
        if( validate)
        {
            //start:KID them doan nay vao check valid time
            if((date_from_arr == date_to_arr) && (to_numeric(hour_from[0]) > to_numeric(hour_to[0])))
            {
                alert('[[.start_time_longer_than_end_time_try_again.]]');
                return false;
            }
            else
            {
                if(to_numeric(hour_from[0]) >23 || to_numeric(hour_to[0]) >23 || to_numeric(hour_from[1]) >59 || to_numeric(hour_to[1]) >59)
                {
                    alert('[[.the_max_time_is_2359_try_again.]]');
                    return false;
                }
                else
                {  
                    return true;
                }
            }
            //end:KID them doan nay vao check valid time
        }
        else
        {
            alert('[[.you_must_choose_bar.]]');
            return false;

        }
    }
    //end:KID   
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
        
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    });
</script>
