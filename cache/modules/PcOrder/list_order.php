<style>
@media print{
    #note, #searchForm{
        display: none;
    }
}

#searchForm{
    width: 100%;
    margin: 0 auto;
}

#searchForm table tr td{
    line-height: 20px;
}

#searchForm input{
    height: 25px;
    border: 1px solid #888;
}
#searchForm select{
    height: 25px;
    border: 1px solid #888;
}
#searchForm input:hover{
    border: 1px dashed #000;
}
#searchForm select:hover{
    border: 1px dashed #000;
}

#search{
    background-color: #009688;
    color: #FFF;
}

#note{
    width: 80%;
    margin: 0 auto;
}

#note span{
    font-size: 14px;
}
</style>
<?php System::set_page_title(HOTEL_NAME);?>
<div class="customer-type-pc_supplier-bound">
<form name="ListSupplierForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr height="40">
            <td width="80%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('order_created');?></td>
            <td width="20%" align="right" nowrap="nowrap">
            </td>
        </tr>
    </table>
    <div id="searchForm">
        <fieldset style="border: 1px solid #333;">
            <legend><?php echo Portal::language('search');?></legend>
            <table>
                <tr style="text-align: center;">
                    <td><?php echo Portal::language('invoice_code');?></td>
                    <td><input  name="invoice_code" id="invoice_code" style="width: 80px;" / type ="text" value="<?php echo String::html_normalize(URL::get('invoice_code'));?>"></td>
                    <td><?php echo Portal::language('invoice_name');?></td>
                    <td><input  name="invoice_name" id="invoice_name" style="width: 120px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('invoice_name'));?>"></td>
                    <td  align="left" nowrap="nowrap"><?php echo Portal::language('user_status');?></td>
    			    <td>
                      <!-- 7211 -->  
                      <select  style="width: 130px;" name="user_status" id="user_status">
                        <option value="1">Active</option>
                        <option value="0">All</option>
                      </select>
                      <!-- 7211 end--> 
                    </td>
                    <td><?php echo Portal::language('creater');?></td>
                    <td><select  name="create_user" id="create_user" style="width: 130px;"><?php
					if(isset($this->map['create_user_list']))
					{
						foreach($this->map['create_user_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('create_user',isset($this->map['create_user'])?$this->map['create_user']:''))
                    echo "<script>$('create_user').value = \"".addslashes(URL::get('create_user',isset($this->map['create_user'])?$this->map['create_user']:''))."\";</script>";
                    ?>
	</select></td>
                    <td><?php echo Portal::language('status');?></td>
                    <td><select  name="status" id="status" style="width: 100px;"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	</select></td>
                    <td><?php echo Portal::language('from_date');?></td>
                    <td><input  name="from_date" id="from_date" style="width: 70px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                    <td><?php echo Portal::language('to_date');?></td>
                    <td><input  name="to_date" id="to_date" style="width: 70px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                    <td><?php echo Portal::language('supplier');?></td>
                    <td><input  name="supplier_name" id="supplier_name" onfocus="Autocomplete();" style="width: 150px;" / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_name'));?>"></td>
                    <td></td>
                    <td><input name="search" type="submit" id="search" value="<?php echo Portal::language('search');?>" /></td>
                </tr>

            </table>
        </fieldset>
    </div>
    <br /> 
    <?php 
        $create = 0;$accountant = 0;$director = 0;$complete = 0;$apart_close = 0; $close = 0; $cancel = 0;
    ?>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <?php
        if($this->map['items']['current']['status'] =='CREATED'){$create++;}if($this->map['items']['current']['status'] =='ACCOUNTANT'){$accountant++;}if($this->map['items']['current']['status'] =='DIRECTOR'){$director++;}if($this->map['items']['current']['status'] =='COMPLETE'){$complete++;}if($this->map['items']['current']['status'] =='APART_CLOSE'){$apart_close++;}if($this->map['items']['current']['status'] =='CLOSE'){$close++;}if($this->map['items']['current']['status'] =='CANCEL'){$cancel++;}
    ?>
    <?php }}unset($this->map['items']['current']);} ?> 
    <div id="note" style="text-align: center;">      
        <input name="CREATE" value="Đã tạo đơn hàng (<?php echo $create; ?>)" readonly="" style="width: 145px; height: 25px; background-color: #92d050; border: none; padding-left: 2px;"/>
        <input name="ACCOUNTANT" value="KT trưởng đã duyệt (<?php echo $accountant; ?>)" readonly="" style="width: 160px; height: 25px; background-color: #b1a0c7; border: none;padding-left: 2px;"/>
        <input name="DIRECTOR" value="Giám đốc đã duyệt (<?php echo $director; ?>)" readonly="" style="width: 158px; height: 25px; background-color: #ff0000; border: none;padding-left: 2px;"/>
        <input name="COMPLETE" value="Đã mua hàng (<?php echo $complete; ?>)" readonly="" style="width: 123px; height: 25px; background-color: #ffc000; border: none;padding-left: 2px;"/>
        <input name="APART_CLOSE" value="Đã đóng 1 phần (<?php echo $apart_close; ?>)" readonly="" style="width: 138px; height: 25px; background-color: #e26b0a; border: none;padding-left: 2px;"/>
        <input name="CLOSE" value="Đã đóng (<?php echo $close; ?>)" readonly="" style="width: 88px; height: 25px; background-color: #974706; border: none;padding-left: 2px;"/>
        <input name="CANCEL" value="Hủy bỏ (<?php echo $cancel; ?>)" readonly="" style="width: 88px; height: 25px; background-color: #8db4e2; border: none;padding-left: 2px;"/>
    </div>
    <div class="content">
        <br />
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="border-collapse: collapse;">
            <tr class="w3-light-gray" style="line-height: 24px; text-transform: uppercase;">
                <th width="30"><?php echo Portal::language('stt');?></th>
                <th width="100" align="center"><?php echo Portal::language('invoice_code');?></th>
                <th width="150" align="center"><?php echo Portal::language('invoice_name');?></th>
                <th width="150" align="center"><?php echo Portal::language('creater');?></th>
                <th width="150" align="center"><?php echo Portal::language('last_edit');?></th>
                <th width="100" align="center"><?php echo Portal::language('status');?></th>
                <th width="150" align="center"><?php echo Portal::language('user_cancel');?></th>
                <th width="250" align="center"><?php echo Portal::language('supplier');?></th>
                <th width="100" align="center"><?php echo Portal::language('total_amount');?></th>
                <th width="250" align="center"><?php echo Portal::language('description');?></th>
                <th width="40" align="center"><?php echo Portal::language('view');?></th>
                <th width="40" align="center"><?php echo Portal::language('edit');?></th>
            </tr>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
            <tr <?php echo $this->map['items']['current']['index']%2==0?' style="background-color: #E8F3FF;"':''?>>
                
                <td align="center"><?php echo $this->map['items']['current']['index'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['code'];?></td>
                <td><?php echo $this->map['items']['current']['name'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['creater'];?> <?php echo $this->map['items']['current']['create_time'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['last_edit_user'];?> <?php echo $this->map['items']['current']['last_edit_time'];?></td>
                <td align="center" style="background-color: #<?php if($this->map['items']['current']['status']=='CREATED'){echo '92d050';}if($this->map['items']['current']['status']=='ACCOUNTANT'){echo 'b1a0c7';}if($this->map['items']['current']['status']=='DIRECTOR'){echo 'ff0000';}if($this->map['items']['current']['status']=='COMPLETE'){echo 'ffc000';}if($this->map['items']['current']['status']=='APART_CLOSE'){echo 'e26b0a';}if($this->map['items']['current']['status']=='CLOSE'){echo '974706';}if($this->map['items']['current']['status']=='CANCEL'){echo '8db4e2';} ?>">
                <?php
                switch ($this->map['items']['current']['status']) {
                    case 'CREATED':
                         echo 'Đã tạo đơn hàng';
                         break;
                    case 'ACCOUNTANT':
                         echo 'KT trưởng đã duyệt';
                         break;
                    case 'DIRECTOR':
                         echo 'Giám đốc đã duyệt';
                         break;
                    
                    case 'COMPLETE':
                         echo 'Đã mua hàng';
                         break;
                    case 'CANCEL':
                         echo 'Hủy bỏ';
                         break;
                    case 'CLOSE':
                        echo 'Đã đóng';
                         break;
                    case 'APART_CLOSE':
                        echo 'Đã đóng 1 phần';
                         break;
                     default:
                        echo '';
                         break;
                 } 
                ?>
                </td>

                <td>
                    <?php echo $this->map['items']['current']['cancel_user'];?>
                    <br/>
                    <?php echo $this->map['items']['current']['time_cancel'];?>
                    <br/>
                    <?php echo $this->map['items']['current']['note_cancel'];?>
                </td>
                <td><?php echo $this->map['items']['current']['supplier_name'];?></td>
                <td align="right"><?php echo $this->map['items']['current']['total'];?></td>
                <td><?php echo $this->map['items']['current']['description'];?></td>
                <td style="text-align: center;"><?php if(User::can_view(false,ANY_CATEGORY)){?><a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'print_order','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a><?php }?></td>
                <td style="text-align: center;">
                <?php 
                if($this->map['items']['current']['status']=='CANCEL')
                {
                    ?>
                    <a href="?page=pc_order&cmd=edit_cancel&id=<?php echo $this->map['items']['current']['id'];?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></i></a>
                    <?php 
                }
                ?>
                </td>
            </tr>
          <?php }}unset($this->map['items']['current']);} ?>            
        </table>
        <br />  
        <div class="paging"><?php echo $this->map['paging'];?></div> 
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			 
</div>
<script>
jQuery('#from_date').datepicker();
jQuery('#to_date').datepicker();
// 7211
    var users = <?php echo String::array2js($this->map['users']);?>;
    for (i in users){
       if(users[i]['is_active']!=1){
          jQuery('option[value='+users[i]['id']+']').remove();  
       }
    }
    jQuery('#user_status').change(function(){
        if(jQuery('#user_status').val()!=1){
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('#create_user').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>');  
               }
            }
        }else{
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('option[value='+users[i]['id']+']').remove();  
               }
            }
        }
    });
    //7211 end
function Autocomplete()
{
    jQuery("#supplier_name").autocomplete({
       url:'get_customer1.php?supplier=1' 
    });
}
</script>