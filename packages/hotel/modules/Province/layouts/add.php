<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_province')); //System::debug($_REQUEST['mi_province']);?>

<span style="display:none">
	<span id="mi_province_sample">
		<div id="input_group_#xxxx#" style="text-align:left;display:block;">
			
            <input name="mi_province[#xxxx#][id]" type="hidden" id="id_#xxxx#" value=""/>
			<span class="multi-input">
                <input  name="mi_province[#xxxx#][code]" style="width:100px;text-transform:uppercase;" type="text" id="code_#xxxx#" class="input_code" tabindex="-1"/>
            </span>
			
            <span class="multi-input">
                <input  name="mi_province[#xxxx#][name]" style="width:300px;" type="text" id="name_#xxxx#" tabindex="-1"/>
            </span>
            
            <span class="multi-input" style="margin-top: 4px;">
                <img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_province','#xxxx#','group_');" style="cursor:pointer;"/>
            </span>
            <br clear="all" />
        </div>
	</span>
</span>
<div align="center">
<table cellspacing="0" width="100%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[|title|]]</td>
					<td width="" align="right"><a class="button-medium-save" onClick="AddProvinceForm.submit();">[[.save.]]</a></td>
					<td width="" align="right"><a class="button-medium-back" onClick="history.go(-1)">[[.back.]]</a></td>                    
                </tr>
            </table>
		</td>
	</tr>
	
    <tr valign="top">
    	<td>
        	<form name="AddProvinceForm" method="post" >
                
                
                
                <table width="100%" cellpadding="5">
                    <?php 
                    if(Form::$current->is_error())
                	{
                	?>
                    <tr valign="top">
                        <td bgcolor="#C8E1C3"><?php echo Form::$current->error_messages();?></td>
                	</tr>
                	<?php
                	}
                	?>
                    
                	<tr valign="top">
                		<td>
                    		<fieldset>
                    			<legend class="title">[[.province.]]</legend>
                    				<span id="mi_province_all_elems" style="text-align:left;">
                                        <span class="multi-input-header" style="width:100px;float:left;">[[.code.]]</span>
                                        <span class="multi-input-header" style="width:300px;float:left;">[[.name.]]</span>
                                        <br clear="all" />
                    				</span>
                                    <?php if(Url::get('cmd')=='add'){?>
                    				<input type="button" value="[[.add_province.]]" onclick="mi_add_new_row('mi_province');" style="width:auto;"/>
                                    <?php }?>
                            </fieldset>	
                		</td>
                    </tr>
            	</table>
        	</form>
    	</td>
    </tr>
</table>
</div>
<script>
mi_init_rows('mi_province',<?php echo isset($_REQUEST['mi_province'])?String::array2js($_REQUEST['mi_province']):'{}';?>);
</script>