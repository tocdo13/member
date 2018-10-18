<?php 
$action = (URL::get('cmd')=='delete')?'delete':'detail';
System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_department'));
?>
<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />




<div class="form_bound">
    <table cellpadding="0" width="100%">
        <tr>
            <td class="form_title">
                <img src="<?php echo Portal::template('core');?>/images/buttons/<?php echo $action;?>_button.gif" align="absmiddle"/><?php echo Portal::language('delete_department');?>
            </td>
            
            <td class="form_title_button">
                <a href="<?php echo URL::build_current(array('cmd'=>'delete_id','id'));?>">
                    <img src="<?php echo Portal::template('core');?>/images/buttons/delete_button.gif"/><br /><?php echo Portal::language('Delete');?>
                </a>
            </td>
            
            <td class="form_title_button">
                <a href="<?php echo URL::build_current(array());?>">
                    <img src="<?php echo Portal::template('core');?>/images/buttons/go_back_button.gif" style="text-align:center"/><br /><?php echo Portal::language('back');?>
                </a>
            </td>
            
            <td class="form_title_button">
                <a  href="<?php echo URL::build('default');?>">
                    <img src="<?php echo Portal::template('core');?>/images/buttons/frontpage.gif"/><br />Trang ch&#7911;
                </a>
            </td>
        </tr>
    </table>
    
    
    
    
    
    
    
    <div class="form_content">
        <table cellspacing="0" width="100%">
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <tr>
        	<td class="form_detail_label"><?php echo Portal::language('id');?></td>
        	<td>:</td>
        	<td class="form_detail_value"><?php echo $this->map['items']['current']['id'];?></td>
        </tr>
        <tr>
        	<td class="form_detail_label"><?php echo Portal::language('code');?></td>
        	<td>:</td>
        	<td class="form_detail_value"><?php echo $this->map['items']['current']['code'];?></td>
        </tr>
        <tr>
        	<td class="form_detail_label"><?php echo Portal::language('name');?>(VN)</td>
        	<td>:</td>
        	<td class="form_detail_value"><?php echo $this->map['items']['current']['name_1'];?></td>
        </tr>
        <tr>
        	<td class="form_detail_label"><?php echo Portal::language('name');?>(EN)</td>
        	<td>:</td>
        	<td class="form_detail_value"><?php echo $this->map['items']['current']['name_2'];?></td>
        </tr>
        <?php }}unset($this->map['items']['current']);} ?>
        </table>
    </div>
</div>
