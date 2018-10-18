<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<script type="text/javascript">
	product_arr = <?php echo String::array2js($this->map['product_arr']);?>;
</script>
<div class="product-report-bound" style="padding:20px;">
<form name="WarehouseImportReportOptionsForm" method="post" onsubmit="return checkDate();">
    <?php if(Form::$current->is_error()){?>
    <div>
    <br/>
    <?php echo Form::$current->error_messages();?>
    </div>
    <?php }?>
    
    <div id="product_toolbar">
        <div class="title">
            <div><?php echo $this->map['title'];?></div>
        </div>
    </div>
    <br />
	<div class="content">
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
            <td colspan="2" align="left" bgcolor="#EFEFEF"><strong><?php echo Portal::language('time_select');?></strong></td>
            </tr>
            <tr>
            <td align="right" width="30%"><?php echo Portal::language('date_from');?></td>
            <td><input  name="date_from" id="date_from" onchange="changevalue();" tabindex="1"/ type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>"></td>
            </tr>
            <tr>
            <td align="right"><?php echo Portal::language('date_to');?></td>
            <td><input  name="date_to" id="date_to" onchange="changefromday();" tabindex="2"/ type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"></td>
            </tr>
                
        </table>
        
        <br />
        
        <!--khong phai bao cao nha cc thi phai chon kho-->
        <?php 
				if((Url::get('page')!='warehouse_import_report' && Url::get('page')!='warehouse_export_supplier_report'))
				{?>
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong><?php echo Portal::language('warehouse');?> </strong></td>
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
        
				<?php
				}
				?>	
        <!--xuat chuyen kho-->
        <?php 
				if((Url::get('page')=='warehouse_export_report'))
				{?>
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong><?php echo Portal::language('to_warehouse');?> </strong></td>
            </tr>
            <tr>
                <td align="center"><select  name="warehouse_to_id" id="warehouse_to_id"><?php
					if(isset($this->map['warehouse_to_id_list']))
					{
						foreach($this->map['warehouse_to_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('warehouse_to_id',isset($this->map['warehouse_to_id'])?$this->map['warehouse_to_id']:''))
                    echo "<script>$('warehouse_to_id').value = \"".addslashes(URL::get('warehouse_to_id',isset($this->map['warehouse_to_id'])?$this->map['warehouse_to_id']:''))."\";</script>";
                    ?>
	</select></td>
            </tr>
        </table>
        
				<?php
				}
				?>
        <br />	 
        <!--bao cao nha cc thi  khong phai chon kho-->
        <?php 
				if((Url::get('page')=='warehouse_import_report'))
				{?>
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            
            <tr>
                <td align="right"><?php echo Portal::language('supplier');?>: </td>
                <!--<td align="left"><select  name="supplier_id" id="supplier_id"><?php
					if(isset($this->map['supplier_id_list']))
					{
						foreach($this->map['supplier_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('supplier_id',isset($this->map['supplier_id'])?$this->map['supplier_id']:''))
                    echo "<script>$('supplier_id').value = \"".addslashes(URL::get('supplier_id',isset($this->map['supplier_id'])?$this->map['supplier_id']:''))."\";</script>";
                    ?>
	</select></td>-->
                <td align="left">
                    <input  name="supplier_name" id="supplier_name" onfocus="Autocomplete_sp()" oninput="check_sp()" / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_name'));?>">
                    <input  name="supplier_id" id="supplier_id" style="display: none;" / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_id'));?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input name="warehouse_import" type="submit" value="<?php echo Portal::language('view_report');?>" tabindex="-1"/></td>
            </tr>
        </table>
        
				<?php
				}
				?>
        <br />
        <!--bao cao tra lai nha cc thi  khong phai chon kho-->
        <?php 
				if((Url::get('page')=='warehouse_export_supplier_report'))
				{?>
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            
            <tr>
                <td align="right"><?php echo Portal::language('supplier');?>: </td>
                <!--<td align="left"><select  name="supplier_id" id="supplier_id"><?php
					if(isset($this->map['supplier_id_list']))
					{
						foreach($this->map['supplier_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('supplier_id',isset($this->map['supplier_id'])?$this->map['supplier_id']:''))
                    echo "<script>$('supplier_id').value = \"".addslashes(URL::get('supplier_id',isset($this->map['supplier_id'])?$this->map['supplier_id']:''))."\";</script>";
                    ?>
	</select></td>-->
                <td align="left">
                    <input  name="supplier_name" id="supplier_name" onfocus="Autocomplete_sp()" oninput="check_sp()" / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_name'));?>">
                    <input  name="supplier_id" id="supplier_id" style="display: none;" / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_id'));?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input name="warehouse_export_supplier" type="submit" value="<?php echo Portal::language('view_report');?>" tabindex="-1"/></td>
            </tr>
        </table>
        
				<?php
				}
				?>
        <br />
        <!--xuat nhap ton-->
        <?php 
				if((Url::get('page')=='warehouse_report'))
				{?>
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong>B&Aacute;O C&Aacute;O NH&#7852;P XU&#7844;T T&#7890;N </strong></td>
            </tr>
            <tr>
                <td align="center">
                    <input  name="negative_number" id="negative_number" value="1" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('negative_number'));?>">
                    <label for="negative_number"><?php echo Portal::language('only_product_with_negative_number');?></label>
                </td>
            </tr>
            <tr>
                <td align="center"><input name="store_remain" type="submit" value="<?php echo Portal::language('view_report');?>" tabindex="-1"/></td>
            </tr>
        </table>
        
				<?php
				}
				?>
        <br />
        <!--the? kho-->
        <?php 
				if((Url::get('page')=='warehouse_book'))
				{?>
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td bgcolor="#EFEFEF"><strong>TH&#7866; KHO (S&#7892; KHO)</strong></td>
            </tr>
            <tr>
                <td align="center">
                <?php echo Portal::language('product');?>
                <input  name="code" id="code" tabindex="3" onfocus="check_warehouse();" onclick="my_autocomplete();" onkeyup="my_autocomplete();" autocomplete="OFF"/ type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>">
                <input name="store_card" type="submit" value="<?php echo Portal::language('view_report');?>" tabindex="-1" onclick="return check_code();"/>
            </td>
            </tr>
        </table>
        
				<?php
				}
				?>
        <br />
        <!--chuyen kho-->
        <?php 
				if((Url::get('page')=='warehouse_export_report'))
				{?>
        <table width="400" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
                <td align="center"><input name="warehouse_export" type="submit" value="<?php echo Portal::language('view_report');?>" tabindex="-1"/></td>
            </tr>
        </table>
        
				<?php
				}
				?>
        <br />
        <h3>&nbsp;</h3>
        <h3>&nbsp;</h3>
	</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	jQuery("#date_from").mask("99/99/9999");
	jQuery("#date_to").mask("99/99/9999");
	jQuery("#date_from").datepicker();
	jQuery("#date_to").datepicker();
    var supplier_arr = <?php echo String::array2js($this->map['suppliers']); ?>;	
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
	function my_autocomplete()
	{
		jQuery("#code").autocomplete({
                url: 'get_product.php?wh_invoice=2&warehouse_id='+jQuery("#warehouse_id").val(),
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
	function checkDate(){
		if(!($('date_from').value && $('date_to').value)){
			alert('<?php echo Portal::language('You_have_to_input_time');?>');
			return false;
		}
		if(!($('warehouse_id').value)){
			alert('<?php echo Portal::language('You_have_to_select_warehouse');?>');
			return false;
		}
	}
    function check_code(){
        if(jQuery("#code").val()=='')
        {
            alert("<?php echo Portal::language('you_must_choose_code_product');?>");
            return false;
        }
    }
    function check_sp(){
    if(jQuery('#supplier_name').val()=='')
    {
        jQuery('#supplier_id').val('');
    }
    }
    function Autocomplete_sp()
    {
        jQuery('#supplier_name').autocomplete({
            url:'get_customer1.php?supplier=1',
            onItemSelect:function(item){
            jQuery("#supplier_id").val(supplier_arr[item.data]['supplier_id']);
            
           }
        });
        
    }
</script>
