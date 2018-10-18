<form name="ChoiseAuditMethodForm" method="post">
  <div class="choise-audit-method-bound">
		<table border="0" cellspacing="0" cellpadding="10" class="choise-audit-method-table">
			<tr>
				<th class="title">[[.night_audit.]]</th>
			</tr>
			<tr>
				<td>
					<br /><img src="packages/hotel/skins/default/images/icons/cashier.png" /><br /><br /><br />
					[[.select_date.]]: <select name="in_date" id="in_date"></select><br /><br />
					<input name="perform_night_audit" type="submit" value="[[.perform_night_audit.]]" class="button big">
				</td>
			</tr>
			<!--IF:cond(User::can_admin() and [[=max_date=]] < Date_Time::to_time(date('d/m/Y')))-->
			<tr>
				<td>
					<h3>[[.close_night_audit.]]</h3>
					[[.from_date.]]: <!--<select name="from_date" id="from_date"></select>--><input name="from_date" type="text" value="[[|from_date|]]" id="from_date" size="8"/>
					[[.to_date.]]: <!--<select name="to_date" id="to_date"></select>--><input name="to_date" type="text" value="[[|to_date|]]" id="to_date" size="8"/>
					<br /><br />
					<input name="close_all" type="submit" value="[[.close_all.]]" class="button">
				</td>
			</tr>
			<!--/IF:cond-->
			<tr>
				<td align="left"><?php echo '<pre  class="night-audit-comfirmation">'.NIGHT_AUDIT_CONFIRMATION.'</pre>';?></td>
			</tr>
		</table>
	</div>
</form>
<script>
    	jQuery('#from_date').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
        jQuery('#to_date').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
</script>