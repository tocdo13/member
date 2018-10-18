<form name="RevenExpenBalanceForm" method="post" >
    <table width="100%" >
        <tr height="40">
            <td class="form-title" width="25%">[[.RenvenBalance.]]</td>
            <?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="[[.save.]]"  class="button-medium-save" /></td><?php }?>
        </tr>
    </table>
    <br />
    <table width="50%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
        <tr class="table-header">
            <th width="50%" align="center" >[[.type.]]</th>
            <th width="50%" align="center" >[[.amount.]]</th>
        </tr>
        <!--LIST:balances-->
        <tr>
            <td align="center" class="row-odd">Khai báo số dư đầu kỳ [[|balances.name|]]</td>
            <td align="center" class="row-odd"><input style="text-align: right; padding-right: 5px;" type="text" <?php echo 'name="balance_'.[[=balances.name=]].'"'; ?> <?php echo 'id="balance_'.[[=balances.name=]].'"'; ?> value="<?php echo System::display_number([[=balances.amount=]]); ?>" onkeyup="processStr(this.id);" /></td>
        </tr>
        <!--/LIST:balances-->
    </table>
</form>

<script>
    function processStr(id)
    {
        if(jQuery("#"+id)){
            //console.log("#amount_"+index_item);
            jQuery("#"+id).keyup(function()
            {
                //console.log("#amount_"+index_item);
                
                var strVal = jQuery("#"+id).val();
                var strValn = strVal.replace(/,/g,"");
                            
                var len = strValn.length;
                var i;
                var newStr = '';
                for(i = len; i > 0; i-=3)
                {
                    var strTmp;
                    if(i-3 > 0)
                    strTmp = ','+strValn.substr(i-3,3);
                    else
                    strTmp = strValn.substr(0,i);
                                        
                    newStr = strTmp+newStr;
                }
                jQuery("#"+id).val(newStr);
                
            });
       	}
    }
</script>