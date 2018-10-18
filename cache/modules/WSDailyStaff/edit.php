<style>
.simple-layout-middle{width:100%;}
#style_edit
{
      width: 95%;
      margin: 10px auto;
      border-radius: 10px;
      -webkit-border-radius: 10px;
      -moz-border-radius: 10px;
      -o-border-radius: 10px;
      box-shadow: 0px 0px 5px #999;
      -webkit-box-shadow: 0px 0px 5px #999;
      -moz-box-shadow: 0px 0px 5px #999;
      -o-box-shadow: 0px 0px 5px #999;
}
a {
  color: #fff;
}

.dropdown dd,
.dropdown dt {
  margin: 0px;
  padding: 0px;
}

.dropdown ul {
  margin: -1px 0 0 0;
}

.dropdown dd {
  position: relative;
}

.dropdown a,
.dropdown a:visited {
  color: #fff;
  text-decoration: none;
  outline: none;
  font-size: 12px;
}

.dropdown dt a {
  background-color: #4F6877;
  display: block;
  overflow: hidden;
  height: 22px;
  width: 110px;
}

.dropdown dt a span,
.multiSel span {
  cursor: pointer;
  display: inline-block;
  padding: 0 0px 0px 0;
}

.dropdown dd ul {
  background-color: #4F6877;
  border: 0;
  color: #fff;
  display: none;
  padding: 2px 15px 2px 5px;
  width: 100px;
  list-style: none;
  height: 200px;
  position: absolute;
  overflow: auto;
}

.dropdown span.value {
  display: none;
}

.dropdown dd ul li a {
  padding: 5px;
  display: block;
}

.dropdown dd ul li a:hover {
  background-color: #fff;
}
</style>
<div  id="style_edit">
<?php
    if($this->map['permission_save']==0)
    {
        $style_save ='style="color:#fff; background-color:#D1CDCD; " disabled="disabled"';
    } 
    else
    {
        $style_save ='';
    }
