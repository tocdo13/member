<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<form name="ListProductPriceForm" method="post">

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
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.product_price_report.]]<br /></font>
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
                                    <td>[[.line_per_page.]]</td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td>|</td>
                                    <td>[[.department.]]</td>
                                	<td>
                                		<select name="department_code" id="department_code" onchange="ListProductPriceForm.act.value='search_department';ListProductPriceForm.submit();"></select>
                                    </td>
            						<td>[[.code.]]</td>
            						<td>
            							<input name="product_id" type="text" id="product_id" style="width:50px"/>
            						</td>
                                    <td>[[.name.]]</td>
            						<td>
            							<input name="product_name" type="text" id="product_name" style="width:100px"/>
            						</td>
                                    <td>[[.category_id.]]</td>
            						<td>
            							<select name="category_id" id="category_id" style="width:150px;"></select>
            						</td>
            						<td>[[.type.]]</td>
            						<td>
            							<select  name="type" id="type" style="width:80px;">
            								<option value="">[[.all.]]</option><option value="GOODS">[[.goods.]]</option><option value="PRODUCT">[[.product.]]</option><option value="DRINK">[[.drink.]]</option><option value="MATERIAL">[[.material.]]</option><option value="EQUIPMENT">[[.equipment.]]</option><option value="SERVICE">[[.service.]]</option><option value="TOOL">[[.tool.]]</option>
            							</select>
            							<script>
            							$('type').value='<?php echo URL::get('type');?>';
            							</script>
            						</td>
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
        jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
    	jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
    }
);
</script>
<!--/IF:first_page-->



<!---------REPORT----------->	
<!--IF:check_data(!isset([[=has_no_data=]]))-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="50px">[[.order_number.]]</th>
        <th class="report_table_header" width="100px">[[.product_code.]]</th>
        <th class="report_table_header" width="350px">[[.product.]]</th>
        <th class="report_table_header" width="150px">[[.price.]]</th>
        <th class="report_table_header" width="100px">[[.unit.]]</th>
        <th class="report_table_header" width="100px">[[.type.]]</th>
    </tr>
    
    <?php $department = '';?>
    <!--LIST:items-->
    <?php if($department != [[=items.department_code=]]){ $department = [[=items.department_code=]];?>
    <tr>
        <td align="left" colspan="6" class="category-group"><strong>[[|items.department_name|]]</strong></td>
    </tr>  
    <?php }?>
    
    <tr bgcolor="white">
        <td align="center" class="report_table_column">[[|items.i|]]</td>
        <td align="left" class="report_table_column">[[|items.product_id|]]</td>
        <td align="left" class="report_table_column">[[|items.product_name|]]</td>
        <td align="right" class="report_table_column">[[|items.price|]]</td>
        <td align="center" class="report_table_column">[[|items.unit|]]</td>
        <td align="center" class="report_table_column">[[|items.type|]]</td>
	</tr>
    <!--/LIST:items-->
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

</form>	
