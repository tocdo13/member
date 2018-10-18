<script>
	full_screen();
</script>
<div style="background-color:#FFFFFF;width:100%;">
    <div style="text-align:center;background-color:#ECE9D8;width:100%;text-indent:20px;line-height:25px;text-transform:uppercase;font-weight:bold;">
        [[.number_of_items_needed_for_minibar.]]
    </div>
    <div style="text-align:center;background-color:#ECE9D8;width:100%;text-indent:20px;font-weight:bold;color:#FF0000;vertical-align:middle;">
        [[|error_message|]]
    </div>
    <table border="2" cellspacing="0" cellpadding="5" align="center" bordercolor="#CEC79B" bgcolor="#FFFFFF" style="border-collapse:collapse;">
        <tr bgcolor="#ECE9D8">
            <td></td>
            <!--LIST:minibar_products_sample-->
            <td align="center">	[[|minibar_products_sample.name|]]</td>
            <!--/LIST:minibar_products_sample-->
        </tr>
        <!--LIST:minibars-->
        <tr>
            <td>Room [[|minibars.room_name|]]</td>
            <!--LIST:minibars.products-->
            <!--<td align="center">[[|minibars.products.import_quantity|]]</td>-->
            <td align="center"><!--IF:cond( isset([[=minibars.products.quantity=]]) and [[=minibars.products.quantity=]] !=0  )-->[[|minibars.products.quantity|]]<!--/IF:cond--></td>
            <!--/LIST:minibars.products-->
        </tr>
        <!--/LIST:minibars-->
    </table>
</div>