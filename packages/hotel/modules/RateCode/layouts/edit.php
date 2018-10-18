<style type="text/css">
.content
{
    margin-bottom: 50px;
}
.rate_code
{
    width: 80px;
    font-size: 12px;
    
}
</style>

<script type="text/javascript" src="packages/core/includes/js/picker.js"></script>
<?php System::set_page_title(HOTEL_NAME);?>
<span style="display:none">
	<span id="mi_room_level_sample">
		<div id="mi_room_level_#xxxx#" style="text-align:left;">
			<input  name="mi_room_level[#xxxx#][id]" type="hidden" id="id_#xxxx#" style=" height: 24px;"/>
            <span class="multi-input"><input  name="mi_room_level[#xxxx#][index]" style="width:50px; height: 24px;text-align: center;background-color: #CCC;" type="text" id="index_#xxxx#" readonly="readonly" tabindex="-1"/></span>
			<span class="multi-input"><input  name="mi_room_level[#xxxx#][code]" style="width:100px; height: 24px;background-color: #CCC;" type="text" id="code_#xxxx#" readonly="readonly" tabindex="-1"/></span>
			<span class="multi-input"><input  name="mi_room_level[#xxxx#][name]" type="text" id="name_#xxxx#" readonly="readonly" style="width:150px; height: 24px;background-color: #CCC;"/></span>
            <span class="multi-input"><input  name="mi_room_level[#xxxx#][price]" type="text" id="price_#xxxx#" style="width:150px;text-align: right; height: 24px;" onkeyup="this.value =number_format(to_numeric(this.value));"/></span>
			
             <br clear="all"/>
		</div>
	</span> 
</span>
<div>   
<form name="EditRoomLevelForm" method="post">
<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-dollar w3-text-orange" style="font-size: 26px;"></i> [[|edit_rate_code|]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="30%" style="text-align: right; padding-right: 30px;"><input type="submit" value="[[.Save_and_close.]]" id="btnsave" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;" onclick="checksubmit();"/><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
        <fieldset>
        <legend class="title">[[.rate_code_information.]]</legend>
            <div style="width: 50%; float:left;">
                <table cellpadding="2" cellspacing="0" style="margin-left: 30px;">
                    
                  <tr>
                        <td class="rate_code" style="width: 100px;">[[.code.]]</td>
                        <td><input name="code" type="text" id="code" style="height: 24px;"/></td>
                   </tr>
                   <tr>
                        <td class="rate_code" style="width: 100px;">[[.rate_code_name.]]</td>
                        <td><input name="name" type="text" id="name" style="height: 24px;"/></td>
                   </tr>
                   <tr>
                        <td class="rate_code" style="width: 100px;">[[.start_date.]]</td>
                        <td> 
                        <input name="start_date" type="text" id="start_date" style="height: 24px;width: 100px;"/>
                        [[.end_date.]]
                        <input name="end_date" type="text" id="end_date" style="height: 24px;width: 100px;"/>
                        </td>
    
                   </tr>
                   <tr>
                        <td class="rate_code" style="width: 100px;">[[.frequence.]]</td>
                        <td>
                        <select id="frequence_id" name="frequence_id" onchange="choose_weekly(this);" style=" height: 24px; width: 100px;">
                        [[|frequence_options|]]
                        </select>
                        </td>
                   </tr>
                   <tr id="choose_weekly">
                        <td class="rate_code" style="width: 100px;">[[.choose_date.]]</td>
                        <td>
                        <label for="chbT2">Thứ 2</label>
                        <input name="chbT2" type="checkbox" id="chbT2"/>
                        
                        <label for="chbT3">Thứ 3</label>
                        <input name="chbT3" type="checkbox" id="chbT3" />
                        
                        <label for="chbT4">Thứ 4</label>
                        <input name="chbT4" type="checkbox" id="chbT4" />
                        
                        <label for="chbT5">Thứ 5</label>
                        <input name="chbT5" type="checkbox" id="chbT5" />
                        
                        <label for="chbT6">Thứ 6</label>
                        <input name="chbT6" type="checkbox" id="chbT6" />
                        
                        <label for="chbT7">Thứ 7</label>
                        <input name="chbT7" type="checkbox" id="chbT7" />
                        
                        <label for="chbT8">Chủ nhật</label>
                        <input name="chbT8" type="checkbox" id="chbT8" />
                        
                        <br />
                        <label for="chbT26">Từ thứ 2 tới thứ 6</label>
                        <input name="chbT26" type="checkbox" id="chbT26" onchange="choose_two_six(this);" />
                        
                        <br />
                        <label for="chbT7CN">Thứ 7 & Chủ nhật</label>
                        <input name="chbT7CN" type="checkbox" id="chbT7CN" onchange="choose_sat_sun(this);" />
                        </td>
                   </tr>
                   <tr>
                        <td class="rate_code" style="width: 100px;">[[.date_type.]]</td>
                        <td>
                        <select id="date_type_id" name="date_type_id" style=" height: 24px;width: 100px;">
                        [[|date_type_options|]]
                        </select>
                        </td>
                   </tr>
                </table>
                <fieldset>
                <legend class="title">[[.room_level.]]</legend>
                    <div class="w3-light-gray">
    				<span id="mi_room_level_all_elems">
    					<span style="white-space:nowrap; width:auto;">
    						
    						<span class="multi-input-header" style="width:50px; height: 24px; text-align: center;">[[.stt.]]</span>
    						<span class="multi-input-header" style="width:100px; height: 24px;text-align: center;">[[.room_level_code.]]</span>
    						<span class="multi-input-header" style="width:150px; height: 24px;text-align: center;">[[.room_level_name.]]</span>
                            <span class="multi-input-header" style="width:150px; height: 24px;text-align: center;">[[.room_level_price.]]</span>
    						
    						<br clear="all"/>
    					</span>
    				</span>
    			 </div>
                </fieldset>
            </div>
            <fieldset style="float: left; width: 40%;">
                <legend class="title">TA/TO/Cor/Walkin [[.list.]]</legend>
                <table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
        			<tr class="w3-light-gray" style="text-transform: uppercase; height: 26px;">
        			  <th width="10%" style="text-align: center;"><input type="checkbox" id="all_item_check_box" onchange="check_all_customer(this)"/></th>
        			  <!--<th width="10%" style="text-align: center;">[[.stt.]]</th>-->
                      <th width="30%" style="text-align: center;">[[.code.]]</th>
        			  <th width="40%" style="text-align: center;">[[.name.]]</th>
     		         </tr>
                     <!--LIST:customer_groups-->
                     <?php
                        if([[=customer_groups.index=]]%2==0)
                        {
                            echo "<tr style='background-color:#E8F3FF ;'>";  
                        } 
                        else
                            echo "<tr>";
                     ?>
                        <td style="text-align: center;">
                        <input name="item_check_box_<?php echo [[=customer_groups.index=]]?>" type="checkbox" id="item_check_box_<?php echo [[=customer_groups.index=]]?>"/>
                        </td>
                        <!--<td style="text-align: center;">[[|customer_groups.index|]]</td>-->
                        <td>[[|customer_groups.id|]]</td>
                        <td>[[|customer_groups.name|]]</td>
                        <input name="customer_group_id_<?php echo [[=customer_groups.index=]] ?>" type="hidden" id="customer_group_id_<?php echo [[=customer_groups.index=]] ?>" value="[[|customer_groups.id|]]" />
                     </tr>
                     <!--/LIST:customer_groups-->
                </table>
                <input name="customer_group_count" type="hidden" id="customer_group_count" value="<?php echo count([[=customer_groups=]]); ?>" />
            </fieldset>
        </fieldset>
	</div>
