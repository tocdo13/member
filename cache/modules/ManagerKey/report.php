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
            <td width="90%" class="form-title">Danh sách tạo khóa</td>
            <td width="10%" align="right" nowrap="nowrap">
             
            <input type="button" name="delete" id="delete" value="<?php echo Portal::language('delete');?>" class="button-medium-delete"/>   
           
            </td>
        </tr>
    </table>
    <div class="content">
        <label onclick="jQuery('.search-box').slideToggle();" ><?php echo Portal::language('search');?></label>
        <div  class="search-box" >
            <fieldset style="text-align: center;" >
            <span>Phòng</span> <select  name="room_id" id="room_id" style="width:80px;"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	</select>
            <span>Ngày bắt đầu</span><input  name="start_date" id="start_date" size="14"  style="width: 80px;" readonly="true" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>">
            <span>Ngày kết thúc</span><input  name="expiry_date" id="expiry_date" size="14" style="width: 80px;" readonly="true"/ type ="text" value="<?php echo String::html_normalize(URL::get('expiry_date'));?>">
            <input type="submit" name="search" id="search" value="<?php echo Portal::language('search');?>" onclick="jQuery('#get_delete').val('');"/>     
            </fieldset>
        </div>
        <br>
        <div id="content" style="width: 90%; height: auto; margin-left: 20px;">
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" align="center">
        <tr class=table-header>
              <th width="1%"><?php echo Portal::language('order_number');?></th>
              
              <th width="15%" align="center">Ngày bắt đầu</th>
              <th width="15%" align="center">Ngày kết thúc</th>
              <th width="30%" align="center">Người tạo</th>
              <th width="30%" align="center">Người checkout</th>
              <th width="5%" align="center"><input name="check_all" id="check_all" type="checkbox" onclick="selected_all();"/></th>
          </tr>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                <tr>
               <td colspan="9" align="left" style="background-color: #FFFF99;">
               <div style=" margin-left: 15px; width: 100%; height: auto; float:left;">
               Recode <span style="margin-left: 5px; margin-right: 5px;"><a class="recode" href="javascript:myJsFunc(<?php echo $this->map['items']['current']['reservation_id'];?>)"><?php echo '#'.$this->map['items']['current']['reservation_id'];?></a></span>
               Phòng <span style="margin-left: 5px; font-size: 14px; color:blue; margin-right: 5px;"><?php echo $this->map['items']['current']['name'];?></span>
               Ngày đến <span style="margin-left: 5px; font-size: 14px; color:blue; margin-right: 5px;"><?php echo $this->map['items']['current']['time_in'];?></span>
               Ngày đi <span style="margin-left: 5px; font-size: 14px; color:blue; margin-right: 5px;"><?php echo $this->map['items']['current']['time_out'];?></span>
               </div></td>  
              </tr> 
              <?php if(isset($this->map['items']['current']['items_key']) and is_array($this->map['items']['current']['items_key'])){ foreach($this->map['items']['current']['items_key'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['items_key']['current'] = &$item2;?>  
                <tr>
                   <td align="center"><?php echo $this->map['items']['current']['items_key']['current']['index'];?></td> 
                  <!-- <td align="center"><?php echo $this->map['items']['current']['items_key']['current']['create_time'];?></td>
                   <td align="center"><?php echo $this->map['items']['current']['items_key']['current']['id'];?></td>-->
                   <td align="center"><?php echo $this->map['items']['current']['items_key']['current']['begin_time'];?></td>
                   <td align="center"><?php echo $this->map['items']['current']['items_key']['current']['end_time'];?></td>
                   <td align="center"><?php echo $this->map['items']['current']['items_key']['current']['create_user'];?> &nbsp; <?php echo $this->map['items']['current']['items_key']['current']['create_time'];?></td>
                   <td align="center"><?php echo $this->map['items']['current']['items_key']['current']['delete_user'];?> &nbsp;<?php echo $this->map['items']['current']['items_key']['current']['delete_time'];?></td>
                   <!--<td align="center"><?php echo $this->map['items']['current']['items_key']['current']['delete_time'];?></td>
                   <td align="center"><?php echo $this->map['items']['current']['items_key']['current']['delete_note'];?></td>--> 
                   <td align="center"><input type="checkbox" name="check_list[]" class="check_list" value="<?php echo $this->map['items']['current']['items_key']['current']['id'];?>"/></td>   
                </tr>  
                <?php }}unset($this->map['items']['current']['items_key']['current']);} ?> 
            <?php }}unset($this->map['items']['current']);} ?>
        </table>
        </div>
  <br />
        
    </div>
    <input  name="get_delete" id="get_delete" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('get_delete'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			    

<script type="text/javascript">
jQuery("#start_date").datepicker();
jQuery("#expiry_date").datepicker();
function myJsFunc(resevation_id)
{
    window.open("?page=reservation&cmd=edit&id="+ resevation_id);
}
jQuery(document).ready(function(){
    jQuery("#delete").click(function(e)
        {
            jQuery("#get_delete").val('get_delete');
            ReportKeysForm.submit();
            
        });
     });
function selected_all()
{
    var check_all = document.getElementById('check_all');
    var arr = document.getElementsByClassName("check_list");
    if(check_all.checked)
    {
        for(var i=0;i<arr.length;i++)
        {
            arr[i].checked = true;
        }
    }
    else
    {
        for(var i=0;i<arr.length;i++)
        {
            arr[i].checked = false;
        }
    }
}
</script>
