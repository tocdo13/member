<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<script type="text/javascript">
	product_arr = <?php echo String::array2js([[=product_arr=]]);?>;
	products= <?php echo String::array2suggest([[=product_arr=]]);?>;	
</script>
<div class="product-report-bound" style="padding:20px;">
<form name="RestaurantWarehouseReportOptionsForm" method="post" onsubmit="return checkDate();">
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
		<!--IF:cond(Url::get('page')=='restaurant_warehouse_report')-->
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
		<!--IF:cond(Url::get('page')=='restaurant_warehouse_book')-->
		<table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
          <tr>
            <td bgcolor="#EFEFEF"><strong>TH&#7866; KHO (S&#7892; KHO)</strong></td>
          </tr>
          <tr>
            <td align="center">[[.product_code.]] 
              <input name="code" type="text" id="code" tabindex="3" onclick="my_autocomplete()"><input name="store_card" type="submit" value="[[.view_report.]]" tabindex="-1"></td>
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
	var data = Array();
	for(var i in product_arr)
	{
		data.push(i);
	}
	function my_autocomplete()
	{
		//jQuery("#code").autocomplete(data).result(function(){});
		jQuery("#code").autocomplete(products, {
			minChars: 0,
			width: 310,
			matchContains: true,
			autoFill: false,
			formatItem: function(row, i, max) {
				return row.name;
			},
			formatMatch: function(row, i, max) {
				return row.name;
			},
			formatResult: function(row) {
				return row.to;
			}
		}).result(function(){});		
		
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