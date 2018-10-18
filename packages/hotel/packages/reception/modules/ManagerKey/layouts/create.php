
<?php
    /*function getip4_local()
    {
        $ip='';
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        { 
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } 
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
         //to check ip is pass from proxy 
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } 
        else 
        { 
            $ip=$_SERVER['REMOTE_ADDR']; 
        } 
        return $ip;
    }*/
?>
<span style="display:none">
	<span id="mi_group_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-input">
                <select name="mi_group[#xxxx#][room]" id="room_#xxxx#">
					[[|room_id_options|]]
				</select>
            </span>
			<!--<span class="multi-input" style="width:20px; text-align:center;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_group','#xxxx#','');event.returnValue=false; " style="cursor:pointer;"/></span>-->   
		</div>
        <br clear="all" />
	</span>
</span>
<form method="post" name="Createform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >[[.create_card_title.]]</td>
                        <td style="width: 260px; " align="right" >
                            <!--<input type="button" id="home" value="Home" width="120px" style="background-color: #E5E5E5; padding-right: 10px; padding-left: 10px; font-weight: bold; " />-->
                            
                        </td>
                    </tr>
                </table>
                   
                <table width="800px" style="border: 1px solid #087E85;">
                    
                    
                    <tr>
                        <td width ="150px" height="280px" align="center" style="background-color: silver; " >
                            <div style=" width: 400px; border: 3px inset gray; background-color: white; padding-right: 20px; padding-left: 40px; padding-top: 20px; ">
                                <table width="100%" >
                                
                                    <?php
                                        if(isset([[=status=]]))
                                        {
                                            if([[=detail=]]!='')
                                                $style ='style="color: blue; font-size: 16px; "';
                                            else
                                                $style ='style="color: red; font-size: 16px; "';
                                            ?>
                                            <tr style="margin-bottom: 10px;">
                                            <td colspan="2" align="center" >
                                                <span <?php echo $style; ?> >[[|status|]]</span>
                                                <br/>
                                                <!--<span style="font-size:14px;color: blue;" >[[|detail|]]</span>-->
                                                <br/>
                                            </td>
                                            </tr>
                                            <?php 
                                        } 
                                        ?>
                                    <tr>
                                        <td width ="120px" valign="top" style=" font-size: 16px;">[[.room.]]</td>
                                        <td style=" font-size: 16px;">
                                            <div >
                                				<span id="mi_group_all_elems">
                                				</span>
                                			</div>
                                			<!--<div style="padding-top: 2px;" ><a href="javascript:void(0);" onclick="if(input_count <104){mi_add_new_row('mi_group');$('room_'+input_count).focus();}" class="button-medium-add">[[.Add.]]</a></div>-->
                                        </td>
                                    </tr>
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.start_date.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input name="date_start" type="text" id="date_start" style="text-align: center;" size="10" />
                                            <input name="time_start" type="text" id="time_start" style="text-align: center;" size="5" />
                                        </td>
                                    </tr>
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.expiry_date.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input name="date_expiry" type="text" id="date_expiry" style="text-align: center;" size="10" />
                                            <input name="time_expiry" type="text" id="time_expiry" style="text-align: center;" size="5" />
                                        </td>
                                    </tr>
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.status.]]</td>
                                         <td style=" font-size: 16px;">
                                            <label for="rbt_1">[[.first_key.]]</label><input name="rbt"  type="radio" id="rbt_1" value="1"  
                                            <?php if(isset($_REQUEST['rbt'])) 
                                            {
                                                if($_REQUEST['rbt']==1)
                                                     echo ' checked="true"';
                                            }
                                            else
                                            {
                                                echo ' checked="true"';
                                            }?>/> 
                                            <label for="rbt_2">[[.late_key.]]</label><input  name="rbt" type="radio" id="rbt_2" value="2"
                                            <?php if(isset($_REQUEST['rbt'])) 
                                            {
                                                if($_REQUEST['rbt']==2)
                                                     echo ' checked="true"';
                                            }
                                            ?>
                                            />
                                         </td>
                                    </tr>
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.number_key.]]</td>
                                        <td style=" font-size: 16px;">
                                            <select id="number_key" name="number_key" style="width: 50px;" >
                                                <option value="1"
                                                <?php
                                                    if(isset($_REQUEST['number_key']) && $_REQUEST['number_key']==1)
                                                        echo ' selected="true"' ;
                                                ?>
                                                >1</option>
                                                <option value="2" 
                                                <?php
                                                    if(isset($_REQUEST['number_key']) && $_REQUEST['number_key']==2)
                                                        echo ' selected="true"' ;
                                                ?>
                                                >2</option>
                                                <option value="3"
                                                <?php
                                                    if(isset($_REQUEST['number_key']) && $_REQUEST['number_key']==3)
                                                        echo ' selected="true"' ;
                                                ?>
                                                >3</option>
                                                <option value="4"
                                                <?php
                                                    if(isset($_REQUEST['number_key']) && $_REQUEST['number_key']==4)
                                                        echo ' selected="true"' ;
                                                ?>
                                                >4</option>
                                                <option value="5"
                                                <?php
                                                    if(isset($_REQUEST['number_key']) && $_REQUEST['number_key']==5)
                                                        echo ' selected="true"' ;
                                                ?>
                                                >5</option>
                                                <option value="6"
                                                <?php
                                                    if(isset($_REQUEST['number_key']) && $_REQUEST['number_key']==6)
                                                        echo ' selected="true"' ;
                                                ?>
                                                >6</option>
                                                <option value="7"
                                                <?php
                                                    if(isset($_REQUEST['number_key']) && $_REQUEST['number_key']==7)
                                                        echo ' selected="true"' ;
                                                ?>
                                                 >7</option>
                                            </select>
                                            
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="font-size: 16px;">
                                            [[.read_key_reception.]]
                                        </td>
                                        <td>
                                            <select id="reception_id" name="reception_id" style="width: 80px;" >
                                                [[|reception_id|]]
                                            </select>
                                        </td>
                                    </tr>
                                    
                                    <tr height="60px" >
                                        <td colspan="2" align="center" >
                                            <input id="create" type="button" value="[[.create_card.]]" style="width: 100px; height: 40px; font-size: 16px; font-weight: bold; color: #1F6B7A; " />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" value="" name="get_result" id="get_result" />
    <input type="hidden" value="" name="width" id="width" />
