<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.alphanumeric.pack.js"></script>
<script type="text/javascript">
	var currency_array={
		'':''
	<!--LIST:currencies-->
		,'[[|currencies.id|]]':'[[|currencies.exchange|]]'
	<!--/LIST:currencies-->
	}
</script>
<div class="pay-by-currency-bound">
	<form name="PayByCurrencyReservationForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[.pay_by_currency.]]</td>
        </tr>
    </table>
    <div class="content" style="padding:20px;">
    	<h3>[[.total_amount.]]: [[|total|]] <?php echo HOTEL_CURRENCY;?></h3>
    	<table width="500" border="1" cellspacing="0" cellpadding="5">
         <tr bgcolor="#BFDFFF">
            <th width="40%" align="right">[[.currency_id.]]</th>
            <th width="60%" align="left">[[.amount.]]</th>
          </tr>
		  <?php $i=0;?>
		<!--LIST:currencies-->
          <tr <?php if($i%2!=0){ echo ' bgcolor="#EFEFEF"';};$i++;?>>
            <td align="right">[[|currencies.id|]]</td>
            <td><input name="currency_[[|currencies.id|]]" type="text" id="currency_[[|currencies.id|]]" onchange="this.value=number_format(roundNumber(this.value,2));updateCurrencyAmount();"></td>
          </tr>
		  <script type="text/javascript">
		  	jQuery("#currency_[[|currencies.id|]]").numeric({allow:"."});
		  </script>
        <!--/LIST:currencies-->
        <tr>
            <td></td>
            <td align="right"><input type="reset" value="[[.reset.]]"><input type="submit" value="[[.save.]]"></td>
          </tr>
        </table>
    </div>
    </form>
</div>
<script type="text/javascript">
	function convertCurrency(value,fromCurrency,toCurrency){
		var amount= (to_numeric(value)/to_numeric(currency_array[fromCurrency]))*to_numeric(currency_array[toCurrency]);
		return amount;
	}
	function updateCurrencyAmount(){
		<!--LIST:currencies-->
			if($('currency_[[|currencies.id|]]') && '[[|currencies.id|]]'!='USD'){
				$('currency_USD').value = number_format(roundNumber(to_numeric($('currency_USD').value) - convertCurrency(to_numeric($('currency_[[|currencies.id|]]').value),'[[|currencies.id|]]','USD'),2));
			}
		<!--/LIST:currencies-->
	}
</script>