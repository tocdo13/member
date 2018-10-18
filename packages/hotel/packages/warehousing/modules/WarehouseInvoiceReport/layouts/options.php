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
        <br />
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
            <td colspan="2" align="left" bgcolor="#EFEFEF"><strong>CH&#7884;N KHO&#7842;NG TH&#7900;I GIAN</strong></td>
            </tr>
            <tr>
            <td align="right" width="30%">[[.date_from.]]</td>
            <td><input name="date_from" type="text" id="date_from" onchange="changevalue();" tabindex="1"/></td>
            </tr>
            <tr>
            <td align="right">[[.date_to.]]</td>
            <td><input name="date_to" type="text" id="date_to" onchange="changefromday();" tabindex="2"/></td>
                
            </tr>
        </table>
        
        <br />
        
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong>[[.warehouse.]] </strong></td>
            </tr>
            
            <tr>
                <td align="center"><select name="warehouse_id" id="warehouse_id"></select></td>
            </tr>
        </table>
        <br />
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong>[[.invoice_type.]] </strong></td>
            </tr>
            
            
            <tr>
                <td align="center"><select name="type" id="type"></select></td>
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
	jQuery("#date_from").mask("99/99/9999");
	jQuery("#date_to").mask("99/99/9999");
	jQuery("#date_from").datepicker();
	jQuery("#date_to").datepicker();
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }	
	function checkDate(){
		if(!($('date_from').value && $('date_to').value)){
			alert('[[.You_have_to_input_time.]]');
			return false;
		}
        
		if(!($('warehouse_id').value)){
			alert('[[.You_have_to_select_warehouse.]]');
			return false;
		}
        
	}
</script>