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
							<td width="65%" class="form-title"><?php echo Portal::language('transplant_order');?></td>
						</tr>
                        <tr>
                        	<td align="right" style="text-align:right;">
                                <?php echo Portal::language('bar_name');?>: <select  name="bars" id="bars" onchange="updateBar();" style="height:30px;font-size: 25px;"><?php
					if(isset($this->map['bars_list']))
					{
						foreach($this->map['bars_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('bars',isset($this->map['bars'])?$this->map['bars']:''))
                    echo "<script>$('bars').value = \"".addslashes(URL::get('bars',isset($this->map['bars'])?$this->map['bars']:''))."\";</script>";
                    ?>
	</select> 
                                <input  name="acction" value="0" id="acction" / type ="hidden" value="<?php echo String::html_normalize(URL::get('acction'));?>">
                                
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
            	<td align="right"><?php echo Portal::language('transplant_from_code');?>:</td>
				<td width="50%" valign="top"><select  name="from_code"  class="select-large" id="from_code" onchange="check_table();" style="height:35px;><?php
					if(isset($this->map['from_code_list']))
					{
						foreach($this->map['from_code_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('from_code',isset($this->map['from_code'])?$this->map['from_code']:''))
                    echo "<script>$('from_code').value = \"".addslashes(URL::get('from_code',isset($this->map['from_code'])?$this->map['from_code']:''))."\";</script>";
                    ?>
	</select></td>
			</tr>
			<tr>
	            <td align="right"><?php echo Portal::language('to_code');?></td>
				<td width="50%" valign="top"><select  name="to_code" class="select-large" id="to_code" style="height:35px;"><?php
					if(isset($this->map['to_code_list']))
					{
						foreach($this->map['to_code_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('to_code',isset($this->map['to_code'])?$this->map['to_code']:''))
                    echo "<script>$('to_code').value = \"".addslashes(URL::get('to_code',isset($this->map['to_code'])?$this->map['to_code']:''))."\";</script>";
                    ?>
	</select></td>
			</tr>
            <tr>
            	<td></td>
                <td><div >
                    <input style="width: 70px; height: 50px;" name="save" type="submit" value="<?php echo Portal::language('split_table_ok');?>" onclick="if(jQuery('#from_code').val() != 0 && jQuery('#to_code').val() != 0){return check_discount();;}else{ alert('Bàn ghép không được để trống!'); return false;}" />
                     <!--<input style="width: 70px; height: 50px;" name="reset" type="reset" value="Hủy" id="reset" onclick="window.history.back(-1);"/>-->
                     <input style="width: 70px; height: 50px;" name="exit" type="reset" value="<?php echo Portal::language('split_table_cancel');?>" id="exit" onclick="window.history.back(-1);"/>
                </div></td>
            </tr>
		</table>
		<input  name="confirm" type ="hidden" id="m" value="<?php echo String::html_normalize(URL::get('confirm','1'));?>">
		<input name="cmd" value="cmd" type="hidden" id="cmd">
		<input  name="action" id="action" type ="hidden" value="<?php echo String::html_normalize(URL::get('action'));?>">
  <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</fieldset>
<script>
<?php echo 'var block_id = '.Module::block_id().';';?>
var from_code = jQuery('#from_code').val();
var to_code = jQuery('#to_code').val();
var to_code_js = <?php echo $this->map['to_code_js'];?>;
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
