<style>
#to_code option{padding:10px 0px;}
</style>
<fieldset id="toolbar" style="margin-top:2px">
	<?php if(Form::$current->is_error()){echo (Form::$current->error_messages());}?>
	<form name="SplitForm" id="SplitForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">		
		<table cellpadding="10" cellspacing="5" width="100%" style="#width:99%;" border="0" align="center">		
			<tr>
				<td colspan="2">
					<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
						<tr>
							<td width="65%" class="form-title">[[.transplant_order.]]</td>
						</tr>
                        <tr>
                        	<td align="right" style="text-align:right;">
                                [[.bar_name.]]: <select name="bars" id="bars" onchange="updateBar();" style="height:30px;font-size: 25px;"></select> 
                                <input name="acction" type="hidden" value="0" id="acction" />
                                
                                <script>
                                    var bar_id = '<?php if(Url::get('bar_id')){ echo Url::get('bar_id');} else { echo '';}?>';
                                    if(bar_id != ''){
                                    	$('bars').value = bar_id;	
                                    }
                                 </script>
                        	</td>
                        </tr>
					</table>
				</td>	
			</tr>	
			<tr>
            	<td align="right">[[.transplant_from_code.]]:</td>
				<td width="50%" valign="top"><select name="from_code"  class="select-large" id="from_code" onchange="check_table();" style="height:35px;></select></td>
			</tr>
			<tr>
	            <td align="right">[[.to_code.]]</td>
				<td width="50%" valign="top"><select name="to_code" class="select-large" id="to_code" style="height:35px;"></select></td>
			</tr>
            <tr>
            	<td></td>
                <td><div >
                    <input style="width: 70px; height: 50px;" name="save" type="submit" value="[[.split_table_ok.]]" onclick="if(jQuery('#from_code').val() != 0 && jQuery('#to_code').val() != 0){return check_discount();;}else{ alert('Bàn ghép không được để trống!'); return false;}" />
                     <!--<input style="width: 70px; height: 50px;" name="reset" type="reset" value="Hủy" id="reset" onclick="window.history.back(-1);"/>-->
                     <input style="width: 70px; height: 50px;" name="exit" type="reset" value="[[.split_table_cancel.]]" id="exit" onclick="window.history.back(-1);"/>
                </div></td>
            </tr>
		</table>
		<input name="confirm" type="hidden" id="confirm" value="1">
		<input name="cmd" value="cmd" type="hidden" id="cmd">
		<input name="action" type="hidden" id="action">
  </form>
</fieldset>
<script>
<?php echo 'var block_id = '.Module::block_id().';';?>
var from_code = jQuery('#from_code').val();
var to_code = jQuery('#to_code').val();
var to_code_js = [[|to_code_js|]];
/** Minh fix chon trung ban **/
//jQuery('#to_code option[value='+from_code+']').removeAttr('disabled');
jQuery('#to_code option[value='+from_code+']').attr('disabled','disabled');
    function check_table()
    {      
        var list_code = document.getElementById("from_code");
        var str_code = list_code.options[list_code.selectedIndex].text;    
        jQuery('#to_code').val('');    
        for(var i in to_code_js){        
            jQuery('#to_code option[value='+to_code_js[i]['id']+']').removeAttr('disabled');
            if(str_code == to_code_js[i]['name']){            
                jQuery('#to_code option[value='+to_code_js[i]['id']+']').attr('disabled','disabled');
            }   
        }         
    }
/** Minh fix chon trung ban **/    
	function updateBar(){
		jQuery('#acction').val(1);
		SplitForm.submit();
	}
    function check_discount()
    {
        var from_code = jQuery("#from_code").val();
        var to_code = jQuery("#to_code").val();
        if (window.XMLHttpRequest){
            xmlhttp=new XMLHttpRequest();
        }
        else{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                if(text_reponse==1)
                {
                    alert("% giảm giá của bàn sẽ được chuyển về 0. Vui long chọn lại giá trị giảm giá cho bàn được ghép vào!");
                }
                return true;
            }
        }
        xmlhttp.open("GET","get_discount_bar.php?data=get_discount_bar&from_code="+from_code+"&to_code="+to_code,true);
        xmlhttp.send();
    }
</script>
