<div class="product-supplier-bound">
<?php if(Form::$current->is_error()){?>
<div>
    <br/>
    <?php echo Form::$current->error_messages();?>
</div>
<?php }?>
<form name="SplitKaraokeInvoiceForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td class="form-title">[[.list_folio.]]</td>
            <td width="1%" nowrap="nowrap" align="right" nowrap="nowrap">
                <?php if(User::can_add(false,ANY_CATEGORY)){?><!--<input name="print_group_vat" type="submit" id="print_group_vat" class="button-medium-add" value="  [[.print_group_vat.]]  "/>--><?php }?>
                <?php if(User::can_view(false,ANY_CATEGORY)){?><a href="<?php echo Url::build('vat_bill',array('department'=>'RECEPTION'));?>"  class="button-medium-add" target="_blank">[[.combine_invoice.]]</a><?php }?>                
            </td>
        </tr>
    </table>
    
	<div class="content">
    	<div class="search-box">
            <a name="top_anchor"></a>
            <fieldset>
            	<legend class="title">[[.search.]]</legend>
                
                <table border="0" cellpadding="3" cellspacing="0">
						<tr>
    						<td align="right" nowrap style="font-weight:bold">[[.select_karaoke.]]</td>
    						<td>:</td>
    						<td nowrap>
    							<select  name="karaoke_id" id="karaoke_id" style="width:120px;" onchange="SplitKaraokeInvoiceForm.submit();">
                                		<option value="0">---All---</option>
                                	<!--LIST:karaokes-->
                                    	<option value="[[|karaokes.id|]]">[[|karaokes.name|]]</option>
                                    <!--/LIST:karaokes-->
                                </select>
    						</td>
    						<td align="right" nowrap style="font-weight:bold">[[.date.]]</td>
    						<td>:</td>
    						<td nowrap>
    							<input name="in_date" type="text" id="in_date" style="width:70px"/>
    						</td>
    						<td align="right" nowrap style="font-weight:bold">[[.invoice_id.]]</td>
    						<td>:</td>
    						<td nowrap>
    							<input name="invoice_id" type="text" id="invoice_id" style="width:70px"/>
    						</td>
    						<td><input name="search" type="submit" value="  [[.search.]]  "/></td>
						</tr>
				</table>
            </fieldset>
        </div>
        
		<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
            <tr bgcolor="#F1F1F1">
                <th width="1%" rowspan="1"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="10px" rowspan="1">[[.stt.]]</th>
                <th width="50px" rowspan="1">[[.id.]]</th>
                <th width="50px" align="center" rowspan="1">[[.code.]]</th>
                <th width="200px" align="center" rowspan="1">[[.table_name.]]</th>
                <th width="90px" align="center" rowspan="1">[[.date.]]</th>
                <th width="90px" align="center" rowspan="1">[[.total.]]</th>
                <th width="40px" align="center" rowspan="1">[[.split_type.]]</th>
            </tr>
            <!--LIST:items-->
            <tr class="[[|items.row_class|]]" <?php Draw::hover('#E2F1DF');?>>  
                <td width="1%">
                    <input id="selected_ids" name="selected_ids[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"/>
                </td>
                <td style="cursor:pointer;" align="center">[[|items.stt|]]</td>
                <td style="cursor:pointer;" align="center">[[|items.id|]]</td>
                <td style="cursor:pointer;" align="center">
                    <a href="<?php echo Url::build('karaoke_touch',array('cmd'=>'edit','id'=>[[=items.id=]]));?>" target="_blank">[[|items.code|]]</a>
                </td>
                <td style="cursor:pointer;">[[|items.table_name|]] </td>
                <td style="cursor:pointer;" align="center"><?php echo date('d/m/Y',[[=items.arrival_time=]]);?></td>
                <td style="cursor:pointer;" align="right">
                <?php echo System::display_number([[=items.total=]]); ?>
                </td> 
                <td style="cursor:pointer;" align="right"><select name="split_type" id="split_type" style="width:90px;">[[|split_type_options|]]</select></td>
                <td style="cursor:pointer;" align="center">
                    <?php if(User::can_add(false,ANY_CATEGORY)){?>
                    
                    <?php }?>
                </td>
            </tr>
            <!--/LIST:items-->			
		</table>
        <div class="paging">[[|paging|]]</div>
        
        <table width="100%">
            <tr>
    			<td width="100%">
    				[[.select.]]:&nbsp;
    				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListFolio,'ManagePortal',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
    				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListFolio,'ManagePortal',false,'#FFFFEC','white');">[[.select_none.]]</a>
    				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListFolio,'ManagePortal',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
    			</td>
    			<td>
    				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"/></a>
    			</td>
			</tr>
        </table>
        <br />
        <br />
        <br />
	</div>
	<input name="act" type="hidden" value=""/>
    <input name="cmd" type="hidden" value=""/>
</form>	
</div>

<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
    jQuery("#in_date").datepicker();
	jQuery("#all_item_check_box").click(function(){
		var check = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
	var karaoke_id = <?php echo (Session::get('karaoke_id')?Session::get('karaoke_id'):0);?>;
	if(karaoke_id>0){
		jQuery('#karaoke_id').val(karaoke_id);	
	}
</script>

<style type="text/css">
a:visited{color:#003399}
</style>