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
        <td colspan="2" align="center" bgcolor="#EFEFEF" style="text-transform: uppercase;"><strong><?php echo Portal::language('choose_warehouse');?></strong></td>
      </tr>
      <tr>
        <td>
        <select  name="warehouse_id" id="warehouse_id" style="height: 24px;"><?php
					if(isset($this->map['warehouse_id_list']))
					{
						foreach($this->map['warehouse_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('warehouse_id',isset($this->map['warehouse_id'])?$this->map['warehouse_id']:''))
                    echo "<script>$('warehouse_id').value = \"".addslashes(URL::get('warehouse_id',isset($this->map['warehouse_id'])?$this->map['warehouse_id']:''))."\";</script>";
                    ?>
	</select>
        </td>
        <td>
        <input name="go" type="submit" id="go" value="<?php echo Portal::language('go');?>" />
        </td>
      </tr>
    </table>
</div>

<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
