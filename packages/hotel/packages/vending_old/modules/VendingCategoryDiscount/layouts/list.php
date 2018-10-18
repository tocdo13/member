<form name="CategoryDiscountForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="75%" class="form-title">[[.vending_category_discount.]]</td>
        </tr>
    </table>
	<?php if(User::can_edit(false,ANY_CATEGORY)){?>
		<!--IF::cond(User::can_admin(false,ANY_CATEGORY))-->
        <div style="width: 400px; float:right; margin:10px 10px 10px auto;height:28px;">
        <a href="#"  class="button-medium-save" onclick="CategoryDiscountForm.submit()" style="float:right; margin-right:40px;">[[.Update.]]</a> 
        </div>
        <!--/IF::cond-->
    <?php }?>
	<table cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="<?php echo Portal::get_setting('crud_list_item_frame_color','#C3C3C3');?>">
		<thead>
			<tr class="table-header">
				<th nowrap align="left">
				[[.name.]]</th>
				<th nowrap align="left">
				[[.code.]]
				</th>
                <th>[[.discount_percent.]]</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0;?>
			<!--LIST:items-->
			<?php $onclick = 'location=\''.URL::build_current().'&cmd=edit&id='.urlencode([[=items.id=]]).'\';"';?>
			<tr valign="middle" style="cursor:pointer;" id="Category_tr_[[|items.id|]]" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
				<td nowrap align="left" onclick="window.location='<?php echo Url::build_current().'&cmd=edit&id='.[[=items.id=]];?>'">
				[[|items.indent|]]
				[[|items.indent_image|]]
				<span class="page_indent">&nbsp;</span>
				[[|items.name|]]</td>
				<td nowrap align="left" <?php echo $onclick;?>>
				[[|items.code|]]
				</td>
                <td><input name="discount_[[|items.id|]]" type="text" lang="[[|items.structure_id|]]" alt="[[|items.level|]]" id="discount_[[|items.id|]]" value="[[|items.discount_percent|]]" onKeyUp="updatePercent([[|items.id|]],this);" ></td>
			</tr>
			<!--/LIST:items-->
		</tbody>
	</table>		
	<input type="hidden" name="cmd" value="" id="cmd"/>
	<input type="hidden" name="confirm" value="1" id="confirm"/>
</form>
<script>
	var ID_BASE = <?php echo ID_BASE;?>;
	var ID_MAX_LEVEL = <?php echo ID_MAX_LEVEL;?>;
	categories = [[|categories_js|]];
	function updatePercent(id,obj){
		valu = obj.value;
		if(is_numeric(obj.value) && to_numeric(obj.value)<100){
			var id_next = to_numeric(obj.lang) + Math.pow(ID_BASE,(ID_MAX_LEVEL - obj.alt));
			for(var i in categories){
				if(obj.lang<categories[i]['structure_id'] && categories[i]['structure_id']<id_next){
					jQuery('#discount_'+i).val(obj.value);	
				}
			}
		}else{
			jQuery('#discount_'+id).val(valu.substr(0,valu.length-1));	
		}
	}
	function ChangeBar(){
		var bar_id = '<?php if(Url::get('bar_id')) {echo Url::get('bar_id');} else {echo '';}?>';	
		jQuery('#confirm').val(0);
		CategoryDiscountForm.submit();
	}
</script>