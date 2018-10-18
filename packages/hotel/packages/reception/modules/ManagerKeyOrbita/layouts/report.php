<style type="text/css">
.recode
{
    font-size: 16px; 
    color:blue;
    cursor: pointer;
}
.recode:hover
{
    text-decoration: underline;
}
</style>

<form name="ReportKeysForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr height="40">
            <td width="80%" class="form-title">Danh sách tạo khóa</td>
            <td width="20%" align="right" nowrap="nowrap">
            <!--<input type="button" name="delete" id="delete" value="[[.delete.]]" class="button-medium-delete"/>--> 
            </td>
        </tr>
    </table>
    <div class="content">
        <label onclick="jQuery('.search-box').slideToggle();" >[[.search.]]</label>
        <div  class="search-box" >
            <fieldset style="text-align: center;" >
            <span>Phòng</span> <select name="room_id" id="room_id" style="width:80px;"></select>
            <span>Ngày bắt đầu</span><input name="start_date" type="text" id="start_date" size="14"  style="width: 80px;" readonly="true" />
            <span>Ngày kết thúc</span><input name="expiry_date" type="text" id="expiry_date" size="14" style="width: 80px;" readonly="true"/>
            <input type="submit" name="search" id="search" value="[[.search.]]" onclick="jQuery('#get_delete').val('');"/>
            </fieldset>
        </div>
        <br>
        <div id="content" style="width: 90%; height: auto; margin-left: 20px;">
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" align="center">
        <tr class=table-header>
              <th width="1%">[[.order_number.]]</th>
              <th width="15%" align="center">Cửa chung</th>
              <th width="15%" align="center">Ngày bắt đầu</th>
              <th width="15%" align="center">Ngày kết thúc</th>
              <th width="30%" align="center">Người tạo</th>
              <th width="30%" align="center">Người checkout</th>
              <!--<th width="20%" align="center">Lý do checkout</th>-->
              <!--<th width="5%" align="center">delete</th>-->
          </tr>
          
          <!--LIST:items-->
                <tr>
               <td colspan="6" align="left" style="background-color: #FFFF99;">
               <div style=" margin-left: 15px; width: 100%; height: auto; float:left;">
               Recode <span style="margin-left: 5px; margin-right: 5px;"><a class="recode" href="javascript:myJsFunc(<?php echo [[=items.reservation_id=]];?>)"><?php echo '#'.[[=items.reservation_id=]];?></a></span>
               Phòng <span style="margin-left: 5px; font-size: 14px; color:blue; margin-right: 5px;"><?php echo [[=items.name=]];?></span>
               Ngày đến <span style="margin-left: 5px; font-size: 14px; color:blue; margin-right: 5px;"><?php echo [[=items.time_in=]];?></span>
               Ngày đi <span style="margin-left: 5px; font-size: 14px; color:blue; margin-right: 5px;"><?php echo [[=items.time_out=]];?></span>
               </div></td>  
              </tr> 
              <!--LIST:items.items_key-->  
                <tr>
                   <td align="center">[[|items.items_key.index|]]</td> 
                   
                   <td align="left" style="padding-left: 5px;">[[|items.items_key.commdoor|]]</td>
                   <td align="center">[[|items.items_key.begin_time|]]</td>
                   <td align="center">[[|items.items_key.end_time|]]</td>
                   <td align="center">[[|items.items_key.create_user|]] &nbsp;[[|items.items_key.create_time|]]</td>
                   <td align="center">[[|items.items_key.checkout_user|]] &nbsp; [[|items.items_key.checkout_time|]]</td>
                   
                   <!--<td align="center">[[|items.items_key.delete_note|]]</td>-->
                   <!--<td align="center"><input type="checkbox" name="check_list[]" value="[[|items.items_key.id|]]"/></td>  -->
                </tr>  
                <!--/LIST:items.items_key--> 
            <!--/LIST:items-->
        </table>
        </div>
  <br />
        
    </div>
    <input name="get_delete" type="hidden" id="get_delete" value="" />
</form>    

<script type="text/javascript">
jQuery("#start_date").datepicker();
jQuery("#expiry_date").datepicker();
function myJsFunc(resevation_id)
{
    window.open("?page=reservation&cmd=edit&id="+ resevation_id);
}

jQuery(document).ready(function(){
    /*
    jQuery("#delete").click(function(e)
        {
            jQuery("#get_delete").val('get_delete');
            ReportKeysForm.submit();
            
        });*/
     });
</script>
