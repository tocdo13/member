<!-- saved from url=(0014)about:internet -->
<form method="post" name="EditMinibarImportForm">
<div style="background-color:#FFFFFF;width:100%;">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr>
    		<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('number_of_items_needed_for_minibar');?></td>
    		<td align="right"><input name="update" type="submit" value="<?php echo Portal::language('import_minibar');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
            <input name="print" type="submit" value="<?php echo Portal::language('print_import_voucher');?>" class="w3-btn w3-gray" style="text-transform: uppercase;"/></td>        
    	</tr>
    </table>
    <div style="text-align:left;background-color:#ECE9D8;width:100%;text-indent:20px;font-weight:bold;color:#FF0000;vertical-align:middle;">
        <?php echo $this->map['error_message'];?>
    </div>
    
    <table border="1" cellspacing="0" cellpadding="5" align="left" bordercolor="#C6E2FF" bgcolor="#FFFFFF" style="border-collapse:collapse;">
        <tr class="w3-light-gray" style="text-transform: uppercase;">
            <td rowspan="3"><input  name="minibar" id="minibar" value="minibar" onclick="check_all();"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('minibar'));?>"></td>  
            <td rowspan="3"><?php echo Portal::language('Minibar_name');?></td>
            <td align="center" colspan="<?php echo sizeof($this->map['minibar_products_sample'])*2;?>"><?php echo Portal::language('Minibar_product_list');?></td>
        </tr>
        <tr>
            <?php if(isset($this->map['minibar_products_sample']) and is_array($this->map['minibar_products_sample'])){ foreach($this->map['minibar_products_sample'] as $key1=>&$item1){if($key1!='current'){$this->map['minibar_products_sample']['current'] = &$item1;?>
            <td align="center" colspan="2"><strong><?php echo $this->map['minibar_products_sample']['current']['name'];?></strong></td>
            <?php }}unset($this->map['minibar_products_sample']['current']);} ?>  
        </tr>
        <tr>
            <?php if(isset($this->map['minibar_products_sample']) and is_array($this->map['minibar_products_sample'])){ foreach($this->map['minibar_products_sample'] as $key2=>&$item2){if($key2!='current'){$this->map['minibar_products_sample']['current'] = &$item2;?>
            <td align="center"><?php echo Portal::language('norm_quantity');?></td>
            <td align="center"><?php echo Portal::language('used');?></td>    
            <?php }}unset($this->map['minibar_products_sample']['current']);} ?>      
        </tr>  
        <?php if(isset($this->map['minibars']) and is_array($this->map['minibars'])){ foreach($this->map['minibars'] as $key3=>&$item3){if($key3!='current'){$this->map['minibars']['current'] = &$item3;?>
        <tr>
            <td><input name="check_<?php echo $this->map['minibars']['current']['id'];?>" id="check_<?php echo $this->map['minibars']['current']['id'];?>" type="checkbox" value="<?php echo $this->map['minibars']['current']['id'];?>"/></td>
            <td>Room <?php echo $this->map['minibars']['current']['name'];?></td>
            <?php if(isset($this->map['minibars']['current']['products']) and is_array($this->map['minibars']['current']['products'])){ foreach($this->map['minibars']['current']['products'] as $key4=>&$item4){if($key4!='current'){$this->map['minibars']['current']['products']['current'] = &$item4;?>
            <td align="center" style="color:blue;"><?php echo $this->map['minibars']['current']['products']['current']['import_quantity'];?></td>
            <td align="center" style="color:red;"><?php 
				if((isset($this->map['minibars']['current']['products']['current']['quantity'])))
				{?><?php echo $this->map['minibars']['current']['products']['current']['quantity'];?>
				<?php
				}
				?></td>    
            <?php }}unset($this->map['minibars']['current']['products']['current']);} ?>
        </tr>
        <?php }}unset($this->map['minibars']['current']);} ?>
    </table>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
	function check_all()
	{
		 if($('minibar').checked==true)
		 {
		 	<?php if(isset($this->map['minibars']) and is_array($this->map['minibars'])){ foreach($this->map['minibars'] as $key5=>&$item5){if($key5!='current'){$this->map['minibars']['current'] = &$item5;?>
				$('check_<?php echo $this->map['minibars']['current']['id'];?>').checked=true;
			<?php }}unset($this->map['minibars']['current']);} ?>
		 }
		 else
		 {
		 	<?php if(isset($this->map['minibars']) and is_array($this->map['minibars'])){ foreach($this->map['minibars'] as $key6=>&$item6){if($key6!='current'){$this->map['minibars']['current'] = &$item6;?>
				$('check_<?php echo $this->map['minibars']['current']['id'];?>').checked=false;
			<?php }}unset($this->map['minibars']['current']);} ?>
		 }
	}
</script>