<form method="post" name="EditMinibarImportForm">
<div style="background-color:#FFFFFF;width:100%;">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
    		<td align="left" style="width: 350px;text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.edit_used_quantity.]]</td>
    		<td align="left">
                <input name="update" type="submit" value="[[.save.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                <input name="back" type="button" value="[[.back.]]" class="w3-btn w3-green" onclick="window.location='<?php echo Url::build_current(); ?>'" style="text-transform: uppercase;"/>
            </td>        
    	</tr>
    </table>
    
    
    <table cellpadding="5" cellspacing="5" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
    		<td align="left" style="vertical-align: top;">
                [[.note.]]:  <textarea name="note" id="note" style="width: 300px;"></textarea>
            </td>        
    	</tr>
    </table>
    
    <table id="tblExport" border="1" cellspacing="0" cellpadding="5" align="left" bordercolor="#C6E2FF" bgcolor="#FFFFFF" style="border-collapse:collapse;">
        <thead>
            <tr class="table-header">
                <!--<td rowspan="3"><input name="room" type="checkbox" id="room" value="room" onclick="check_all();"/></td>-->  
                <td rowspan="3">[[.room_name.]]</td>
                <td align="center" colspan="<?php echo sizeof([[=room_amenities=]])*2;?>">[[.list_amenities_in_room.]]</td>
            </tr>
            <tr>
                <!--LIST:room_amenities-->
                <td align="center">
                    <strong>[[|room_amenities.id|]]</strong>
                    <br />
                    <span style="font-size: 10px ;">[[|room_amenities.name|]]</span>
                </td>
                <!--/LIST:room_amenities-->  
            </tr>
            <tr>
                <!--LIST:room_amenities-->
                <td align="center">[[.used.]]</td>    
                <!--/LIST:room_amenities-->      
            </tr>  
        </thead>
        <tbody>
            <!--LIST:rooms-->
            <tr>
                <!--<td><input name="check_[[|rooms.id|]]" id="check_[[|rooms.id|]]" type="checkbox" value="[[|rooms.id|]]"/></td>-->
                <td>
                    Room [[|rooms.name|]]
                    <input name="[[|rooms.id|]]" id="[[|rooms.id|]]" type="hidden" value="[[|rooms.id|]]"/>
                </td>
                <!--LIST:rooms.products-->
                    <!--IF:cond_minibar(isset([[=rooms.products.is_real=]]))-->
                    <td align="center" style="color:red;">
                        <input name="[[|rooms.id|]]_[[|rooms.products.product_id|]]" type="text" id="[[|rooms.id|]]_[[|rooms.products.product_id|]]" style="width: 50px;text-align: center;" class="input_number" value="<?php if(isset($_REQUEST[[[=rooms.id=]]."_".[[=rooms.products.product_id=]]])) echo $_REQUEST[[[=rooms.id=]]."_".[[=rooms.products.product_id=]]]; ?>"  oninput="update_total(this);" />
                    </td> 
                    <!--ELSE-->
                    <td align="center" style="background-color: #F1F1F1;"></td>
                    <!--/IF:cond_minibar-->
                        
                <!--/LIST:rooms.products-->
            </tr>
            <!--/LIST:rooms-->
            <tr>
                <td>
                    [[.Total_quantity.]]
                </td>
                <!--LIST:room_amenities-->
                <td align="center"></td>    
                <!--/LIST:room_amenities--> 
            </tr>
        </tbody>
    </table>
    
    <table cellspacing="0" cellpadding="0" style="text-align:center; background: white; position: absolute; top:0px; z-index:99999; left:-1px; display:none;" border="1" id="hidTable">
        <thead> 
            <tr class="table-header">
                <!--<td rowspan="3"><input name="room" type="checkbox" id="room" value="room" onclick="check_all();"/></td>-->  
                <td rowspan="3">[[.room_name.]]</td>
                <td align="center" colspan="<?php echo sizeof([[=room_amenities=]])*2;?>">[[.list_amenities_in_room.]]</td>
            </tr>
            <tr>
                <!--LIST:room_amenities-->
                <td align="center">
                    <strong>[[|room_amenities.id|]]</strong>
                    <br />
                    <span style="font-size: 10px ;">[[|room_amenities.name|]]</span>
                </td>
                <!--/LIST:room_amenities-->  
            </tr>
            <tr>
                <!--LIST:room_amenities-->
                <td align="center">[[.used.]]</td>    
                <!--/LIST:room_amenities-->      
            </tr> 
        </thead> 
    </table>
    
</div>
</form>
<script>
	function check_all()
	{
		 if($('room').checked==true)
		 {
		 	<!--LIST:rooms-->
				$('check_[[|rooms.id|]]').checked=true;
			<!--/LIST:rooms-->
		 }
		 else
		 {
		 	<!--LIST:rooms-->
				$('check_[[|rooms.id|]]').checked=false;
			<!--/LIST:rooms-->
		 }
	}
    function check_value(obj)
    {
        if(jQuery(obj).val()=='')
            jQuery(obj).val('0');
    }
    
    jQuery(document).ready(function(){
        //jQuery("table#tblExport tbody:first-child td").attr("colspan",jQuery("table#tblExport thead tr tr").length);
        
        jQuery("table#hidTable").width(jQuery("table#tblExport").outerWidth()+"px");
        //jQuery("table#hidTable thead").width(jQuery("table#tblExport").outerWidth()+"px");
        //jQuery("table#hidTable thead tr").width(jQuery("table#tblExport").outerWidth()+"px");
        jQuery("table#hidTable").css("left",jQuery("table#tblExport").offset().left+"px");
        
        jQuery("table#hidTable thead tr td").each(function(){
            
            var index = jQuery("table#hidTable thead tr td").index(jQuery(this));
            var element  = jQuery("table#tblExport thead tr td").get(index);
            var width = to_numeric(jQuery(element).outerWidth())-1;
            //console.log(width);
            jQuery(this).width(width+"px");
        });
        
        jQuery(window).scroll(function() {
            var documentScrollLeft = jQuery(window).scrollLeft();
            var documentScrollTop = jQuery(window).scrollTop();
            if(documentScrollLeft>200){
                jQuery("table#hiddenTable").css("left",documentScrollLeft);
                jQuery("table#hiddenTable").show("fast");
            }
            else{
               jQuery("table#hiddenTable").hide("fast"); 
            }
            if(documentScrollTop>300){
               jQuery("table#hidTable").css({"top":documentScrollTop+"px","display":""}); 
            }
            else{
               jQuery("table#hidTable").css("display","none");  
            }
            
        });
        
        jQuery("#tblExport tbody tr:first-child td").not("td:first-child").each(function(){
            var obj = jQuery(this).find("input");
            update_total(obj);
        });
        
    });
    
    function update_total(obj)
    {  
        var this_index = jQuery(jQuery(obj).parent()).index();
        var total = 0;
        jQuery("#tblExport tbody tr").not("tr:last-child").each(function(){
            var parrent_element = jQuery(this).find("td");
            var this_element = jQuery(this).find("td").get(this_index);
            if(jQuery(this_element).find("input").length>0)
            {
                total += to_numeric(jQuery(this_element).find("input").val());
            }
        });
        jQuery(jQuery("#tblExport tbody tr:last-child td").get(this_index)).html(total);
    }
</script>