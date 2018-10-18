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
        <td colspan="2" align="center" bgcolor="#EFEFEF" style="text-transform: uppercase;"><strong>[[.choose_warehouse.]]</strong></td>
      </tr>
      <tr>
        <td>
        <select name="warehouse_id" id="warehouse_id" style="height: 24px;"></select>
        </td>
        <td>
        <input name="go" type="submit" id="go" value="[[.go.]]" />
        </td>
      </tr>
    </table>
</div>

</form>	
</div>
