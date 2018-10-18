<!--IF:page([[=page_no=]]==1)-->
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
                    [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                    <br />
                    [[.user_print.]]:<?php echo ' '.User::id();?>
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
                                            <td style="text-align: left;">[[.karaoke.]]</td>
                                        	<td style="text-align: right;"><select name="karaoke_id" id="karaoke_id"></select></td>
                                            <td rowspan="3"><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
                                        </tr>
                                        <tr>
                                            <td>[[.no_of_page.]]</td>
                                        	<td style="text-align: right;"><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                        	<td style="text-align: left;">[[.date.]]</td>
                                        	<td style="text-align: right;"><input name="date" type="text" id="date" class="date-input" /></td>
                                        </tr>
                                        <tr>
                                            <td>[[.from_page.]]</td>
                                        	<td style="text-align: right;"><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                        	<td style="text-align: left;">&nbsp;</td>
                                        	<td style="text-align: right;">&nbsp;</td>
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
    jQuery('#date').datepicker(); 
</script>
