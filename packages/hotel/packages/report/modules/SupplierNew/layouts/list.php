<div>
<form name="ListSupplierForm" method="post" >
    <table id="header" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td><b style="font-size: 16px;">[[.list_supplier.]]</b></td>
            <td> 
                <a href="<?php echo URL::build_current(array('cmd'=>'add','group_id','action'));?>"  class="button-medium-add">[[.Add.]]</a>
                <a href="javascript:void(0)" class="button-medium-delete" onclick="delete_group();if(!confirm('[[.are_you_sure.]]')){return false};ListSupplierForm.cmd.value='group_delete';ListSupplierForm.submit();" >[[.delete.]]</a>
                <a href="?page=supplier_new&cmd=import"  class="button-medium-add">[[.add_import.]]</a>
            </td>
        </tr>
       
    </table>
    <div>
        <fieldset>
            <legend>[[.search.]]</legend>
            <input name="code_search" type="text" id="code" />
            <input name="supplier_name" type="text" id="supplier_name"/>
            <input name="search" type="submit" id="search" value="[[.search.]]" />
        </fieldset>
    </div>
    <table id="content" width="100%" cellpadding="0" cellspacing="0" border="1" >
        <tr>
            <td width="1%"><input name="all_checkbox" type="checkbox" id="all_checkbox" /></td>
            <td width="2%">stt</td>
            <td width="5%">[[.code.]]</td>
            <td width="25%">[[.product_name.]]</td>
            <td width="20%">[[.address.]]</td>
            <td width="40%">[[.contact_person_info.]]</td>
            <td width="1%">[[.edit.]]</td>
            <td width="1%">[[.delete.]]</td>
            
        </tr>
        <?php $i=1; ?>
        <!--LIST:items-->
        <tr>
            <td><input name="check" type="checkbox" id="[[|items.id|]]" class="check"/></td>
            <td><?php echo $i++ ?></td>
            <td>[[|items.code|]] </td>
            <td>[[|items.name|]] </td>
            <td>[[|items.address|]] </td>
            <td>
                &loz;<label>[[.contact_person_name.]]:</label><label>[[|items.contact_person_name|]]</label><br />
                &loz;<label>[[.contact_person_phone.]]:</label><label>[[|items.contact_person_phone|]]</label><br />
                &loz;<label>[[.contact_person_mobile.]]:</label><label>[[|items.contact_person_mobile|]]</label><br />
                &loz;<label>[[.contact_person_email.]]:</label><label>[[|items.contact_person_email|]]</label>
             </td>
            <td valign="center">
            <?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a><?php }?>
                
            </td>
            <td valign="center">
                <?php if(User::can_delete(false,ANY_CATEGORY) ){ ?>
                <a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]])); ?>" class="delete_button"><img src="packages/core/skins/default/images/buttons/delete.gif" /> </a>
                <?php } ?>
            </td>
        </tr>
        <!--/LIST:items--> 
    </table>
    <input name="cmd" type="hidden" value=""/>
    <input name="id_select" type="hiden" id="id_select" />
</form>
</div>
<script>

    jQuery("#all_checkbox").change(function(){
        var check = this.checked;
        jQuery('.check').each(function(){  //each thi lap het cac gia tri co cung class khac vs each id chi goi den thang co dung cai id do thoi
            this.checked =check;
        })
    })
    
    // xoa tung ncc 1
    jQuery(".delete_button").click(function(){
       if(!confirm('.ban_co_muon_xoa_ko'))
       {
        return false;
               
       } 
    });
    // tao function delete_group
    function delete_group()
    {
        var inputs = jQuery('input:checkbox:checked') ;// tim den cai input nao co thuoc tinh checkbox:checked
        var strids ='0';
        for(var i=0; i<inputs.length;i++ ) // dem so phan tu co input type la checkbox:checked
        {
            if(inputs[i].id!='all_checkbox') // mang inputs thu i id ko  = 'allcheckbox'
            {
                strids += "," + inputs[i].id; // tao 1 chuoi cach nhau bang dau , de lay tat ca cac id cua input do
            }
        }
        jQuery('#id_select').val(strids)  ; // them gia tri vao the input id_select
        
    }

</script>