</form>
<script>
    <?php if(isset($_REQUEST['mi_group'])){echo 'var arr = '.String::array2js($_REQUEST['mi_group']).';';}else{echo 'var arr = [];';}?>
    mi_init_rows('mi_group',arr);
    jQuery(document).ready(function(){
        jQuery("#width").val(screen.width);
        
        jQuery("#home").click(function(e)
        {
            window.location.href = '?page=manager_key&portal=default';
        });
        
        jQuery("#create").click(function(e)
        {
            if(check_input())
            {
                jQuery("#get_result").val('get_result');
                Createform.submit();
            }
        });
        jQuery("#date_start").datepicker();
        jQuery("#date_expiry").datepicker();
        jQuery('#time_start').mask('99:99');
        jQuery('#time_expiry').mask('99:99');
    });
    
    function check_input()
    {
        if(!jQuery("#date_start").val())
        {
            alert('Input start date!');
            return false;
        }
        if(!jQuery("#date_expiry").val())
        {
            alert('Input expiry date!');
            return false;
        }
        return true;
    }
    
    /*var lenghOption  =  document.Createform.reception_id.length;
    window.onload = function () {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://www.telize.com/jsonip?callback=DisplayIP";
        document.getElementsByTagName("head")[0].appendChild(script);
    };
    var ip4 = '<?php //echo getip4_local();?>';
    function DisplayIP(response) {
        var j=0;
        for(var i = 0;i<lenghOption;i++)
        {
            var reception =  document.Createform.reception_id.options[i].value;
            var str_reception = reception.split("_");
            if(str_reception[1]==response.ip || str_reception[1]==ip4)
            {
                j =i;
                break;
            }
        }
        var selected_op =  document.Createform.reception_id.options[j].value;
        document.getElementById("reception_id").value = selected_op;
        
    }*/
    
</script>
    
