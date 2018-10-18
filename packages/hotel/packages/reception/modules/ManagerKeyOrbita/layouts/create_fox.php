<form method="post" name="Createform" >
    <table width ="100%">
        <tr>
            <td align="center" width="100%" >
                <table width ="800px" >
                    <tr>
                        <td style="width: 60px; " ><img src="skins/default/images/icon/337.gif" style="width: 60px; height: 50px; " /></td>
                        <td style=" font-size: 14px; font-weight: bold; color: #0066CC; height: 50px; " align="left" >[[.manager_key.]]</td>
                        
                    </tr>
                </table>
                   
                <table width="800px" style="border: 1px solid #087E85;" >
                    <?php
                    if(isset([[=status=]]))
                    {
                        ?>
                        <tr>
                        <td  width ="150px" height="100px" align="center" style="background-color: silver; " >
                            <span style="color: red; font-size: 28px; " ><b>[[|status|]]</b></span>
                            <br /><br />
                            <span style="font-size: 20px;" >[[|detail|]]</span>
                        </td>
                        </tr>
                        <?php 
                    } 
                    ?>
                    
                    <tr>
                        <td width ="150px" height="280px" align="center" style="background-color: silver; " >
                            <div style=" width: 400px; border: 3px inset gray; background-color: white; padding-right: 20px; padding-left: 40px; padding-top: 20px; ">
                                <table width="100%" >
                                    <tr>
                                        <td width ="120px" valign="top" style=" font-size: 16px;">[[.rooms.]]</td>
                                        <td style=" font-size: 16px;">
                                        <select name="room_id" id="room_id" style="width: 80px;">
                                        [[|room_id_options|]]
                                        </select>
                                        </td>
                                    </tr>
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.create_date.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input name="date_start" type="text" id="date_start" style="text-align: center;" size="10" class="readonly" readonly="readonly" />
                                            <input name="time_start" type="text" id="time_start" style="text-align: center;" size="5" class="readonly" readonly="readonly"  />
                                        </td>
                                    </tr>
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.expiry_date.]]</td>
                                        <td style=" font-size: 16px;">
                                            <input name="date_expiry" type="text" id="date_expiry" style="text-align: center;" size="10" class="readonly" readonly="readonly"  />
                                            <input name="time_expiry" type="text" id="time_expiry" style="text-align: center;" size="5" class="readonly" readonly="readonly"  />
                                        </td>
                                    </tr>
                                    <tr height="20px">
                                        <td style=" font-size: 16px;">[[.status.]]</td>
                                        <td style=" font-size: 16px;">
                                            <?php
                                                $first='';
                                                $late ='';
                                                if($_REQUEST['is_late'])
                                                {
                                                    $late =' checked="true"';
                                                } 
                                                else
                                                    $first =' checked="true"';
                                            ?>
                                            <label for="rbt_1">[[.new_card.]]</label><input name="rbt"  type="radio" id="rbt_1" value="1"  <?php echo $first;?>/> 
                                            <label for="rbt_2">[[.copy.]]</label><input  name="rbt" type="radio" id="rbt_2" value="2" <?php echo $late ; ?>/>  
                                        </td>
                                    </tr>
                                    
                                    <tr height="30px">
                                        <td style=" font-size: 16px;">[[.number_key.]]</td>
                                        <td style=" font-size: 16px;">
                                            <select id="number_key" name="number_key" style="width: 80px;" >
                                                <option value="1" >1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4" >4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                            </select>
                                            &nbsp; &nbsp;
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="font-size: 16px;">
                                            [[.reception.]]
                                        </td>
                                        <td>
                                            <select id="reception_id" name="reception_id" style="width: 80px;" >
                                                [[|reception_id|]]
                                            </select>
                                        </td>
                                    </tr>
                                    
                                    <tr height="60px" >
                                        
                                        <td align="center" colspan="2">
                                            <input id="create" type="submit" value="[[.create_card.]]" style="width: 100px; height: 40px; font-size: 16px; font-weight: bold; color: #1F6B7A; " />
                                            
                                            <!--<input id="checkout" type="submit" value="Checkout" style="margin-left:10px;width: 100px; height: 40px; font-size: 16px; font-weight: bold; color: #1F6B7A; " />-->
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
    <input name="number_keys" type="hidden"  id="number_keys" />
    <input type="hidden" value="" name="get_result" id="get_result" />
    <input type="hidden" value="" name="width" id="width" />
</form>
<script>
   
    jQuery(document).ready(function()
    {
        jQuery("#create").click(function(e)
        {
            if(check_input())
            {
                jQuery("#get_result").val('get_result');
                jQuery("#get_checkout").val('');
                Createform.submit();
            }
        });
        
        //jQuery("#date_start").datepicker();
        ///jQuery("#date_expiry").datepicker();
        //jQuery('#time_start').mask('99:99');
        //jQuery('#time_expiry').mask('99:99');
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
    
</script>
    
