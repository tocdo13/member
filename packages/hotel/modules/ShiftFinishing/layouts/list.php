<style>
	.shift-finishing{
		text-align:center;
		padding:20px;	
	}
	.shift-finishing table{
		margin:auto;
	}
	.total-amount{
		font-size:20px;
	}
</style>
<div class="shift-finishing">
<table width="400" border="0" cellspacing="0" cellpadding="10" align="center">
    <tr>
    	<td align="center" class="title">[[.shift_finishing.]]<br /> <span class="sub-title">([[.current_time.]]: [[|current_time|]])<br />[[.username.]]: [[|user_id|]]</span></td>
    </tr>
    <tr>
        <td align="center"><span class="total-amount">[[.total.]]:[[|total|]]</span></td>
    </tr>
     <tr>
        <td align="center"><input type="submit" value="[[.finish.]]" /></td>
    </tr>
</table>
</div>