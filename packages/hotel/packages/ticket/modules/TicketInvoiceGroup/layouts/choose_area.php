<div class="product-report-bound" style="padding:20px;">
<form name="WarehouseImportReportOptionsForm" method="post">
<?php if(Form::$current->is_error()){?>
<div>
    <br/>
    <?php echo Form::$current->error_messages();?>
</div>
<?php }?>

<div class="content" style="margin: 0 auto; width: 250px;">
    <table width="200" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
      <tr>
        <td colspan="2" align="center" bgcolor="#EFEFEF"><strong>[[.choose_ticket_area.]]</strong></td>
      </tr>
      <tr>
        <td>
            <select name="ticket_area_id" id="ticket_area_id"></select>
        </td>
        <td>
        <input name="go" type="submit" id="go" value="[[.go.]]" />
        </td>
      </tr>
    </table>
</div>

</form>	
</div>