</form>	
</div>
<script type="text/javascript">
<?php 
    if(isset($_REQUEST['mi_room_level']))
    {
        echo 'var room_level = '.String::array2js($_REQUEST['mi_room_level']).';';
    }
    else
    {
        echo 'var room_level = [];';
    }
    echo 'var count_customers ='.count([[=customer_groups=]]).';';
?>

mi_init_rows('mi_room_level',room_level);
jQuery("#start_date").datepicker();
jQuery("#end_date").datepicker();

<?php
    if(Url::get('cmd')=='add')
        echo "var is_add=1;";
    else
        echo "var is_add=0;"; 
    if(isset($_REQUEST['frequence_id']))
    {
        ?>
        document.getElementById('frequence_id').value='<?php echo $_REQUEST['frequence_id'];?>';
        <?php 
    }
    if(isset($_REQUEST['date_type_id']))
    {
        ?>
        document.getElementById('date_type_id').value='<?php echo $_REQUEST['date_type_id'];?>';
        <?php 
    }
    
    if(isset($_REQUEST['weekly']))
    {
        ?>
        var weekly ='<?php echo $_REQUEST['weekly'];?>';
        var arr_weekly = weekly.split(",");
        for(var i=0;i<arr_weekly.length;i++)
        {
            //hien thi nhung thu duoc chon 
            document.getElementById('chbT'+ arr_weekly[i]).checked = true;
        }
        jQuery("#choose_weekly").css('display','');
        <?php 
    }
    else
    {
        ?>
        jQuery("#choose_weekly").css('display','none');
        <?php 
    }
    foreach([[=customer_groups=]] as $row)
    {
        if(isset($row['rate_customer_group_id']) && $row['rate_customer_group_id']!='')
        {
            ?>
            document.getElementById('item_check_box_' + '<?php echo $row['index'];?>').checked = true;
            <?php 
        }
    }
    
?>
if(is_add)
{
    jQuery("#choose_weekly").css('display','none'); 
}


function choose_weekly(obj)
{
    if(obj.value=='WEEKLY')
    {
        //hien thi dong chon cac thu
        jQuery("#choose_weekly").css('display',''); 
    }
    else
    {
        //an dong chon cac thu
        jQuery("#choose_weekly").css('display','none'); 
    }
}
function checksubmit(){
    jQuery('#btnsave').css('display','none');
}
function choose_two_six(obj)
{
    if(obj.checked)
    {
        //hien thi cac check tu T2 toi T6
        document.getElementById('chbT2').checked=true;
        document.getElementById('chbT3').checked=true;
        document.getElementById('chbT4').checked=true;
        document.getElementById('chbT5').checked=true;
        document.getElementById('chbT6').checked=true;
    }
    else
    {
        //bo check tu T2 toi T6
        document.getElementById('chbT2').checked=false;
        document.getElementById('chbT3').checked=false;
        document.getElementById('chbT4').checked=false;
        document.getElementById('chbT5').checked=false;
        document.getElementById('chbT6').checked=false;
    }
}

function choose_sat_sun(obj)
{
    if(obj.checked)
    {
        //hien thi chon T7 & CN
        document.getElementById('chbT7').checked=true;
        document.getElementById('chbT8').checked=true;

    }
    else
    {
        //bo hien thi chon T7 & CN 
        document.getElementById('chbT7').checked=false;
        document.getElementById('chbT8').checked=false;

    }
}

function check_all_customer(obj)
{
    if(obj.checked)
    {
        //checked tat ca customer groups
        for(var i=1;i<=count_customers;i++)
        {
            document.getElementById('item_check_box_' + i).checked=true;
        }
    }
    else
    {
        //bo checked tat ca customer groups 
        for(var i=1;i<=count_customers;i++)
        {
            document.getElementById('item_check_box_' + i).checked=false;
        }
        
    }
}


</script>