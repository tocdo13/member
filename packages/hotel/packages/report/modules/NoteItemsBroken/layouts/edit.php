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
    if([[=permission_save=]]==0)
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
                
            	<input name="save" type="submit"  id="save" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="margin-right: 5px; text-transform: uppercase;" onclick="return check_staff();" <?php echo $style_save;?>/><!-- onclick="return check_staff();"-->
				<input type="button" value="[[.back.]]" class="w3-btn w3-green" onclick="window.history.back();" style="text-transform: uppercase;"/>
            </td>
        </tr>
    </table>
    
    <input name="selected_chb" type="hidden" id="selected_chb" style="width: 300px;"/>
	<div class="content">
        <?php
        if(Url::get('cmd')=='edit')
        {
            ?>
            <div style="width: 100%; margin-left: 10%; margin-top: 10px; margin-bottom: 10px;" >[[.staff_name.]] &nbsp; <input name="staff_name" type="text" id="staff_name" style="width: 200px; height: 26px;"/> &nbsp;[[.date.]] &nbsp;<input name="from_date" type="text" id="from_date" style="width: 100px; height: 26px;" /></div><!--style="background-color: #D1CDCD;" readonly="readonly"-->
            <?php 
           
        } 
        else
        {
            ?>
            <table cellpadding="0" cellspacing="0" width="80%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td align="left" style="padding-left: 10px;">[[.date.]]</td>
                    <td align="left"><input name="from_date" type="text" id="from_date" style="width: 100px; height: 26px;"  onchange="EditDailyStaffForm.re_add.value='re_add';EditDailyStaffForm.submit();"/></td>
                    <td align="left">[[.staff_name.]]</td>
                    <td align="left"><input name="staff_name" type="text" id="staff_name" style="width: 200px; height: 26px;"/></td>
                    <td align="left">[[.choose_floor.]]</td>
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
                                        [[|floor_option|]]
                                        </ul>
                                    </div>
                                </dd>
                            </dl>
                    </td>
                    <td align="left">[[.status.]]</td>
                    <td align="left"><select name="new_status" id="new_status" style="width: 200px; height: 26px;"></select></td>
                    <td><input class="w3-btn w3-gray" style="height: 26px; padding-top: 5px;" type="button" value="[[.search.]]" onclick="EditDailyStaffForm.floor_view.value='re_add';EditDailyStaffForm.submit();" /></td>
                </tr>
            </table>
            <?php 
        }
        ?>
        
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="margin-bottom: 10px;">
		  
          <tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
			  <th width="10px" align="center"><input name="all_item_check_box" type="checkbox" id="all_item_check_box" onclick="select_all();"/></th>
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
		  <!--LIST:items-->
            <?php if( !isset($_REQUEST['new_status']) OR ( isset($_REQUEST['new_status']) and $_REQUEST['new_status']=='' ) OR ( isset($_REQUEST['new_status']) and $_REQUEST['new_status']==[[=items.room_guest_status=]] ) ){ ?>
            <tr>
                <?php 
                if([[=items.status_cmd=]]==1)
                {
                    ?>
                        <td width="10px"><input name="chb_[[|items.id|]]" type="checkbox" id="chb_[[|items.id|]]" onclick="select_object(this,[[|items.room_id|]]);" class="col" checked="checked"/></td>
                    <?php 
                }
                else
                {
                    ?>
                        <td width="10px"><input name="chb_[[|items.id|]]" type="checkbox" id="chb_[[|items.id|]]" onclick="select_object(this,[[|items.room_id|]]);" class="col"/></td>
                    <?php
                } 
                ?>
                <td align="center"><?php echo $i++; ?></td>
                <td align="center">[[|items.name|]]</td>
                <td align="center"><?php if([[=items.extrabed=]]==1){ echo "Yes"; } else{ echo " "; }?></td>
                <td align="center">[[|items.room_status|]]</td>
                <td align="center">[[|items.room_guest_status|]]</td>
                <input name="room_name[[[|items.id|]]]" type="hidden" id="room_name_[[|items.id|]]" value="[[|items.room_name|]]"/>
                <input name="house_status[[[|items.room_id|]]]" type="hidden" id="house_status_[[|items.room_id|]]" value="[[|items.house_status|]]"/>
                <input name="reservation_room_id[[[|items.room_id|]]]" type="hidden" id="reservation_room_id_[[|items.room_id|]]" value="[[|items.reservation_room_id|]]"/>
                <?php 
                if([[=items.status_cmd=]]==1)
                {
                    ?>
                        <td align="center"><input name="work_in[[[|items.room_id|]]]" type="text" id="work_in_[[|items.room_id|]]" value="[[|items.work_in|]]" style="width: 98%;"/> </td>
                        <td align="center"><input name="work_out[[[|items.room_id|]]]" type="text" id="work_out_[[|items.room_id|]]" value="[[|items.work_out|]]" style="width: 98%;"/></td>
                        <td align="center"><input name="remark[[[|items.room_id|]]]" type="text" id="remark_[[|items.room_id|]]" value="[[|items.remark|]]" style="width: 98%;"/></td>
                    <?php 
                }
                else
                {
                    ?>
                        <td align="center"><input name="work_in[[[|items.room_id|]]]" type="text" id="work_in_[[|items.room_id|]]"  style="width: 98%;"/> </td>
                        <td align="center"><input name="work_out[[[|items.room_id|]]]" type="text" id="work_out_[[|items.room_id|]]" style="width: 98%;"/></td>
                        <td align="center"><input name="remark[[[|items.room_id|]]]" type="text" id="remark_[[|items.room_id|]]" style="width: 98%;"/></td>
                    <?php
                } 
                ?>
                
            </tr>
            <?php } ?>
		  <!--/LIST:items-->		
		</table>
	</div>
	<input name="save" type="hidden" id="save" value="" />
    <input name="re_add" type="hidden" id="re_add" value="" />
    <input name="floor_view" type="hidden" id="floor_view" value="" />
    <input name="floor" type="hidden" id="floor" value="" />
</form>	
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
        foreach([[=items=]] as $k=>$row)
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
                foreach([[=items=]] as $k=>$row) 
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
        return true;
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
