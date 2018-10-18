<style>
    .multi-input-header{
        height:20px;
    }
</style>
<span style="display:none">
	<span id="mi_ticket_card_area_ip_sample">
		<div id="input_group_#xxxx#" style="margin-top: 8px;">
			<span class="multi_input">
					<input name="mi_ticket_card_area_ip[#xxxx#][id]" style="width:30px; background: #FFC;" id="id_#xxxx#" readonly="" type="hidden"/>
			</span>
            <span class="multi_input">
					<input  name="mi_ticket_card_area_ip[#xxxx#][stt]" style="width:30px; background: #FFC;" type="text" id="stt_#xxxx#" readonly=""/>
			</span>
            <span class="multi_input">
					<input  name="mi_ticket_card_area_ip[#xxxx#][ip]" style="width:157px;" type="text" id="ip_#xxxx#" autocomplete="off"/>
			</span>
			<span class="multi_input"><span style="width:20px;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="if(confirm('[[.Are_you_sure.]]')) mi_delete_row($('input_group_#xxxx#'),'mi_ticket_card_area_ip','#xxxx#','');event.returnValue=false;" style="cursor:hand;"/>
			</span></span><br/>
		</div>
	</span>
</span>
<div class="row">
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">[[.ticket_card_area_ip_list.]]</h4>
                </div>
                <form name="setMenu" method="POST" onsubmit="return checkDuplicate();">
                    <input  name="deleted_ids" id="deleted_ids" type="hidden" value=""/>
                    <div class="panel-body">
                        <div class="col-md-3 pull-right" style="margin-bottom:20px;">
                            <div style="pull-right">
                                <input class="btn btn-sm btn-info" style="margin-right: 50px;" value="[[.Save.]]" name="submit" type="submit"/>
                            </div>
                        </div>
                        <div>
                            <?php if(Form::$current->is_error()){?><strong>B&#225;o l&#7895;i</strong><br>
                        	<?php echo Form::$current->error_messages();?><br>
                        	<?php }?>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12" style="margin-bottom: 20px;">
                                <div class="col-md-6 form-group">
                                    <span style="font-weight: bold;"><?php echo mb_strtoupper([[=name=]],"utf-8"); ?></span>
                                </div>
                            </div>
                            <div class="col-md-12" style="padding-left: 30px;">
                                 <span id="mi_ticket_card_area_ip_all_elems" style="margin-bottom: 10px; display:block;">
                					<span>
                						<span class="multi-input-header" style="width:30px;">[[.STT.]]</span>
                						<span class="multi-input-header" style="width:160px;">[[.IP.]]</span>
                						<span class="multi-input-header" style="width:20px;"><img src="skins/default/images/spacer.gif"/></span>
                						<br/>
                					</span>
                				 </span>
                                 <input type="button" value="[[.add.]]" onclick="mi_add_new_row('mi_ticket_card_area_ip');"/>
                                 <input type="hidden" value="<?php echo isset($_GET['id'])?$_GET['id']:""; ?>" name="bar_set_menu_id" />   
                            </div>
                            <hr class="col-md-6" />
                        </div>                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
<?php
if(isset($_REQUEST['mi_ticket_card_area_ip']))
{
    echo 'var mi_ticket_card_area_ip = '.String::array2js($_REQUEST['mi_ticket_card_area_ip']).';';
?>
mi_init_rows('mi_ticket_card_area_ip',mi_ticket_card_area_ip);
<?php
}
?>
var ticket_card_area_ip_list = <?php if(!empty([[=ticket_card_area_ip_list=]])) echo [[=ticket_card_area_ip_list=]]; else echo "''"; ?>;
function delete_set_menu(obj){
    if(confirm('Bạn có chắc không?')){
        jQuery(obj).parent().parent().remove();
    }
}


function changeSttValue(){
    var max_stt = jQuery("#mi_set_menu_all_elems span:last-child").prev().find(("div span:nth-child(2) input")).val();
    var max_stt = parseInt(max_stt)+1;
    if(isNaN((max_stt))){
        max_stt = 1;
    }
        jQuery("#mi_set_menu_all_elems span:last-child div span:nth-child(2) input").val(max_stt);
}

function checkDuplicate(){
    //console.log(ticket_card_area_ip_list);
    var condition = true;  
    var i = 0;
    var regular = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/g;
    jQuery("input[id^=ip_]").each(function(){
            if(i!=0 && condition){
                var ip = jQuery(this).val().trim();
                if(!ip.match(regular)){
                    alert("IP "+ip+" không hợp lệ ! Xin vui lòng kiểm tra lại!");
                    condition=false;
                }
            }
            i++;
      });
            
    var j = 0;   
      jQuery("input[id^=ip_]").each(function(){
            if(j!=0 && condition){
                var ip = jQuery(this).val().trim();
                //console.log(ip);
                jQuery("input[id^=ip_]").not(this).each(function(){
                    var ip_temp = jQuery(this).val().trim();
                    if(ip==ip_temp){
                        alert("IP "+ip+" đã bị trùng! Xin vui lòng kiểm tra lại!");
                        condition=false;
                    }
                });
                for(var ip_temp in ticket_card_area_ip_list)
                {
                    
                    if(ip==ticket_card_area_ip_list[ip_temp]['ip'])
                    {
                        alert("Địa chỉ IP "+ip+" đã tồn tại ở một cửa kiểm soát khác. Xin vui lòng đổi lại địa chỉ IP này!");
                        jQuery(this).focus();
                        condition=false;
                    }
                }
            }
            j++;
      });
    return condition;  
}
</script>