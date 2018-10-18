<link rel="stylesheet" href="packages/core/skins/default/css/jquery/datepicker.css" />
<script type="text/javascript" src="packages/core/includes/js/jquery/datepicker.js"></script>

<div class="product-report-bound" style="padding:20px;">
<form name="WarehouseInvoiceReportOptionsForm" method="post" onsubmit="return checkDate();">

	
    <div id="product_toolbar">
        <div class="title">
            <div><?php echo $this->map['title'];?></div>
        </div>
    </div>
    <br />
	<div class="content">
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
            <td colspan="2" align="left" bgcolor="#EFEFEF"><strong><?php echo Portal::language('chotel');?></strong></td>
            </tr>
            <tr>
            <!--Start Luu Nguyen Giap add portal -->
            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
            <td style="margin: 0;" align="center"><select  name="portal_id" id="portal_id"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></td>
            <?php //}?>
            <!--End Luu Nguyen Giap add portal -->
            </tr>
            
        </table>
        <br />
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td colspan="2" align="left" bgcolor="#EFEFEF"><strong><?php echo Portal::language('choose_time');?></strong></td>
            </tr>
            <tr>
                <td align="right" width="30%"><?php echo Portal::language('date');?></td>
                <td><input  name="date" id="date" tabindex="1"/ type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>"></td>
            </tr>
        </table>
        
        <br />
        
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong><?php echo Portal::language('warehouse_parent');?> </strong></td>
            </tr>
            
            <tr>
                <td align="center"><select  name="warehouse_id" id="warehouse_id"><?php
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
	</select></td>
            </tr>
        </table>
         <br />
        <!--
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong><?php echo Portal::language('product_id');?> </strong></td>
            </tr>
            <tr>
                <td align="center"><input  name="product_id" id="product_id" onfocus="check_warehouse();" onclick="my_autocomplete();" onkeyup="my_autocomplete();" autocomplete="OFF" / type ="text" value="<?php echo String::html_normalize(URL::get('product_id'));?>"></td>
            </tr>
        </table>
        -->
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td align="center"><input name="warehouse_export" type="submit" value="<?php echo Portal::language('view_report');?>" tabindex="-1"/></td>
            </tr>
        </table>
	</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	jQuery("#date").mask("99/99/9999");
	jQuery("#date").datepicker();	
	function checkDate(){
		if(!($('date').value)){
			alert('<?php echo Portal::language('You_have_to_input_time');?>');
			return false;
		}
        
		if(!($('warehouse_id').value)){
			alert('<?php echo Portal::language('You_have_to_select_warehouse');?>');
			return false;
		}
        
	}

	
	function my_autocomplete()
	{
		jQuery("#product_id").autocomplete({
                url: 'get_product.php?wh_invoice=1&for_report=1&warehouse_id='+jQuery("#warehouse_id").val(),
				selectFirst:false
        });
	}
    
    function check_warehouse()
    {
        if(!jQuery("#warehouse_id").val())
        {
            alert('You must choose warehouse');
            jQuery("#warehouse_id").focus();
            return;
        }
    }

</script>