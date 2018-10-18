<?php 
$title = Portal::language('product_list');
System::set_page_title(HOTEL_NAME.' - '.Portal::language('product_list'));
?>
<form name="RevenExpenListForm" enctype="multipart/form-data" method="post">
    <table width="100%" cellspacing="0" cellpadding="0">
    	<tr valign="top">
    		<td align="left">
                <table cellpadding="0" cellspacing="0" width="100%" border="0" class="table-bound">
                    <tr height="40">
                        <td class="form-title" width="25%">[[.list.]] <?php echo [[=type=]]==1?"thu":'chi'; ?> </td><!--([[.quantity.]]: [[|total|]])-->
                        <td align="center" >
                            <!--<input type="submit" value="Thu" id="thu" <?php if([[=type=]]==1) echo 'style="background-color: red;"'; else echo 'style="background-color: gray;"'; ?> onclick="jQuery('#type').val(1);" />
                            <input type="submit" value="Chi" id="chi" <?php if([[=type=]]==-1) echo 'style="background-color: red;"'; else echo 'style="background-color: gray;"'; ?> onclick="jQuery('#type').val(-1);" />-->
                            <!--
                            <input type="button" value="Thu" id="thu" <?php if([[=type=]]==1) echo 'style="background-color: red;"'; else echo 'style="background-color: gray;"'; ?> onclick="var url = '<?php echo Url::build_current();?>'+'&type=1';window.location.href = url;" />
                            <input type="button" value="Chi" id="chi" <?php if([[=type=]]==-1) echo 'style="background-color: red;"'; else echo 'style="background-color: gray;"'; ?> onclick="var url = '<?php echo Url::build_current();?>'+'&type=-1';window.location.href = url;" />
                             -->
                        </td>
    					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add',"type"=>Url::get('type')));?>"  class="button-medium-add">[[.add.]]</a></td><?php }?>
       					<?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%"><input type="button" value="[[.edit.]]" onclick="openw('edit')"  class="button-medium-edit" /></td><?php }?>
                        <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><input type="button" value="[[.delete.]]" onclick="openw('delete')"  class="button-medium-delete" /></td><?php }?>
                    </tr>
                </table>
    		</td>
    	</tr>
        <tr valign="top">
    		<td width="100%">
    			<table border="0" cellspacing="0" width="100%">
        			<tr>
        				<td width="100%">
        					<fieldset>
                                <legend class="title">[[.search_options.]]</legend>
                                <table border="0" cellpadding="3" cellspacing="0" width="100%" >
            						<tr width="100%" >
                                        <td align="center" >
                                            <strong>[[.from_date.]]:&nbsp;</strong><input name="from_date" type="text" id="from_date" style="width:80px" onchange="changevalue();"/>&nbsp;&nbsp;
                                            <strong>[[.to_date.]]:&nbsp;</strong><input name="to_date" type="text" id="to_date" style="width:80px" onchange="changefromday();"/>&nbsp;&nbsp;
                                            <strong>[[.code.]]:&nbsp;</strong><input name="code" type="text" id="code" style="width:50px"/>&nbsp;&nbsp;
                    						<strong>[[.item.]]:&nbsp;</strong>
                                            <select name="item_id" id="item_id" style="width:150px;">
                                                <option value="">[[.all.]]</option>
                                                [[|item_id_options|]]
                                            </select>&nbsp;&nbsp;
                                            <script>
          							               jQuery('#item_id').val('<?php echo URL::get('item_id');?>');
                							</script>
                    						<strong>[[.group.]]:&nbsp;</strong>
                                            <select  name="group_id" id="group_id" style="width:100px;">
                                                <option value="">[[.all.]]</option>
                                                [[|group_id_options|]]
                                            </select>&nbsp;&nbsp;
                                            <script>
          							               jQuery('#group_id').val('<?php echo URL::get('group_id');?>');
                							</script>
                    						<strong>[[.member.]]:&nbsp;</strong>
                                            <select  name="member_id" id="member_id" style="width:100px;">
                                                <option value="">[[.all.]]</option>[[|user_id_options|]]
                                            </select>&nbsp;&nbsp;
                                            <script>
          							               jQuery('#member_id').val('<?php echo URL::get('member_id');?>');
                							</script>
                    						<input name="search" type="submit" value="  [[.search.]]  "/>
                                        </td>
            						</tr>
    					       </table>
                           </fieldset>
                           <br />
                           <table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
        						<tr class="table-header">
                                    <th width="22px" title="[[.check_all.]]">
                                        <input type="checkbox" value="" id="Product_check" onclick="checkall(this.checked);"/>
                                    </th>
                                    
                                    <th nowrap align="center" width="40px" >[[.code.]]	</th>
                                    
                                    <th nowrap align="center" width="100px" >[[.amount.]]</th>
                                    
                                    <th nowrap align="center" width="40px" >[[.unit.]]</th>
                                    
                                    <th nowrap align="center" width="70px" >[[.time.]]</th>
                                    
                                    <th align="center" width="160px" nowrap="nowrap"> [[.item.]] </th>
                                    
                                    <th align="center" width="120px" nowrap="nowrap"> [[.group.]] </th>
                                    
                                    <th align="center" width="270px" nowrap="nowrap"> [[.note.]] </th>
                                    
                                    <th align="center" width="100" nowrap="nowrap"> [[.create.]] </th>
                                    
            				        <th nowrap align="left" width="100px" >[[.member.]]</th>
                                    
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>	
                                    <th width="1%">&nbsp;</th>
                                    <?php }
                                    
                                    if(User::can_delete(false,ANY_CATEGORY)){?>
                                    <th width="1%">&nbsp;</th>
                                    <?php }?>
                                </tr>
                                <!--LIST:items-->
                                <tr <?php echo ([[=items.i=]]%2==0)?'class="row-odd"':'class="row-even"';?>>
                                    <td align="center" >
                                        <input type="checkbox" value="1" <?php echo 'id="Product_check_'.[[=items.id=]].'"';?> <?php echo 'name="Product_check_'.[[=items.id=]].'"';?> onclick="get_ids();" />
                                    </td>
                                    <td align="center" >
                                        [[|items.id|]]
                                    </td>
                                    <td align="right" style="padding-right: 5px;" >
                                        <?php echo System::display_number([[=items.amount=]]); ?>
                                    </td>
                                    <td align="center" >
                                        [[|items.currency_id|]]
                                    </td>
                                    <td align="center" >
                                        [[|items.date_cf|]]
                                    </td>
                                    <td align="center" >
                                        [[|items.item_name|]]
                                    </td>
                                    <td align="center" >
                                        [[|items.group_name|]]
                                    </td>
                                    <td align="center" >
                                        [[|items.note|]]
                                    </td>
                                    <td align="center" >
                                        [[|items.input_name|]]
                                    </td>
                                    <td align="center" >
                                        [[|items.member_name|]]
                                    </td>
                                    <!--IF:cond(User::can_edit(false,ANY_CATEGORY))-->
        							<td nowrap align="center" >
                                        <a href="<?php echo Url::build_current(array()+array('cmd'=>'edit','ids'=>[[=items.id=]],'type'=>[[=type=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]"></a>
                                    </td>
        							<!--/IF:cond-->
        							<!--IF:cond(User::can_delete(false,ANY_CATEGORY))-->
        							<td nowrap align="center" >
                                        <a href="<?php echo Url::build_current(array('cmd'=>'delete','ids'=>[[=items.id=]],'type'=>[[=type=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]"></a>							
                                    </td>
        							<!--/IF:cond-->
                                </tr>
                                <!--/LIST:items-->
    				        </table>
                        </td>
                    </tr>
                </table>
            </td>
   		</tr>
        <tr>
        </tr>
    </table>
    <input name="cmd" type="hidden" value="" />
    <input name="ids" id="ids" type="hidden" value="" />
    <input name="type" id="type" type="hidden" value="[[|type|]]" />