?>
<form name="EditDailyStaffForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 24px;"></i> 
            <?php
                if(Url::get('cmd')=='edit')
                    echo 'CẬP NHẬT' ;
                else
                    echo 'THÊM MỚi';
            ?>
            </td>
            <td width="40%" align="right" nowrap="nowrap" style="padding-right: 50px;">
                
            	<input name="save" type="submit"  id="save" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="margin-right: 5px; text-transform: uppercase;" onclick="return check_staff();" <?php echo $style_save;?>/><!-- onclick="return check_staff();"-->
				<input type="button" value="<?php echo Portal::language('back');?>" class="w3-btn w3-green" onclick="window.history.back();" style="text-transform: uppercase;"/>
            </td>
        </tr>
    </table>
    
    <input  name="selected_chb" id="selected_chb" style="width: 300px;"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('selected_chb'));?>">
	<div class="content">
        <?php
        if(Url::get('cmd')=='edit')
        {
            ?>
            <div style="width: 100%; margin-left: 10%; margin-top: 10px; margin-bottom: 10px;" ><?php echo Portal::language('staff_name');?> &nbsp; <input  name="staff_name" id="staff_name" style="width: 200px; height: 26px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('staff_name'));?>"> &nbsp;<?php echo Portal::language('date');?> &nbsp;<input  name="from_date" id="from_date" style="width: 100px; height: 26px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></div><!--style="background-color: #D1CDCD;" readonly="readonly"-->
            <?php 
           
        } 
        else
        {
            ?>
            <table cellpadding="0" cellspacing="0" width="80%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td align="left" style="padding-left: 10px;"><?php echo Portal::language('date');?></td>
                    <td align="left"><input  name="from_date" id="from_date" style="width: 100px; height: 26px;"  onchange="EditDailyStaffForm.re_add.value='re_add';EditDailyStaffForm.submit();"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                    <td align="left"><?php echo Portal::language('staff_name');?></td>
                    <td align="left"><input  name="staff_name" id="staff_name" style="width: 200px; height: 26px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('staff_name'));?>"></td>
                    <td align="left"><?php echo Portal::language('choose_floor');?></td>
                    <td align="left">
                        <dl class="dropdown" style="width: 100px; height: 26px;"> 
                            <dt>
                            <a href="#">
                              <span class="hida"></span>    
                              <p class="multiSel"></p>  
                            </a>
                            </dt>
                                <dd>
                                    <div class="mutliSelect">
                                        <ul>
                                        <?php echo $this->map['floor_option'];?>
                                        </ul>
                                    </div>
                                </dd>
                            </dl>
                    </td>
                    <td align="left"><?php echo Portal::language('status');?></td>
                    <td align="left"><select  name="new_status" id="new_status" style="width: 200px; height: 26px;"><?php
					if(isset($this->map['new_status_list']))
					{
						foreach($this->map['new_status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('new_status',isset($this->map['new_status'])?$this->map['new_status']:''))
                    echo "<script>$('new_status').value = \"".addslashes(URL::get('new_status',isset($this->map['new_status'])?$this->map['new_status']:''))."\";</script>";
                    ?>
	</select></td>
                    <td><input class="w3-btn w3-gray" style="height: 26px; padding-top: 5px;" type="button" value="<?php echo Portal::language('search');?>" onclick="EditDailyStaffForm.floor_view.value='re_add';EditDailyStaffForm.submit();" /></td>
                </tr>
            </table>
            <?php 
        }
        ?>
        
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="margin-bottom: 10px;">
		  
          <tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
			  <th width="10px" align="center"><input  name="all_item_check_box" id="all_item_check_box" onclick="select_all();"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('all_item_check_box'));?>"></th>
              <th align="center" width="20px">STT</th>
			  <th align="center" width="100px">Room</th>
              <th align="center" width="100px">Extrabed</th>
              <th align="center" width="100px">IO</th>
              <th align="center" width="100px">Status</th>
			  <th align="center">Work in</th>
              <th align="center">Work out</th>
			  <th align="center">Remark</th>
		  </tr>
          <?php $i=1; ?>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <?php if( !isset($_REQUEST['new_status']) OR ( isset($_REQUEST['new_status']) and $_REQUEST['new_status']=='' ) OR ( isset($_REQUEST['new_status']) and $_REQUEST['new_status']==$this->map['items']['current']['room_guest_status'] ) ){ ?>
            <tr>
                <?php 
                if($this->map['items']['current']['status_cmd']==1)
                {
                    ?>
                        <td width="10px"><input  name="chb_<?php echo $this->map['items']['current']['id'];?>" id="chb_<?php echo $this->map['items']['current']['id'];?>" onclick="select_object(this,<?php echo $this->map['items']['current']['room_id'];?>);" class="col" checked="checked"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('chb_'.$this->map['items']['current']['id']));?>"></td>
                    <?php 
                }
                else
                {
                    ?>
                        <td width="10px"><input  name="chb_<?php echo $this->map['items']['current']['id'];?>" id="chb_<?php echo $this->map['items']['current']['id'];?>" onclick="select_object(this,<?php echo $this->map['items']['current']['room_id'];?>);" class="col"/ type ="checkbox" value="<?php echo String::html_normalize(URL::get('chb_'.$this->map['items']['current']['id']));?>"></td>
                    <?php
                } 
                ?>
                <td align="center"><?php echo $i++; ?></td>
                <td align="center"><?php echo $this->map['items']['current']['name'];?></td>
                <td align="center"><?php if($this->map['items']['current']['extrabed']==1){ echo "Yes"; } else{ echo " "; }?></td>
                <td align="center"><?php echo $this->map['items']['current']['room_status'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['room_guest_status'];?></td>
                <input name="reservation_room_id[<?php echo $this->map['items']['current']['room_id'];?>]" type="hidden" id="reservation_room_id_<?php echo $this->map['items']['current']['room_id'];?>" value="<?php echo $this->map['items']['current']['reservation_room_id'];?>"/>
                <?php 
                if($this->map['items']['current']['status_cmd']==1)
                {
                    ?>
                        <td align="center"><input name="work_in[<?php echo $this->map['items']['current']['room_id'];?>]" type="text" id="work_in_<?php echo $this->map['items']['current']['room_id'];?>" value="<?php echo $this->map['items']['current']['work_in'];?>" style="width: 98%;"/> </td>
                        <td align="center"><input name="work_out[<?php echo $this->map['items']['current']['room_id'];?>]" type="text" id="work_out_<?php echo $this->map['items']['current']['room_id'];?>" value="<?php echo $this->map['items']['current']['work_out'];?>" style="width: 98%;"/></td>
                        <td align="center"><input name="remark[<?php echo $this->map['items']['current']['room_id'];?>]" type="text" id="remark_<?php echo $this->map['items']['current']['room_id'];?>" value="<?php echo $this->map['items']['current']['remark'];?>" style="width: 98%;"/></td>
                    <?php 
                }
                else
                {
                    ?>
                        <td align="center"><input name="work_in[<?php echo $this->map['items']['current']['room_id'];?>]" type="text" id="work_in_<?php echo $this->map['items']['current']['room_id'];?>"  style="width: 98%;"/> </td>
                        <td align="center"><input name="work_out[<?php echo $this->map['items']['current']['room_id'];?>]" type="text" id="work_out_<?php echo $this->map['items']['current']['room_id'];?>" style="width: 98%;"/></td>
                        <td align="center"><input name="remark[<?php echo $this->map['items']['current']['room_id'];?>]" type="text" id="remark_<?php echo $this->map['items']['current']['room_id'];?>" style="width: 98%;"/></td>
                    <?php
                } 
                ?>
                
            </tr>
            <?php } ?>
		  <?php }}unset($this->map['items']['current']);} ?>		
		</table>
	</div>
	<input  name="save" id="save" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('save'));?>">
    <input  name="re_add" id="re_add" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('re_add'));?>">
    <input  name="floor_view" id="floor_view" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('floor_view'));?>">
    <input  name="floor" id="floor" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('floor'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>

<script type="text/javascript">
	jQuery("#from_date").datepicker();
    
	jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
    var str_room ='';
    <?php
        foreach($this->map['items'] as $k=>$row)
        {
            if($row['status_cmd']==1)
            {
                if( !isset($_REQUEST['new_status']) OR ( isset($_REQUEST['new_status']) and $_REQUEST['new_status']=='' ) OR ( isset($_REQUEST['new_status']) and $_REQUEST['new_status']==$row['room_guest_status'] ) )
                echo ' str_room +="'.$row["room_id"].',";';
            }
        }
    ?>
    document.getElementById('selected_chb').value = str_room;
    function select_all()
    {
        
        var check = document.getElementById('all_item_check_box').checked;
        if(check)
        {
            jQuery('form .col').attr('checked',true);
            
            var str="";
            <?php
                foreach($this->map['items'] as $k=>$row) 
                {
                    if( !isset($_REQUEST['new_status']) OR ( isset($_REQUEST['new_status']) and $_REQUEST['new_status']=='' ) OR ( isset($_REQUEST['new_status']) and $_REQUEST['new_status']==$row['room_guest_status'] ) )
                    echo ' str +="'.$row["room_id"].',";';
                }
            ?>
            document.getElementById('selected_chb').value = str;
        }
        else
        {
            jQuery('form .col').attr('checked',false);
            document.getElementById('selected_chb').value = "";
        }
    }
    
    function select_object(object,id)
    {
        if(object.checked)
        {
            var str =id + ",";
            document.getElementById('selected_chb').value +=str;
        }
        else
        {
            var str =id + ",";
            var arr_select = document.getElementById('selected_chb').value;
            
            arr_select = arr_select.replace(str, "");
            document.getElementById('selected_chb').value = arr_select;
        }
    }
    click=0;
    function check_staff()
    {
        var flag = true
        var staff = document.getElementById('staff_name').value;
        if(staff.trim()=="")
        {
            alert('Bạn chưa nhập tên nhân viên!');
            return false;
        }
        
        var str_room = document.getElementById('selected_chb').value;
        if(str_room.trim()=="")
        {
            alert('Bạn chưa chọn phòng nào cả!');
            return  false;
        }
        click++;
        if(click!=1)
        {
            return  false;
        }else{
            return true;
        }
        
    }
jQuery(".dropdown dt a").on('click', function() {
jQuery(".dropdown dd ul").slideToggle('fast');
jQuery(".dropdown dd ul").css('overflow','auto');
});
jQuery(".dropdown dd ul li a").on('click', function() {
  jQuery(".dropdown dd ul").hide();
});

function getSelectedValue(id) {
  return jQuery("#" + id).find("dt a span.value").html();
}

jQuery(document).bind('click', function(e) {
  var $clicked = jQuery(e.target);
 if (!$clicked.parents().hasClass("dropdown")) jQuery(".dropdown dd ul").hide();
});

jQuery('.mutliSelect input[type="checkbox"]').on('click', function() {

  var title = jQuery(this).closest('.mutliSelect').find('input[type="checkbox"]').val(),
    title = jQuery(this).val() + ",";
  if (jQuery(this).is(':checked')) {
    var html = '<span title="' + title + '">' + title + '</span>';
    //jQuery('.multiSel').append(html);
    //jQuery(".hida").hide();
  } else {
    jQuery('span[title="' + title + '"]').remove();
    var ret = jQuery(".hida");
    jQuery('.dropdown dt a').append(ret);

  }
});
function get_ids()
    {
    var inputs = jQuery('input:checkbox:checked');
    var string = "";
    for (var i=0;i<inputs.length;i++)
    { 
        if(string==""){
            string =inputs[i].value;
        }else{
            string +=","+inputs[i].value;
        }
         
    }
    jQuery('#floor').val(string);
    }       
</script>
