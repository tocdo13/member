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
                    <img src="<?php echo Portal::template('core');?>/images/buttons/delete_button.gif"/><br />[[.Delete.]]
                </a>
            </td>
            
            <td class="form_title_button">
                <a href="<?php echo URL::build_current(array());?>">
                    <img src="<?php echo Portal::template('core');?>/images/buttons/go_back_button.gif" style="text-align:center"/><br />[[.back.]]
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
        <!--LIST:items-->
        <tr>
        	<td class="form_detail_label">[[.id.]]</td>
        	<td>:</td>
        	<td class="form_detail_value">[[|items.id|]]</td>
        </tr>
        <tr>
        	<td class="form_detail_label">[[.code.]]</td>
        	<td>:</td>
        	<td class="form_detail_value">[[|items.code|]]</td>
        </tr>
        <tr>
        	<td class="form_detail_label">[[.name.]](VN)</td>
        	<td>:</td>
        	<td class="form_detail_value">[[|items.name_1|]]</td>
        </tr>
        <tr>
        	<td class="form_detail_label">[[.name.]](EN)</td>
        	<td>:</td>
        	<td class="form_detail_value">[[|items.name_2|]]</td>
        </tr>
        <!--/LIST:items-->
        </table>
    </div>
</div>
