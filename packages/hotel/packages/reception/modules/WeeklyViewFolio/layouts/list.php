<form name="ListFolio" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td class="" width="50%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.list_folio.]]</td>
            <td width="50%" nowrap="nowrap" align="right" style="padding-right: 30px;">
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input class="w3-btn w3-pink" type="button" onclick="print_vat();" value="  [[.print_group_vat.]]  "/><?php }?>             
            </td>
        </tr>
    </table>
	<fieldset>
    	<legend class="title">[[.search.]]</legend>
        
        <table border="0" cellpadding="3" cellspacing="0">
				<tr style="font-weight: normal !important;">
                    
                    <!-- trung add search customer -->
                    <td align="right" nowrap >[[.customer_name_search.]]</td>
					<td>:</td>
                    <td nowrap>
                    <input name="customer_name" type="text" id="customer_name" onfocus="Autocomplete();" autocomplete="off" style="width:150px; height: 24px;"/>
                    <input name="customer_id" type="text" id="customer_id" class="hidden" />
					</td>
                    <!--end trung add search customer -->
                    <td align="right" nowrap >[[.recode_id.]]</td>
					<td>:</td>
                    <td nowrap>
						<input name="recode_id" type="text" id="recode_id" style="width:70px; height: 24px;"/>
					</td>
					<td align="right" nowrap >[[.folio_id.]]</td>
					<td>:</td>
					<td nowrap>
						<input name="folio_id" type="text" id="folio_id" style="width:70px; height: 24px;"/>
					</td>
					<td align="right" nowrap >[[.from_date.]]</td>
					<td>:</td>
					<td nowrap>
						<input name="from_date" type="text" id="from_date" onchange="ValidateTime();" readonly="" style="width:70px; height: 24px;"/>
					</td>
					<td align="right" nowrap >[[.to_date.]]</td>
					<td>:</td>
					<td nowrap>
						<input name="to_date" type="text" id="to_date" onchange="ValidateTime();" readonly="" style="width:70px; height: 24px;"/>
					</td>
                    <td>
                        [[.status.]]:<select name="status" id="status" style=" height: 24px;"></select>
                    </td>
                    <td>
                        [[.rank.]]:<select name="order_by" id="order_by" onchange="ListFolio.submit();" style=" height: 24px;"></select>
                    </td>
					<td><input name="search" type="button" value="  [[.search.]]  " onclick="fun_check();" style=" height: 24px;"/></td>
				</tr>
		</table>
    </fieldset>
</div>

<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
    <tr style="background: #EEEEEE; height: 25px; text-align: center; text-transform: uppercase;;">
        <th><input type="checkbox" id="checkboxall"/></th>
        <th>[[.stt.]]</th>
        <th>[[.recode.]]</th>
        <th>[[.info.]]</th>
        <th>[[.folio_id.]]</th>
        <th>[[.room.]]</th>
        <th>[[.date.]]</th>
        <th style="text-align: right;" >[[.total.]]: [[|total|]]</th>
        <th style="text-align: right;">[[.total_remain_vat.]]: [[|total_remain|]]</th>
        <th>[[.VAT.]]</th>
    </tr>
    <!--LIST:items-->
    <tr style="text-align: center; font-weight: normal !important;">
        <th style="width: 30px;font-weight: normal !important;"><input id="item_[[|items.id|]]" type="checkbox" class="checkitem" value="[[|items.id|]]" /></th>
        <th style="font-weight: normal !important;">[[|items.stt|]]</th>
        <th style="font-weight: normal !important;"><a target="_blank" href="?page=reservation&cmd=edit&id=[[|items.recode|]]" id="[[|items.recode|]]" onclick="thenao(this)" >[[|items.recode|]]  </a></th>
        <th style="text-align: left;font-weight: normal !important;">[[.customer_name.]]: [[|items.customer_name|]] <br /> [[.guest_name.]] [[|items.traveller_name|]]</th>
        <th style="font-weight: normal !important;"><a target="_blank" href="[[|items.href|]]">[[|items.code|]]</a></th>
        <th style="font-weight: normal !important;">[[|items.room_name|]]</th>
        <th style="font-weight: normal !important;">[[|items.time|]]</th>
        <th style="text-align: right;font-weight: normal !important;">[[|items.total|]]</th>
        <th style="text-align: right;font-weight: normal !important;">[[|items.total_remain|]]</th>
        <th><input class="w3-btn w3-pink" type="button" onclick="print_vat('[[|items.id|]]');" value="[[.print_vat.]]"/></th>
    </tr>
    <!--/LIST:items-->			
</table>
</form>	
<script type="text/javascript">
var array_order = [];



//jQuery('#order_by').on('change', function() {
//  var value = jQuery(this).val();
//  console.log(value);
//   
//});

    function Autocomplete()
    {
        jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast.php?customer=1',
             onItemSelect: function(item){
                document.getElementById('customer_id').value = item.data[0];
                        
            }
        }) ;
    }

    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    jQuery("#checkboxall").click(function(){
        if(document.getElementById('checkboxall').checked==true) {
            jQuery(".checkitem").attr('checked',true);
        } else {
            jQuery(".checkitem").attr('checked',false);
        }
    });
    function print_vat($id=false){
        if(!$id) {
            $list_id = '';
            jQuery(".checkitem").each(function(){
                if(document.getElementById(this.id).checked==true) {
                    if($list_id=='')
                        $list_id = this.value;
                    else
                        $list_id += ','+this.value;
                }
            });
            if($list_id=='')
                alert('[[.are_you_select_folio_code.]]');
            else
                window.location.href='?page=vat_bill&cmd=add&type=FOLIO&invoice_id='+$list_id+'';
        }
        else {
            window.location.href='?page=vat_bill&cmd=add&type=FOLIO&invoice_id='+$id+'';
        }
        return false;
    }
    
    function fun_check(){
        ListFolio.submit();
    }
    
    function ValidateTime() {
        
    }
    
</script>
