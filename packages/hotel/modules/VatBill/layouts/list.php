<style>
    .simple-layout-middle{
        width:100%;
    }
    .simple-layout-content{
        padding: 0px; border: none;
    }
</style>

<div style="width: 95%; margin: 0px auto 50px;">
    <table style="width: 100%;">
        <tr>
            <th><h3 style="text-transform: uppercase;">[[.vat_invoice_list.]]</h3></th>
            <th style="text-align: right;">
                <input type="button" value="[[.print_vat_other.]]" onclick="window.location.href='?page=vat_bill&cmd=add';" style="padding: 5px;" />
            </th>
        </tr>
    </table>
    <form name="VatBillListForm" method="POST">
    <table style="width: 100%;">
        <tr>
            <td>[[.vat_code.]]: <input name="vat_code" type="text" id="vat_code" style="padding: 5px;" /></td>
            <td>[[.start_date.]]: <input name="start_date" type="text" id="start_date" style="padding: 5px;" /></td>
            <td>[[.end_date.]]: <input name="end_date" type="text" id="end_date" style="padding: 5px;" /></td>
            <td>[[.status.]]: <select name="vat_type" id="vat_type" style="padding: 5px;"></select></td>
            <td>[[.type.]]: <select name="type" id="type" style="padding: 5px;"></select></td>
            <td><input name="search" type="submit" id="search" value="[[.search.]]" style="padding: 5px;" /></td>
        </tr>
    </table>
    </form>
    <table style="width: 100%; margin: 10px auto;" border="1" bordercolor="#CCCCCC">
        <tr style="background: #EEEEEE; height: 35px; text-align: center;">
            <th>[[.stt.]]</th>
            <th>[[.code.]]</th>
            <th>[[.customer_name.]]</th>
            <th>[[.type.]]</th>
            <th>[[.invoice_ids.]]</th>
            <th>[[.total.]]</th>
            <th>[[.start_date.]]</th>
            <th>[[.end_date.]]</th>
            <th>[[.creater.]]</th>
            <th>[[.create_time.]]</th>
            <th>[[.last_editer.]]</th>
            <th>[[.last_edit_time.]]</th>
            <th>[[.status.]]</th>
            <th>[[.count_print.]]</th>
            <th>[[.note.]]</th>
            <th>[[.edit.]]</th>
            <th>[[.cancel.]]</th>
        </tr>
        <!--LIST:items-->
        <tr style="height: 30px; text-align: center;">
            <td>[[|items.stt|]]</td>
            <td>[[|items.vat_code|]]</td>
            <td>[[|items.customer_name|]]</td>
            <td>[[|items.type|]]</td>
            <td>[[|items.invoice_ids|]]</td>
            <td style="text-align: right;">[[|items.total_amount|]]</td>
            <td>[[|items.start_date|]]</td>
            <td>[[|items.end_date|]]</td>
            <td>[[|items.creater|]]</td>
            <td>[[|items.create_time|]]</td>
            <td>[[|items.last_editer|]]</td>
            <td>[[|items.last_edit_time|]]</td>
            <td><!--IF:cond1([[=items.status=]]=='CANCEL')-->[[.cancel.]]<!--ELSE--><!--IF:cond([[=items.vat_type=]]=='SAVE_CODE')-->[[.save_code.]]<!--ELSE--><!--IF:cond2([[=items.vat_type=]]=='SAVE_NO_PRINT')-->[[.save_and_no_print_vat.]]<!--ELSE-->[[.printed.]]<!--/IF:cond2--><!--/IF:cond--><!--/IF:cond1--></td>
            <td>[[|items.count_print|]]</td>
            <td style="text-align: left;"><!--IF:cond1([[=items.status=]]=='CANCEL')-->[[|items.note_cancel|]]<!--ELSE-->[[|items.note|]]<!--/IF:cond1--></td>
            <td><a href="?page=vat_bill&cmd=edit&id=[[|items.id|]]"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" /></a></td>
            <td>
                <!--IF:cond1([[=items.status=]]!='CANCEL' and [[=items.vat_type=]]!='SAVE_NO_PRINT')--><input type="button" onclick="CancelVAT([[|items.id|]],'[[|items.vat_code|]]');" value="[[.cancel.]]" /><!--/IF:cond1-->
            </td>
        </tr>
        <!--/LIST:items-->
    </table>
    [[|paging|]]
</div>
<div id="LightBoxNoteCancel" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(255,255,255,0.5); display: none;">
    
</div>
<script>
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    
    function CancelVAT($vat_id,$vat_code){
        $content = '<div style="width: 640px; height: 320px; margin: calc( (50% - 320px) / 2 ) auto; background: #2D2D30; box-shadow: 0px 0px 3px #1297FB;">';
            $content += '<div style="width: 630px; height: 50px; padding: 5px; border-bottom: 1px solid #4B4B4D;"><h3 style="line-height: 50px; margin: 0px; padding: 0px; color: #1297FB; margin-left: 10px; text-transform: uppercase;">[[.vat_cancel_confrim.]] '+$vat_code+'</h3></div>';
            $content += '<div style="width: 630px; height: 190px; padding: 5px;">';
                $content += '<p style="color: #FFFFFF;">[[.note.]]</p>';
                $content += '<textarea id="note_cancel" style="width: calc(100% - 5px); background: none; border: 1px solid #1297FB; margin: 0px auto; height: 150px; color: #FFFFFF;"></textarea>';
            $content += '</div>';
            $content += '<div style="width: 630px; height: 50px; padding: 5px;">';
                $content += '<input type="button" value="[[.cancel.]]" onclick="ResetCancel();" style="float: right; margin-right: 10px; padding: 10px;" />';
                $content += '<input type="button" value="[[.action.]]" onclick="ActionCancelVat('+$vat_id+');" style="float: right; margin-right: 10px; padding: 10px;" />';
            $content += '</div>';
        $content += '</div>';
        
        jQuery("#LightBoxNoteCancel").html($content);
        jQuery("#LightBoxNoteCancel").css('display','');
    }
    function ResetCancel() {
        jQuery("#LightBoxNoteCancel").html('');
        jQuery("#LightBoxNoteCancel").css('display','none');
    }
    function ActionCancelVat($vat_id) {
        <?php echo 'var block_id = '.Module::block_id().';';?>
        $note_cancel = jQuery("#note_cancel").val();
        if(to_numeric($note_cancel.length)<3) {
            alert('[[.note_must_contain_3_or_more_characters.]]');
            return false;
        } else {
            jQuery.ajax({
    					url:"form.php?block_id="+block_id,
    					type:"POST",
    					data:{actioncancel:'CANCELVAT',vat_bill_id:$vat_id,note_cancel:$note_cancel},
    					success:function(html)
                        {
                            console.log(html);
                            if(html=='error') {
                                alert('[[.action_unsuccess.]] !');
                            } else {
                                alert('[[.success.]] !');
                                location.reload();
                            }
    					}
		          });
        }
    }
</script>