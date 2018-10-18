<!--IF:page(([[=page_no=]]==[[=start_page=]]) or [[=page_no=]]==0)-->
<div class="report-bound" style=" page:land;">
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%">
			<tr valign="top">
                <td align="left" width="65%">
                    <strong><?php echo HOTEL_NAME;?></strong>
                    <br />
                    <?php echo HOTEL_ADDRESS;?>
                </td>
                <td align="right" nowrap width="35%">
                    [[.template_code.]]
                    <br />
                    [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                    <br />
                    [[.user_print.]]:<?php echo User::id();?>
                </td>
			</tr>	
		</table>
		<h2 style="text-transform:uppercase;font-weight:bold;">[[.product_remain_report.]]</h2>
        
        <!---------SEARCH----------->
        <table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
            <tr><td>
            	<link rel="stylesheet" href="skins/default/report.css"/>
            	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
                	<div>
                    	<table width="100%">
                            <tr><td >
                                <form name="SearchForm" method="post">
                                    <table style="margin: 0 auto;">
                                        <tr>
                                        	<td>[[.line_per_page.]]</td>
                                        	<td style="text-align: right;"><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                            
                                            <td style="text-align: left;">[[.date.]]</td>
                                        	<td style="text-align: right;"><input name="date" type="text" id="date" class="date-input" onchange="changevalue();" /></td>
                                        </tr>
                                        <tr>
                                            <td>[[.no_of_page.]]</td>
                                        	<td style="text-align: right;"><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                        	
                                            <td style="text-align: left;">[[.to_date.]]</td>
                                        	<td style="text-align: right;"><input name="to_date" type="text" id="to_date" class="date-input" onchange="changefromday();" /></td>
                                        </tr>
                                        <tr>
                                            <td>[[.from_page.]]</td>
                                        	<td style="text-align: right;"><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                        	<td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            
                                        </tr>
                                    </table>
                                    
                                    <table style="margin: 0 auto;">
                            <tr>
                                <td style="text-align: left;">[[.hotel.]]</td>
                                <td style="text-align: right;"><select name="portal_id" id="portal_id" onchange="get_bar();"></select></td>
                            </tr>
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
                            
                            <tr>
                                <td colspan="2" style="text-align: right;"><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
                            </tr>
                        </table>
                                </form>
                            </td></tr>
                        </table>
                	</div>
            	</div>
            </td></tr>
        </table>
    </td>
</tr>
</table>
</div>
<!--/IF:page-->     
<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>
<script>
    jQuery('#status').val('<?php echo Url::get('status'); ?>');
    jQuery('.date-input').datepicker(); 
    function get_bar()
    {
        //SearchForm.submit(); 
    }

    function changevalue()
    {
        var myfromdate = $('date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date").val(jQuery("#to_date").val());
        }
    }
    
    jQuery("#checked_all").click(function (){
       // console.log("!!");
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
    });
</script>
