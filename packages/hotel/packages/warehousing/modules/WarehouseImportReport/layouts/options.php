<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<script type="text/javascript">
	product_arr = <?php echo String::array2js([[=product_arr=]]);?>;
</script>
<div class="product-report-bound" style="padding:20px;">
<form name="WarehouseImportReportOptionsForm" method="post" onsubmit="return checkDate();">
<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
	<div id="product_toolbar">
		<div class="title">
			<div>[[|title|]]</div>
		</div>
	</div>
	<div class="content">
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
          <tr>
            <td colspan="2" align="left" bgcolor="#EFEFEF"><strong>CH&#7884;N KHO&#7842;NG TH&#7900;I GIAN</strong></td>
          </tr>
          <tr>
            <td align="right">[[.date_from.]]</td>
            <td><input name="date_from" type="text" id="date_from" tabindex="1"></td>
          </tr>
          <tr>
            <td align="right">[[.date_to.]]</td>
            <td><input name="date_to" type="text" id="date_to" tabindex="2"></td>
          </tr>
        </table><br />
		<table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
          <tr>
            <td bgcolor="#EFEFEF"><strong>[[.warehouse.]] </strong></td>
          </tr>
       	<!--IF:cond(Url::get('page')!='warehouse_import_report')--> 
		  <tr>
            <td align="center">
              <select name="warehouse_id" id="warehouse_id"></select></td>
          </tr>
		<!--/IF:cond-->
		 </table>
		 <br />
		<!--IF:cond(Url::get('page')=='warehouse_report')-->
		<table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
          <tr>
            <td bgcolor="#EFEFEF"><strong>B&Aacute;O C&Aacute;O NH&#7852;P XU&#7844;T T&#7890;N </strong></td>
          </tr>
		  <tr>
            <td align="center"><p>&nbsp;</p></td>
          </tr>
          <tr>
            <td align="center"><input name="store_remain" type="submit" value="[[.view_report.]]" tabindex="-1"></td>
          </tr>
        </table>
		<!--/IF:cond-->
		<!--IF:cond(Url::get('page')=='warehouse_book')-->
		<table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
          <tr>
            <td bgcolor="#EFEFEF"><strong>TH&#7866; KHO (S&#7892; KHO)</strong></td>
          </tr>
          <tr>
            <td align="center">[[.product_code.]] 
              <input name="code" type="text" id="code" tabindex="3" onclick="my_autocomplete()" AUTOCOMPLETE=OFF><input name="store_card" type="submit" value="[[.view_report.]]" tabindex="-1"></td>
          </tr>
        </table>
		<!--/IF:cond-->
		<!--IF:cond(Url::get('page')=='warehouse_export_report')-->
		<table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
		   <tr>
            <td align="center"><input name="warehouse_export" type="submit" value="[[.view_report.]]" tabindex="-1"></td>
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
	function my_autocomplete()
	{
		jQuery("#code").autocomplete({
                url: 'get_product.php?wh_invoice=1',
				selectFirst:false
        });
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