</form>
[[|paging|]]


<script>
    jQuery(document).ready(function(){
        jQuery("#from_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
        jQuery("#to_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
    });
    function checkall(val)
    {
        var inputs = jQuery('input:checkbox');
        for (var i=0;i<inputs.length;i++)
        { 
            if(inputs[i].id.indexOf('Product_check_')==0)
            {
                //console.log(inputs[i].id);
                if(val)
                {
                    //console.log(1);
                    inputs[i].checked = 1;
                }
                else
                {
                    inputs[i].checked = 0;
                    //console.log(0);
                }
            }
        }
        
        get_ids();
    }
    
    function get_ids()
    {
        var inputs = jQuery('input:checkbox:checked');
        var strids = "";
        for (var i=0;i<inputs.length;i++)
        { 
            if(inputs[i].id.indexOf('Product_check_')==0)
            {
                strids +=","+inputs[i].id.replace("Product_check_","");
            }
        }
        strids = strids.replace(",","");
        jQuery('#ids').val(strids);
        console.log(jQuery('#ids').val());
    }
    
    function openw(cmd)
    {
        var url = '?page=reven_expen';
		
		url += '&cmd='+cmd;
		var ids = jQuery('#ids').val();
        url += '&ids='+ids;
        var type = '[[|type|]]';
        url += '&type='+type;
		//window.open(url);
        location.href = url;
        console.log(url);
    }

    function changevalue(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('to_date').value =$('from_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('to_date').value =$('from_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('to_date').value =$('from_date').value;
                }
            }
        }
    }
    function changefromday(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('from_date').value= $('to_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('from_date').value = $('to_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('from_date').value =$('to_date').value;
                }
            }
        }
    }
</script>