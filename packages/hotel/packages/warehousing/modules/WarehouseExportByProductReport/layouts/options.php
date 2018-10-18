<link rel="stylesheet" href="packages/core/skins/default/css/jquery/datepicker.css" />
<script type="text/javascript" src="packages/core/includes/js/jquery/datepicker.js"></script>

<div class="product-report-bound" style="padding:20px;">
<form name="WarehouseInvoiceReportOptionsForm" method="post" onsubmit="return checkDate();">

	
    <div id="product_toolbar">
        <div class="title">
            <div>[[|title|]]</div>
        </div>
    </div>
    <br />
	<div class="content">
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
            <td colspan="2" align="left" bgcolor="#EFEFEF"><strong>[[.chotel.]]</strong></td>
            </tr>
            <tr>
            <!--Start Luu Nguyen Giap add portal -->
            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
            <td style="margin: 0;" align="center"><select name="portal_id" id="portal_id"></select></td>
            <?php //}?>
            <!--End Luu Nguyen Giap add portal -->
            </tr>
            
        </table>
        <br/>
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td colspan="2" align="left" bgcolor="#EFEFEF"><strong>[[.choose_time.]]</strong></td>
            </tr>
            <tr>
                <td align="right" width="30%">[[.from_date.]]</td>
                <td><input name="from_date" type="text" id="from_date" onchange="changevalue();" tabindex="1"/></td>
            </tr>
            <tr>
                <td align="right" width="30%">[[.to_date.]]</td>
                <td><input name="to_date" type="text" id="to_date" onchange="changefromday();" tabindex="1"/></td>
            </tr>
            
        </table>
        
        <br />
        
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong>[[.restaurant.]]-[[.warehouse_parent.]] </strong></td>
            </tr>
            
            <tr>
                <td align="center"><select name="warehouse_id" id="warehouse_id"></select></td>
            </tr>
        </table>
         <br/>
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong>[[.product_id.]] </strong></td>
            </tr>
            <tr>
                <td align="center"><input name="product_id" type="text" id="product_id" onfocus="check_warehouse();" onclick="my_autocomplete();" autocomplete="OFF" /></td>
            </tr>
        </table>
        <br/>
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td align="center"><input name="warehouse_export" type="submit" value="[[.view_report.]]" tabindex="-1"/></td>
            </tr>
        </table>
	</div>
</form>	
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	jQuery("#from_date").mask("99/99/9999");
	jQuery("#to_date").mask("99/99/9999");
	jQuery("#from_date").datepicker();
	jQuery("#to_date").datepicker();
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
	function checkDate(){
        
		if(!($('from_date').value && $('to_date').value)){
			alert('[[.You_have_to_input_time.]]');
			return false;
		}
		if(!($('warehouse_id').value)){
			alert('[[.You_have_to_select_warehouse.]]');
			return false;
		}
        
	}

	
	function my_autocomplete()
	{
		jQuery("#product_id").autocomplete({
                url: 'get_product.php?wh_invoice=1&warehouse_id='+jQuery("#warehouse_id").val(),
				selectFirst:false
        });
	}
    
    function check_warehouse()
    {
        if(!jQuery("#warehouse_id").val())
        {
            alert('You must choose warehouse');
            jQuery("#warehouse_id").focus();
            return;
        }
    }

</script>