<style type="text/css">
.tdtitle{padding-left: 15px; width: 120px;}
.tdcolu2{width:300px;}
.tdcolu3{width:650px;}
.attorney{width:150px;} 
.time{width:50px;}
.tdright{float:right; margin-right: 5px;}
.table-header{height:35px;}
.table-header th{text-align:center;}
.dtcenter{text-align:center;}
</style>
<?php 
//System::debug($_REQUEST); 
//exit();?>
<script type="text/javascript">

</script>
<div class="customer_care">
<form name="EditCustomerCareForm" method="post">
    <input style="display: none" id="selected_ietm" name="selected_ietm" type="button" onclick="<?php echo Url::build_current(array('cmd'=>'view','type','id'=>Url::get('id')));?>">
    <div class="content">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td width="30%" class="" style="font-size: 18px; text-transform: uppercase;"><i class="fa fa-tty w3-text-orange" style="font-size: 24px;"></i> [[|title|]]</td>
            <td style="padding-right: 30px;" class="w3-right" ><a class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" href="javascript:void(0)" onclick="Reset();">[[.add.]]</a>
            <a class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" href="javascript:void(0)" onclick="EditCustomerCareForm.submit();">[[.save.]]</a>
            <a class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none; " href="javascript:void(0)" onclick="location='<?php echo URL::build('customer');?>';">[[.back.]]</a></td>
        </tr>
    </table>
    <fieldset>
        <legend class="title">[[.Contents.]]</legend>
        <table width="800px" border="0" cellspacing="0" cellpadding="2">
        <tr >
            <td class="tdtitle" >
            [[.company_name.]]
            </td>
            <td colspan="2">
            <input name="id" type="hidden" id="id"/>
            <input name="id_customer_care" type="hidden" id="id_customer_care"/>
            <input name="name" type="text" id="name"  style="width: 356px;" onfocus="Autocomplete();" autocomplete="off"/> 
            </td>
            <td class="tdright">
            [[.sale_code.]]: <input name="sale_code" type="text" id="sale_code"  readonly="readonly" style="background-color: #CCC; width:120px;"/>
            </td>
        </tr>
        <tr>
            <td class="tdtitle">[[.date_contact.]]</td>
            <td><input name="date_contact" type="text" id="date_contact" style="margin-left: 0;"/></td>
            <td >[[.time.]]:<input name="time_contact" type="text" id="time_contact" class="time" style="width: 35px;"/> </td>
            <td class="tdright">[[.type_contact.]]:<select name="expedeency" id="expedeency" style="margin:0px;padding:0px; width:120px;"></select></td>
        </tr>
        <tr>
            <td class="tdtitle">[[.address_contact.]]
            </td>
            <td colspan="2"><input name="address_contact" type="text"  id="address_contact" style="width: 356px;" onfocus="ChangBackGround(this);"/> </td>
            <td class="tdright">[[.action_contact.]]:<select name="init_active" id="init_active" style="margin:0px;padding:0px; width:120px;"></select></td>
        </tr>
        <tr>
            <td class="tdtitle">[[.content_contact.]]</td>
            <td colspan="3"><textarea name="content_contact"  id="content_contact" rows="3" class="tdcolu3"></textarea></td>
        </tr>
        <tr>
            <td class="tdtitle">[[.attorney_customer.]]
            </td>
            <td><input name="attorney_customer" type="text" id="attorney_customer" class="attorney"/></td>
            <td colspan="2">[[.attorney_hotel.]]: <input name="attorney_hotel" type="text"  id="attorney_hotel" class="attorney"/></td>
        </tr>
        <tr>
            <td class="tdtitle">[[.Note.]]</td>
            <td colspan="3"><textarea name="note"  id="note" rows="3" class="tdcolu3"></textarea></td>
        </tr>
        </table>
    </fieldset>
    <fieldset >
         <legend class="title">[[.customer_care_history.]]</legend>
         <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
            <tr class="w3-light-gray" style="text-transform: uppercase; height: 24px; text-align: center;" >
              <th width="1%" style="text-align: center;">[[.order_number.]]</th>
              <th width="10%">[[.date_contact.]]</th>
              <th width="10%">[[.action_contact.]]</th>
              <th width="10%">[[.type_contact.]]</th>
              <th width="15%">[[.location.]]</th>
              <th width="20%">[[.content.]]</th>
              <th width="8%" >[[.customercare.]]</th>
              <th width="8%">[[.hotelcare.]]</th>
              <th width="20%">[[.note.]]</th>
              <th width="5%">[[.Edit.]]</th>
              <th width="5%">[[.Delete.]]</th>
          </tr>
          <!--LIST:items-->
              <tr>
                <td class="dtcenter">[[|items.i|]]</td>
                <td class="dtcenter">[[|items.date_contact|]] <br>[[|items.time|]]</td>
                <td>[[|items.init_active|]]</td>
                <td>[[|items.expedeency|]]</td>
                <td>[[|items.address_contact|]]</td>
                <td>[[|items.content_contact|]]</td>
                <td>[[|items.attorney_customer|]]</td>
                <td>[[|items.attorney_hotel|]]</td>
                <td>[[|items.note|]]</td>
                <td class="dtcenter">
                <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                    <a href="<?php echo Url::build_current(array('cmd'=>'edit','type','id_customer_care'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a>
                    <?php }?>
                </td>
                
                <td class="dtcenter">
                <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','type','id_customer_care'=>[[=items.id=]],'customer_id'=>Url::get('id')));?>"><img src="packages/core/skins/default/images/buttons/delete.gif"/></a>
                    <?php }?>
                </td>
              </tr>
          <!--/LIST:items-->
          </table>           
    </fieldset>
    </div>
    <input name="cmd" type="hidden" id="cmd"  >
</form>
</div>
<script type="text/javascript">
jQuery('#date_contact').datepicker();
jQuery("#time_contact").mask("99:99");
jQuery(".delete-one-item").click(function (){
    if(confirm('[[.are_you_sure_delete_customercare_this.]]')){
        EditCustomerCareForm.submit();
    }
    else
    {
        return false;
    }
    
});
function ChangBackGround(x)
{
   // x.style.backgroundColor='red';
}
 //name text search: module_id
 //name text kq: module_name
 //select module(id,name)
function Autocomplete()
{
    jQuery("#name").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item) {
             //console.log(item);
             //console.log(item.data[0]);
             document.getElementById('id').value = item.data[0];
             document.getElementById('cmd').value='view';
             EditCustomerCareForm.submit(); 
          
          
        }
    }) ;
}
function Reset()
{
    document.getElementById('date_contact').value='';
    document.getElementById('time_contact').value='';
    document.getElementById('address_contact').value='';
    
    document.getElementById('content_contact').value='';
    document.getElementById('attorney_customer').value='';
    document.getElementById('attorney_hotel').value='';
    document.getElementById('note').value='';
    document.getElementById('id_customer_care').value='';
    
    document.getElementById('cmd').value='add';
}
</script>
