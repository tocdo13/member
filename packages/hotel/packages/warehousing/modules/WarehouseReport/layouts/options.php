<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<script type="text/javascript">
	product_arr = <?php echo String::array2js([[=product_arr=]]);?>;
</script>
<div class="product-report-bound" style="padding:20px;">
<form name="WarehouseImportReportOptionsForm" method="post" onsubmit="return checkDate();">
    <?php if(Form::$current->is_error()){?>
    <div>
    <br/>
    <?php echo Form::$current->error_messages();?>
    </div>
    <?php }?>
    
    <div id="product_toolbar">
        <div class="title">
            <div>[[|title|]]</div>
        </div>
    </div>
    <br />
	<div class="content">
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
            <td colspan="2" align="left" bgcolor="#EFEFEF"><strong>[[.time_select.]]</strong></td>
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
        
        <!--khong phai bao cao nha cc thi phai chon kho-->
        <!--IF:cond(Url::get('page')!='warehouse_import_report' && Url::get('page')!='warehouse_export_supplier_report')-->
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong>[[.warehouse.]] </strong></td>
            </tr>
            <tr>
                <td align="center"><select name="warehouse_id" id="warehouse_id"></select></td>
            </tr>
        </table>
        <!--/IF:cond-->	
        <!--xuat chuyen kho-->
        <!--IF:cond(Url::get('page')=='warehouse_export_report')-->
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong>[[.to_warehouse.]] </strong></td>
            </tr>
            <tr>
                <td align="center"><select name="warehouse_to_id" id="warehouse_to_id"></select></td>
            </tr>
        </table>
        <!--/IF:cond-->
        <br />	 
        <!--bao cao nha cc thi  khong phai chon kho-->
        <!--IF:cond(Url::get('page')=='warehouse_import_report')-->
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            
            <tr>
                <td align="right">[[.supplier.]]: </td>
                <!--<td align="left"><select name="supplier_id" id="supplier_id"></select></td>-->
                <td align="left">
                    <input name="supplier_name" type="text" id="supplier_name" onfocus="Autocomplete_sp()" oninput="check_sp()" />
                    <input name="supplier_id" type="text" id="supplier_id" style="display: none;" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input name="warehouse_import" type="submit" value="[[.view_report.]]" tabindex="-1"/></td>
            </tr>
        </table>
        <!--/IF:cond-->
        <br />
        <!--bao cao tra lai nha cc thi  khong phai chon kho-->
        <!--IF:cond(Url::get('page')=='warehouse_export_supplier_report')-->
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            
            <tr>
                <td align="right">[[.supplier.]]: </td>
                <!--<td align="left"><select name="supplier_id" id="supplier_id"></select></td>-->
                <td align="left">
                    <input name="supplier_name" type="text" id="supplier_name" onfocus="Autocomplete_sp()" oninput="check_sp()" />
                    <input name="supplier_id" type="text" id="supplier_id" style="display: none;" />
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input name="warehouse_export_supplier" type="submit" value="[[.view_report.]]" tabindex="-1"/></td>
            </tr>
        </table>
        <!--/IF:cond-->
        <br />
        <!--xuat nhap ton-->
        <!--IF:cond(Url::get('page')=='warehouse_report')-->
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong>B&Aacute;O C&Aacute;O NH&#7852;P XU&#7844;T T&#7890;N </strong></td>
            </tr>
            <tr>
                <td align="center">
                    <input name="negative_number" type="checkbox" id="negative_number" value="1" />
                    <label for="negative_number">[[.only_product_with_negative_number.]]</label>
                </td>
            </tr>
            <tr>
                <td align="center"><input name="store_remain" type="submit" value="[[.view_report.]]" tabindex="-1"/></td>
            </tr>
        </table>
        <!--/IF:cond-->
        <br />
        <!--the? kho-->
        <!--IF:cond(Url::get('page')=='warehouse_book')-->
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong>TH&#7866; KHO (S&#7892; KHO)</strong></td>
            </tr>
            <tr>
                <td align="center">
                [[.product.]]
                <input name="code" type="text" id="code" tabindex="3" onfocus="check_warehouse();" onclick="my_autocomplete();" onkeyup="my_autocomplete();" autocomplete="OFF"/>
                <input name="store_card" type="submit" value="[[.view_report.]]" tabindex="-1" onclick="return check_code();"/>
            </td>
            </tr>
        </table>
        <!--/IF:cond-->
        <br />
        <!--chuyen kho-->
        <!--IF:cond(Url::get('page')=='warehouse_export_report')-->
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td align="center"><input name="warehouse_export" type="submit" value="[[.view_report.]]" tabindex="-1"/></td>
            </tr>
        </table>
        <!--/IF:cond-->
        <br />
        <h3>&nbsp;</h3>
        <h3>&nbsp;</h3>
	</div>
</form>	
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	jQuery("#date_from").mask("99/99/9999");
	jQuery("#date_to").mask("99/99/9999");
	jQuery("#date_from").datepicker();
	jQuery("#date_to").datepicker();
    var supplier_arr = <?php echo String::array2js([[=suppliers=]]); ?>;	
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
	function my_autocomplete()
	{
		jQuery("#code").autocomplete({
                url: 'get_product.php?wh_invoice=2&warehouse_id='+jQuery("#warehouse_id").val(),
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
    function check_code(){
        if(jQuery("#code").val()=='')
        {
            alert("[[.you_must_choose_code_product.]]");
            return false;
        }
    }
    function check_sp(){
    if(jQuery('#supplier_name').val()=='')
    {
        jQuery('#supplier_id').val('');
    }
    }
    function Autocomplete_sp()
    {
        jQuery('#supplier_name').autocomplete({
            url:'get_customer1.php?supplier=1',
            onItemSelect:function(item){
            jQuery("#supplier_id").val(supplier_arr[item.data]['supplier_id']);
            
           }
        });
        
    }
</script>
