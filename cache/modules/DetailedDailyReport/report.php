<!---------REPORT----------->

<table cellpadding="5" cellspacing="0" width="300px" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:12px;">
	<tr bgcolor="#EFEFEF">
        <th class="report_table_header" width="60px"><?php echo Portal::language('type');?></th>
		<th class="report_table_header" width="40px"><?php echo Portal::language('quantity');?></th>
		<th class="report_table_header" width="140px"><?php echo Portal::language('total_before_tax');?></th>
	</tr>
     <!--class="report_table_column"-->
	<tr bgcolor="white">
        <td align="center"><?php echo Portal::language('food');?></td>    
		<td align="center" ><?php echo $this->map['summary']['FOOD']['quantity']; ?></td>
        <td align="right" ><?php echo System::display_number($this->map['summary']['FOOD']['price']); ?></td>        
	</tr>
    
    
    <tr bgcolor="white">
        <td align="center" ><?php echo Portal::language('drink');?></td>  
		<td align="center" ><?php echo $this->map['summary']['DRINK']['quantity']; ?></td>
        <td align="right" ><?php echo System::display_number($this->map['summary']['DRINK']['price']); ?></td>        
	</tr>
     <tr bgcolor="white">
        <td align="center" ><?php echo Portal::language('Service');?></td>  
		<td align="center" ><?php echo $this->map['summary']['SERVICE']['quantity']; ?></td>
        <td align="right" ><?php echo System::display_number($this->map['summary']['SERVICE']['price']); ?></td>        
	</tr>
    
    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong><?php echo Portal::language('total');?></strong></td>  
        <td align="right" ><?php echo System::display_number($this->map['summary']['DRINK']['price']+$this->map['summary']['FOOD']['price']+$this->map['summary']['SERVICE']['price']); ?></td>        
	</tr>
    
    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong><?php echo Portal::language('charge');?></strong></td>  
        <td align="right" ><?php echo System::display_number($this->map['summary']['DRINK']['charge']+$this->map['summary']['FOOD']['charge']+$this->map['summary']['SERVICE']['charge']); ?></td>        
	</tr>
    
    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong><?php echo Portal::language('tax');?></strong></td>  
        <td align="right" ><?php echo System::display_number($this->map['summary']['DRINK']['tax']+$this->map['summary']['FOOD']['tax']+$this->map['summary']['SERVICE']['tax']); ?></td>        
	</tr>

    <tr bgcolor="white">
        <td align="center"  colspan="2" ><strong><?php echo Portal::language('total_after_tax');?></strong></td>  
        <td align="right" ><?php echo System::display_number_report($this->map['summary']['DRINK']['total_after_tax']+$this->map['summary']['FOOD']['total_after_tax']+$this->map['summary']['SERVICE']['total_after_tax']); ?></td>        
	</tr>
    
</table>

<br /><br />

<table cellpadding="5" cellspacing="0" width="300px" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
		<th class="report_table_header" width="20px"><?php echo Portal::language('stt');?></th>
		<th class="report_table_header" width="160px"><?php echo Portal::language('product_name');?></th>
        <th class="report_table_header" width="20px"><?php echo Portal::language('quantity');?></th>
        <th class="report_table_header" width="100px"><?php echo Portal::language('total_after_tax');?></th>
	</tr>
    
    <?php $category_name = '' ?>
	<?php if(isset($this->map['summary']) and is_array($this->map['summary'])){ foreach($this->map['summary'] as $key1=>&$item1){if($key1!='current'){$this->map['summary']['current'] = &$item1;?>
    <?php
        if($category_name != $this->map['summary']['current']['type'] ) 
        {
            $category_name = $this->map['summary']['current']['type'];
    ?>
	<tr bgcolor="white">
		<td align="left"  colspan="4" style="text-indent: 10px;"><em><strong><?php echo Portal::language('category');?>: <?php echo $this->map['summary']['current']['type'];?></strong></em></td>        
	</tr>
    <?php
        }
    ?>
    <?php $stt = 1; ?>
    <?php if(isset($this->map['summary']['current']['child']) and is_array($this->map['summary']['current']['child'])){ foreach($this->map['summary']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['summary']['current']['child']['current'] = &$item2;?>
	<tr bgcolor="white">
		<td align="center" ><?php echo $stt++;?></td>
        <td align="left" ><?php echo $this->map['summary']['current']['child']['current']['product_name'];?></td>
        <td align="center" ><?php echo $this->map['summary']['current']['child']['current']['quantity'];?></td>
        <td align="right"  ><?php echo System::display_number_report(round($this->map['summary']['current']['child']['current']['total_after_tax']));?></td>        
	</tr>
    <?php }}unset($this->map['summary']['current']['child']['current']);} ?>
    
	<?php }}unset($this->map['summary']['current']);} ?>
</table>
<br />