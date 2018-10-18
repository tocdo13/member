<link href="skins/default/category.css" rel="stylesheet" type="text/css" />

<div>


<form name="ListRoomTypeForm" method="post">
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="75%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('manage_note_for_work');?></td>
		<?php if(User::can_add(false,ANY_CATEGORY)){ ?>
		<td align="right"  width="25%">
            <a href="<?php echo	Url::build_current(array('cmd'=>'add')+array('housekeeping_equipment_old_store_id')); ?>" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Add');?></a>
        
		<?php } if(User::can_delete(false,ANY_CATEGORY)){?>
	
        <a href="javascript:void(0)" onclick="if(!confirm('Bạn có chắc không?')){return false};ListRoomTypeForm.cmd.value='delete';ListRoomTypeForm.submit();" class="w3-red w3-btn" style="text-transform: uppercase; text-decoration: none; ">Xóa</a>
		<?php }?>
	</tr>
</table>


<fieldset>
        	<legend class="title"><b><?php echo Portal::language('search_options');?></b></legend>
            <table>
               
                	<tr>

                		<td nowrap>From Date:<input name="from_date" type="text" id="from_date" value="<?php if(isset($this->map['from_date']))echo $this->map['from_date'];?>" /></td>
                	
                		<td>To Date:<input name="to_date" type="text" id="to_date" value="<?php if(isset($this->map['to_date']))echo $this->map['to_date'];?>" /></td>
                        <td>
                			<?php echo Draw::button(Portal::language('search'),false,'subsearch',true,'SearchHousekeepingEquipmentForm');?></td><td>
                			
                		</td>
                	</tr>
              
            </table>
  </fieldset>
  
  <table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
    		<tbody>
        			<tr class="table-header">
            		<th width="2%"><input type="checkbox" id="all_item_check_box"/></th>
            			<th width="10%" align="center">Tiêu đề</th>
            			<th width="50%" align="center">Mô tả</th>
                        <th width="20%" align="center">Thời gian</th>
            			<th width="9%" align="center">Sửa</th>
            			<th width="9%" align="center">Xóa</th>
            		</tr>
                 
                    <?php
                    //System::debug($this->map['note_tree']);die;
                    foreach($this->map['note_tree'] as $key=>$val){
                        echo '<tr class="category-group" bgcolor="#FFFF99">
                            <td colspan="8" style="padding-left:30px;">Ngày <strong>'.substr($key,6,7).'/'.substr($key,-4,-2).'/'.substr($key,0,4).'</strong></td>
                		</tr>';
                        foreach($val as $v){
                            ?>
                            <tr <?php if($v['status']==1){echo 'bgcolor="#B0C4DE"';} if($v['start_time']>$this->map['date_now']){ echo 'bgcolor="#20B2AA"';} if($v['start_time']<$this->map['date_now'] && $v['status']==0){ echo 'bgcolor="#F4A460"';} ?>>
                            <?php
                             echo ' 
                			<td>
            				<input name="item_check_box[]" type="checkbox" class="item-check-box" value="'.$v['id'].'"/>
                            </td>
                			<td align="center" >'.$v['title'].'</td>
                			<td align="center" >'.$v['description'].'</td>
                			<td align="center" >'.date('H-i:d/m/Y',$v['start_time']).'--'.date('H-i:d/m/Y',$v['end_time']).'</td>
                           <td style ="text-align:center"><a href= "'.URL::build_current().'&cmd=edit&id='.$v['id'].'">
                             <img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
                	       <td style ="text-align:center"><a class="delete-one-item" href="'.Url::build_current(array('cmd'=>'delete','id'=>$v['id'])).'">
                           <img src="packages/core/skins/default/images/buttons/delete.gif"></a></td>
           		           </tr>
                        
                        ';
                        }
                      
                    }
                    ?>
                    
                   
    		 </tbody>
			 </table>
  
</div>
	<input name="cmd" value="" type="hidden"/>
<input type="hidden" name="form_block_id" value="39"/>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			

<script type="text/javascript">
	jQuery("#delete_button").click(function (){
            
		ListRoomTypeForm.cmd.value = 'delete';
		ListRoomTypeForm.submit();
	});
	jQuery(".delete-one-item").click(function (){
	  
       		if(!confirm('<?php echo Portal::language('are_you_sure');?>')){
			return false;
		}
	});
	jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
      
	});
</script>

<script type="text/javascript">
jQuery(document).ready(
    function()
    {  
          jQuery('.subsearch').click(function(){
           
          var from= jQuery(this).parents('table').find('#from_date').val();
           var to= jQuery(this).parents('table').find('#to_date').val();
          if(from==''){
                alert('Bạn chưa nhập ngày bắt đầu');
                document.forms['ListRoomTypeForm'].from_date.focus();
                return false;
          }else if(to==''){
                alert('Bạn chưa nhập ngày kết thúc');
                document.forms['ListRoomTypeForm'].to_date.focus();
                return false;
          }else{
            var arr = from.split("/");
            from=arr[2]+arr[1]+arr[0];
            var arr = to.split("/");
           to=arr[2]+arr[1]+arr[0];
            if(to<from){
                alert('Ngày kết thúc không được nhỏ hơn ngày bắt đầu');
                document.forms['ListRoomTypeForm'].from_date.focus();
                return false;
            }
          }
          
        });
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
        
        jQuery('#to_day').datepicker();
       // jQuery('#from_time').mask("99:99");
//        jQuery('#to_time').mask("99:99");
       
      
    }
    );
function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
function Autocomplete()
{
    jQuery("#customer").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item) {
             //console.log(item);
            document.getElementById('customer_id').value = item.data[0];
           // console.log(document.getElementById('customer_id').value);
        }
    }) ;
}



</script>

<script src="packages/core/includes/js/jquery/datepicker.js">
</script>



