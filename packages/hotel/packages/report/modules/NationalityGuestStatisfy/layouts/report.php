<style>
/*full m�n h�nh*/
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                        
                        <br />
                        [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.statisfy_guest_nationality.]]<br /></font>
                            <label style="font-size: 14px;">([[.attach_form.]])<br /></label>
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
                                   
                                    <td>[[.hotel.]]: <select name="portal_id" id="portal_id"></select></td>
                                    <td>|</td>
                                    <td>[[.from.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" style="width: 80px;"/></td>
                                    <td>[[.to.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" style="width: 80px;"/></td>
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



<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">

	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="250px" rowspan="2">[[.nationality.]]</th>
        <th class="report_table_header" width="250px" rowspan="2">[[.total_of_guest.]]<br />([[.count.]])</th>
        <th class="report_table_header" width="250px" rowspan="2">[[.male.]]<br />(M)</th>
        <th class="report_table_header" width="250px" rowspan="2">[[.female.]]<br />(F)</th>
        <th class="report_table_header" width="250px" colspan="3">[[.in_which.]]</th>
    </tr>
    <tr>
        <th class="report_table_header" width="250px">[[.foreign_visitor.]]</th>
        <th class="report_table_header" width="250px">[[.foreign_vietnamese.]]</th>
        <th class="report_table_header" width="250px">[[.vietnamese_1.]]</th>
    </tr>
    <!--LIST:items-->
    <tr>
        <td><?php if([[=items.country_name=]]!='country_other'){?>[[|items.country_name|]]<?php }else{?> [[.country_other.]] <?php } ?></td>
        <td>[[|items.count_traveller|]]</td>
        <td>[[|items.count_male|]]</td>
        <td>[[|items.count_famale|]]</td>
        <td>[[|items.count_visitor|]]</td>
        <td>[[|items.count_visitor_vietnam|]]</td>
        <td>[[|items.count_vietnam|]]</td>
    </tr>
    <!--/LIST:items-->
    <tr>
        <th>[[.total.]]</th>
        <th>[[|total_traveller|]]</th>
        <th>[[|total_male|]]</th>
        <th>[[|total_famale|]]</th>
        <th>[[|total_visitor|]]</th>
        <th>[[|total_visitor_vietnam|]]</th>
        <th>[[|total_vietnam|]]</th>
    </tr>
<!---------FOOTER----------->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td ></td>
	<td align="center" > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" ></td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center"><strong>[[.representative.]]</strong><br /><em>([[.signature_stamped.]])</em></td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>