 <style>
 @font-face {
  font-family: 'FontAwesome';
  src: url('fonts/fonts/fontawesome-webfont.eot?v=4.1.0');
  src: url('fonts/fonts/fontawesome-webfont.eot?#iefix&v=4.1.0') format('embedded-opentype'), url('fonts/fonts/fontawesome-webfont.woff?v=4.1.0') format('woff'), url('fonts/fonts/fontawesome-webfont.ttf?v=4.1.0') format('truetype'), url('fonts/fonts/fontawesome-webfont.svg?v=4.1.0#fontawesomeregular') format('svg');
  font-weight: normal;
  font-style: normal;
}
    a{
        text-decoration: none;
    }
    a:hover{
        color: #000000;
    }
    table tr td{
        padding: 0px 5px;
    }
    table#file tr{
        background: #ffffff;
    }
    table#file tr:hover{
        background: #eeeeee;
    }
    table input{
        margin:10px 0px;
        height : 25px !important;
    }
 </style>
 <?php
$max['id']=0;
$max_array['code'] = "";
$code_new = $max_array['code'];
$now_date = getdate();
$date_start = $now_date['mday']."/".$now_date['mon']."/".$now_date['year'];
$_REQUEST['date_start'] = $date_start;
$_REQUEST['code_new'] = $code_new;
$_REQUEST['user_id_code'] = User::id();
$_REQUEST['page_title'] = Url::get('cmd');
$id = Url::get('id');

?>
<style>
ul{
    list-style: none;
}
.simple-layout-middle{width:100%;}
#sty_tab tr td:last-child{
    text-align: right;
}
#sty_tab tr td input, #sty_tab tr td select{
    height: 20px; line-height: 25px; text-align: left;
}
</style>
<span style="display:none">
	<span id="mi_contact_group_sample">
		<div id="input_group_#xxxx#" style="text-align:left;">
			<input  name="mi_contact_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi-input" style="width: 15%;"><input  name="mi_contact_group[#xxxx#][contact_name]" type="text" id="contact_name_#xxxx#" style="width:100%;" tabindex="-1" /></span>
            <span class="multi-input" style="width: 10%;"><input  name="mi_contact_group[#xxxx#][contact_brithday]" type="text" id="contact_brithday_#xxxx#" style="width:100%;" class="choose-date" /></span>
            <span class="multi-input" style="width: 10%;"><input  name="mi_contact_group[#xxxx#][contact_regency]" type="text" id="contact_regency_#xxxx#" style="width:100%;" /></span>
			<span class="multi-input" style="width: 10%;"><input  name="mi_contact_group[#xxxx#][contact_phone]" type="text" id="contact_phone_#xxxx#" style="width:100%;" /></span>
            <span class="multi-input" style="width: 10%;"><input  name="mi_contact_group[#xxxx#][contact_mobile]" type="text" id="contact_mobile_#xxxx#" oninput="check_length(this.value);" style="width:100%;" /></span>
			<span class="multi-input" style="width: 15%;"><input  name="mi_contact_group[#xxxx#][contact_email]" type="text" id="contact_email_#xxxx#" style="width:100%;" /></span>
			<span class="multi-input" style="width: 15%;"><input  name="mi_contact_group[#xxxx#][contact_given]" type="text" id="contact_given_#xxxx#" style="width:100%;" /></span>
            <span class="multi-input" style="width: 10%;">
                <select   name="mi_contact_group[#xxxx#][contact_status]" id="contact_status_#xxxx#" style="width:100%; height: 25px;">
					<option value="Đang hoạt động">Đang hoạt động</option>
                    <option value="Ngừng hoạt động">Ngừng hoạt động</option>
				</select>
            </span>
            <span class="multi-input">
				<span style="width:24px;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_contact_group','#xxxx#','group_');" style="cursor:pointer;"/></span></span>
             <br clear="all">
		</div>
	</span> 
</span>
<div class="customer_type-bound">
<form name="EditCustomerForm" method="post" enctype="multipart/form-data">
<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table cellpadding="0" cellspacing="0" width="960px" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="50%" class="form-title">&nbsp &nbsp<?php echo $this->map['title'];?></td>
            <td width="50%" style="text-align: right;">
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input class="w3-btn w3-orange w3-hover-orange w3-text-white w3-hover-text-white" type="button" name="save"  value="<?php echo Portal::language('Save');?>" onclick="check_TA();" style="margin-right: 10px; padding: 5px; text-transform: uppercase;" /><?php }?>
				<input class="w3-btn w3-green w3-hover-green w3-text-white" type="button" value="<?php echo Portal::language('back');?>" onclick="window.location.href='<?php echo Url::build_current(array('group_id','act'));?>'" style="margin-right: 10px; padding: 5px; text-transform: uppercase;" />
            </td>
        </tr>
    </table>
	<div class="content" style="float: left;">
		<?php if(Form::$current->is_error()){?><br><div><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
        <table border="0" cellspacing="0" cellpadding="0">
            <tr valign="top">
            	<td>
                	<fieldset>
                    <legend class="w3-text-black w3-padding" style="text-transform: uppercase;"><?php echo Portal::language('main_infor');?> - <span style="color: red; display: none;">(*) Nguồn khách TA, OTA, KHÁCH NHÀ HÀNG, CORPORATE, BOOK QUA WEBSITE bắt buộc phải nhập Địa chỉ, Số điện thoại</span></legend>
                	<table border="0" cellspacing="0" cellpadding="2" id="style_table" style="width: 100%;">
                        <table id="sty_tab" style="width: 100%;">
                        <tr class="style_tr">
                            <!---<td align="right">
                                <?php echo Portal::language('update_date');?>:
                            </td>--->
                            <td align="right">                        
                                <!---<?php if($_REQUEST['page_title']=="edit"){ ?>
                                <input  name="start_date" id="start_date" tabindex="-1" readonly="" style="border: none; float: left;" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>">
                                <?php }else{ ?>
                                <input name="start_date" type="text" id="start_date" tabindex="-1" readonly="" value="<?php echo $_REQUEST['date_start']; ?>" style="border: none; float: left;" />
                                <?php } ?>--->
                                <?php echo Portal::language('code');?>:
                            </td>
                            <td style="width: 15%;">
                                <?php if($_REQUEST['page_title']=="edit"){ ?>
                                <input  name="code" id="code" tabindex="-1" readonly="" style="border: none;"/ type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>">
                                <?php }else{ ?>
                                <input name="code" type="text" id="code" tabindex="-1" readonly="" value="<?php echo $_REQUEST['code_new']; ?>" style="border: none;" />
                                <?php } ?>
                            </td>
                            
                            <td align="right" style="width: 15%;">
                                <?php echo Portal::language('style_customer');?><span style="color: red;"> (*) </span>:
                            </td>
                            <td style="width: 15%;">
                                <select  name="group_id" id="group_id" style="width:100%; height: 25px;" ><?php
					if(isset($this->map['group_id_list']))
					{
						foreach($this->map['group_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('group_id',isset($this->map['group_id'])?$this->map['group_id']:''))
                    echo "<script>$('group_id').value = \"".addslashes(URL::get('group_id',isset($this->map['group_id'])?$this->map['group_id']:''))."\";</script>";
                    ?>
	</select>
                            </td>
                            <td style="width: 15%;" align="right">
                                <?php if(isset($this->map['customers'][$id]['group_id']) and ($this->map['customers'][$id]['group_id']!="WKIN")){ ?>
                                <p class="label" id="lbl_tax_code"><?php echo Portal::language('tax_code');?><!--<span style="color: red;"> (*)  </span>-->:</p>
                            </td>
                            <td colspan="2" style="width: 15%;">
                                <input  name="tax_code" id="tax_code" oninput="check_length(this.value);"  style="width:100%;"/ type ="text" value="<?php echo String::html_normalize(URL::get('tax_code'));?>">
                            </td>
                            <td align="right" style="width: 15%;">
                                <?php }else{ ?>                                
                                <p class="label" id="lbl_tax_code"><?php echo Portal::language('tax_code');?>:</p>
                            </td>
                            <td colspan="2" style="width: 15%;">
                                <input  name="tax_code" id="tax_code" style="width:100%;"/ type ="text" value="<?php echo String::html_normalize(URL::get('tax_code'));?>">
                            </td>
                                <?php } ?>
                        </tr>
                        <tr class="style_tr">                            
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('company_name_def');?><span style="color: red;"> (*) </span>:
                            </td>
                            <td style="width: 15%;">
                                <input  name="name" id="name" tabindex="-1" style="width: 100%; text-transform: uppercase;"/ type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>">
                            </td>
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('company_name_full');?>:
                            </td>
                            <td colspan="3" style="width: 45%;">
                                <input  name="def_name" id="def_name" tabindex="-1" style="width: 100%; text-transform: uppercase;"/ type ="text" value="<?php echo String::html_normalize(URL::get('def_name'));?>">
                            </td>
                        </tr>
                        <tr class="style_tr">
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('address_company');?>:
                            </td>
                            <td style="width: 75%;" colspan="5">
                                <input  name="address" id="address" style="width:100%;" / type ="text" value="<?php echo String::html_normalize(URL::get('address'));?>">
                            </td>
                            
                        </tr>
                        <tr class="style_tr">
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('national');?>:
                            </td>
                            <td style="width: 15%;">
                                <select  name="country" id="country" onchange="get_city();" style="float: left; width:100%; height: 25px;"><?php
					if(isset($this->map['country_list']))
					{
						foreach($this->map['country_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('country',isset($this->map['country'])?$this->map['country']:''))
                    echo "<script>$('country').value = \"".addslashes(URL::get('country',isset($this->map['country'])?$this->map['country']:''))."\";</script>";
                    ?>
	</select>
                            </td>
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('city');?>:
                            </td>
                            <td style="width: 15%;">
                                <select  name="city" id="city" style="width:100%; height: 25px;" onchange="get_district();"><?php
					if(isset($this->map['city_list']))
					{
						foreach($this->map['city_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('city',isset($this->map['city'])?$this->map['city']:''))
                    echo "<script>$('city').value = \"".addslashes(URL::get('city',isset($this->map['city'])?$this->map['city']:''))."\";</script>";
                    ?>
	</select>
                            </td>
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('district');?>:
                            </td>
                            <td style="width: 15%;">
                                <select  name="district" id="district" style="width:100%; height: 25px;" ><?php
					if(isset($this->map['district_list']))
					{
						foreach($this->map['district_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('district',isset($this->map['district'])?$this->map['district']:''))
                    echo "<script>$('district').value = \"".addslashes(URL::get('district',isset($this->map['district'])?$this->map['district']:''))."\";</script>";
                    ?>
	</select>
                            </td>
                            
                        </tr>
                        <tr class="style_tr">
                            
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('email');?>:
                            </td>
                            <td style="width: 15%;">                            
                                <input  name="email" id="email" style="width:100%;"/ type ="text" value="<?php echo String::html_normalize(URL::get('email'));?>">
                            </td>
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('telephone_company');?>:
                            </td>
                            <td style="width: 15%;">
                                <input  name="mobile" id="mobile" oninput="check_length(this.value);"  style="width:100%;" / type ="text" value="<?php echo String::html_normalize(URL::get('mobile'));?>">
                            </td>
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('fax');?>:
                            </td>
                            <td style="width: 15%;">
                                <input  name="fax" id="fax" oninput="check_length(this.value);"  style="width:100%;"/ type ="tel" value="<?php echo String::html_normalize(URL::get('fax'));?>">
                            </td>
                        </tr>
                        <tr class="style_tr">
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('bank_account');?>:
                            </td>
                            <td style="width: 15%;">                                
                                <input  name="bank_code" id="bank_code" style="width: 100%;" / type ="text" value="<?php echo String::html_normalize(URL::get('bank_code'));?>">
                            </td>
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('bank');?>:
                            </td>
                            <td style="width: 15%;">                            
                                <select  name="bank_id" id="bank_id" style="float: left; width:100%; height: 25px;"><?php
					if(isset($this->map['bank_id_list']))
					{
						foreach($this->map['bank_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('bank_id',isset($this->map['bank_id'])?$this->map['bank_id']:''))
                    echo "<script>$('bank_id').value = \"".addslashes(URL::get('bank_id',isset($this->map['bank_id'])?$this->map['bank_id']:''))."\";</script>";
                    ?>
	</select>
                            </td>
                             
                        </tr>
                        <tr class="style_tr">
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('status');?>:
                            </td>
                            <td style="width: 15%;">                            
                                <select  name="status" style="width:100%; height: 25px;" id="status"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	</select>
                            </td>
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('creart_date');?>:
                            </td>
                            <td style="width: 15%;">                          
                                <input  name="creart_date" id="creart_date" style="float: left; width: 100%; "/ type ="text" value="<?php echo String::html_normalize(URL::get('creart_date'));?>">
                            </td>
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('sectors');?>:
                            </td>
                            <td style="width: 15%;">
                                <select  name="sectors_id" id="sectors_id" style="width:100%; height: 25px;"><?php
					if(isset($this->map['sectors_id_list']))
					{
						foreach($this->map['sectors_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('sectors_id',isset($this->map['sectors_id'])?$this->map['sectors_id']:''))
                    echo "<script>$('sectors_id').value = \"".addslashes(URL::get('sectors_id',isset($this->map['sectors_id'])?$this->map['sectors_id']:''))."\";</script>";
                    ?>
	</select>
                            </td>
                        </tr>
                        <tr class="style_tr">
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('note');?>:
                            </td>
                            <td style="width: 75%;" colspan="5">
                                <textarea  name="note" id="note" style="width: 100%; height: 100px;"><?php echo String::html_normalize(URL::get('note',''));?></textarea>
                            </td>
                        </tr>
                        <tr class="style_tr">
                            <td style="width: 15%;" align="right">
                                <?php echo Portal::language('sale_code');?>:
                            </td>
                            <td style="width: 15%;">
                                <select  name="sale_code" id="sale_code" style="width:100%; height: 25px;"><?php
					if(isset($this->map['sale_code_list']))
					{
						foreach($this->map['sale_code_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('sale_code',isset($this->map['sale_code'])?$this->map['sale_code']:''))
                    echo "<script>$('sale_code').value = \"".addslashes(URL::get('sale_code',isset($this->map['sale_code'])?$this->map['sale_code']:''))."\";</script>";
                    ?>
	</select>
                            </td>
                            <td colspan="2" align="right">
                                 <?php echo Portal::language('user_update');?>:
                            </td>
                            <td>
                                <?php if($_REQUEST['page_title']=="edit"){ ?>
                                <input  name="user_id" id="user_id" tabindex="-1" readonly="" style=" border: none; float: left; width:150px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('user_id'));?>">
                                <?php }else{ ?>
                                <input name="user_id" type="text" id="user_id" tabindex="-1" readonly="" value="<?php echo $_REQUEST['user_id_code']; ?>" style=" border:none; float:left; width:150px;" />
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                          <td colspan="2" class="label">&nbsp;</td>
                      </tr>
                      
                      </table>
                      <fieldset style="width: 80%; margin: 0px auto;">
                        <legend>Upload File</legend>
                        <table style="width: 100%;" >
                            <tr style="background: #ddd;">
                                <th style="text-align: center;">UPLOAD FILE</th>
                                <th style="text-align: center;">FILE (<label id="num_file_lbl"><?php echo sizeof($this->map['list_file']); ?></label>)</th>
                            </tr>
                            <tr>
                                <td style="text-align: center;"><?php echo Portal::language('select_file');?>:<br /><input type="file" name="file" id="file" /></td>
                                <td style="border-left: 1px solid #ddd; text-align: left;">
                                    <table id="file">
                                        <?php if(isset($this->map['list_file']) and is_array($this->map['list_file'])){ foreach($this->map['list_file'] as $key1=>&$item1){if($key1!='current'){$this->map['list_file']['current'] = &$item1;?>
                                            <tr id="tr_<?php echo $this->map['list_file']['current']['id'];?>">
                                                <td>File name: <?php echo $this->map['list_file']['current']['file_name'];?></td>
                                                <td style="width: 50px; height: 30px; position: relative; font-family: FontAwesome;"><a href="?page=customer&cmd=download&customer_code=<?php echo $this->map['list_file']['current']['customer_code'];?>&id=<?php echo $this->map['list_file']['current']['id'];?>" target="_blank" style="height: 30px; width: auto; line-height: 30px; padding: 2px 2px; background: #00b9f2; color: #ffffff; border-radius: 5px; border: 2px solid #ffffff; box-shadow: 0px 0px 5px #000000; text-decoration: none;"><?php echo Portal::language('download');?></a></td>
                                                <td style="width: 50px; height: 30px; position: relative; font-family: FontAwesome;"><a href="#" id="<?php echo $this->map['list_file']['current']['id'];?>" onclick="delete_file(this);" style="height: 30px; width: auto; line-height: 30px; padding: 2px 2px; background: red; color: #ffffff; border-radius: 5px; border: 2px solid #ffffff; box-shadow: 0px 0px 5px #000000; text-decoration: none;"><?php echo Portal::language('delete');?></a></td>
                                            </tr>
                                        <?php }}unset($this->map['list_file']['current']);} ?>
                                    </table>
                                </td>
                            </tr>
                            <tr style="display: none;">
                                <input  name="delete_file" id="delete_file" style="display: none;" / type ="text" value="<?php echo String::html_normalize(URL::get('delete_file'));?>">
                            </tr>
                        </table>
                      </fieldset>
						<tr>
                       	<td colspan="2">
						<fieldset>
							<legend class="title"><?php echo Portal::language('contact_person_info');?></legend>                            
                            <span id="mi_contact_group_all_elems" style="text-align:left;">
							<span>
                                <span class="multi-input-header" style="width:15%;"><?php echo Portal::language('contact_name');?></span>
                                <span class="multi-input-header" style="width:10%;"><?php echo Portal::language('contact_brithday');?></span>
                                <span class="multi-input-header" style="width:10%;"><?php echo Portal::language('contact_regency');?></span>
								<span class="multi-input-header" style="width:10%;"><?php echo Portal::language('contact_phone');?></span>
								<span class="multi-input-header" style="width:10%;"><?php echo Portal::language('contact_mobile');?></span>
                                <span class="multi-input-header" style="width:15%;"><?php echo Portal::language('contact_email');?></span>
                                <span class="multi-input-header" style="width:15%;"><?php echo Portal::language('contact_given');?></span>
                                <span class="multi-input-header" style="width:10%;"><?php echo Portal::language('contact_status');?></span>
							</span>                            
                            </span>
							<div>
                                <input type="button" value="<?php echo Portal::language('add_contact');?>" onclick="mi_add_new_row('mi_contact_group'); update_input();" class="button-medium-add"/>
                            </div>
                        </fieldset>
                        </td>
                        </tr>
                    </table>
                    </fieldset>
                </td>
            </tr>
        </table>
	</div >
    
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript">
    jQuery('#creart_date').datepicker();
    function delete_file(obj){
        var id = obj.id;
        var list_delete = jQuery("#delete_file").val();
        list_delete = list_delete+","+id;
        jQuery("#delete_file").val(list_delete);
        jQuery("#tr_"+id).css('display','none');
        var num_file = Number(jQuery("#num_file_lbl").html());
        num_file = num_file - 1;
        jQuery("#num_file_lbl").html(num_file);
    }
</script>
<script type="text/javascript">
jQuery(document).ready(function()
{
    var code_group = jQuery("#group_id").val().replace('#',"");
    //console.log(code_group);
    if(code_group=="WKIN"){
        
        jQuery("#lbl_tax_code").css("display","none");
        jQuery("#tax_code").css("display","none");
    }
    else{
         jQuery("#lbl_tax_code").css("display","block");
        jQuery("#tax_code").css("display","block");
    }
    for(var i=101;i<=input_count;i++){
        jQuery('#contact_brithday_'+input_count).datepicker();
    }
    jQuery('#creart_date').datepicker();
});
</script>
<script type="text/javascript">
mi_init_rows('mi_contact_group',<?php echo isset($_REQUEST['mi_contact_group'])?String::array2js($_REQUEST['mi_contact_group']):'{}';?>);
function check_tax_code(){
    var code_group = jQuery("#group_id").val().replace('#',"");

    //console.log(code_group);
    if(code_group=="WKIN"){
        jQuery("#lbl_tax_code").css("display","none");
        jQuery("#tax_code").css("display","none");
    }
    else{
        jQuery("#lbl_tax_code").css("display","block");
        jQuery("#tax_code").css("display","block");
    }
    
}
function update_input(){
    for(var i=101;i<=input_count;i++){
        jQuery('#contact_brithday_'+input_count).datepicker();
    }
}
/*jQuery("#code").autocomplete({
		url: 'r_get_customer.php?code=1'
});
jQuery("#NAME").autocomplete({
		url: 'r_get_customer.php?name=1'
});*/
<?php
    if($_REQUEST['page_title']=="add"){
?>
<?php } ?>
function get_city(){
        var country_id = jQuery("#country").val().replace('#',"");
        if (window.XMLHttpRequest)
        {
            xmlhttp=new XMLHttpRequest();
        }
        else
        {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var objs = jQuery.parseJSON(text_reponse);
                var txt=document.getElementById("city").innerHTML;
                var new_content = '<option value="" onchange=\"get_district();\" >----select-city----</option>';                        
                for(var obj in objs)
                {
                    new_content +='<option value="'+obj+'" onchange="get_district();">'+objs[obj]+'</option>';
                }
                document.getElementById("city").innerHTML = new_content;
            }
        }
        xmlhttp.open("GET","db_customer.php?data=get_city&country="+country_id,true);
        xmlhttp.send();
}
function get_district(){
        var city_id = jQuery("#city").val().replace('#',"");
        if (window.XMLHttpRequest)
        {
            xmlhttp=new XMLHttpRequest();
        }
        else
        {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var otbjs = jQuery.parseJSON(text_reponse);
                var txt=document.getElementById("district").innerHTML;
                var new_contentt = '<option value="" >----select-city----</option>';                        
                for(var otbj in otbjs)
                {
                    new_contentt +='<option value="'+otbj+'" >'+otbjs[otbj]+'</option>';
                }
                document.getElementById("district").innerHTML = new_contentt;
                
            }
        }
        xmlhttp.open("GET","db_customer.php?data=get_district&city="+city_id,true);
        xmlhttp.send();
}
function check_length(i)
{
    console.log(i.length);
    if(i.length>=20)
    {
        alert('số kí tự quá dài');
        return false;
    }
}
function is_tax_code($tax_code){
    $check = true;
    if($tax_code.length==10){
        $tax_code_arr = $tax_code.split('');
        for($i=0;$i<10;$i++){
            if(to_numeric($tax_code[$i])>=0 && to_numeric($tax_code[$i])<=9){
                // true
            }else{
                $check = false;
            }
        }
    }else if($tax_code.length==14){
        $tax_code_arr = $tax_code.split('-');
        if($tax_code_arr.length!=2){
            $check = false;
        }else{
            $arr1 = $tax_code_arr[0].split('');
            for($i=0;$i<10;$i++){
                if(to_numeric($arr1[$i])>=0 && to_numeric($arr1[$i])<=9){
                    // true
                }else{
                    $check = false;
                }
            }
            $arr2 = $tax_code_arr[1].split('');
            for($i=0;$i<3;$i++){
                if(to_numeric($arr2[$i])>=0 && to_numeric($arr2[$i])<=9){
                    // true
                }else{
                    $check = false;
                }
            }
        }
    }else{
        $check = false;
    }
    return $check;
}
function Check_Tax_Code(){
    if(jQuery("#tax_code").val()!=''){
        if(jQuery("#tax_code").val().length!=10 && jQuery("#tax_code").val().length!=14){
            alert('Bạn đang nhập sai mã số thuế, vui lòng kiểm tra lại!');
            $check = false;
        }else{
            if(to_numeric(jQuery("#tax_code").val().split(' ').length)!=1){
                alert('Mã số thuế không được nhập kí tự trống, vui lòng kiểm tra lại!');
                $check = false;
            }else if(!is_tax_code(jQuery("#tax_code").val())){
                alert('Mã số thuế không đúng định dạng, vui lòng kiểm tra lại!');
                $check = false;
            }
            else{
                <?php echo 'var block_id = '.Module::block_id().';';?>
                <?php echo 'var cmd = \''.Url::get('cmd').'\';';?>
                var id=<?php echo Url::get('cmd')=='edit'?Url::get('id'):'0';?>;
                jQuery.ajax({
        					url:"form.php?block_id="+block_id,
        					type:"POST",
        					data:{check_conflix_tax_code:1,cmd_check:cmd,tax_code:jQuery("#tax_code").val(),id_check:id},
        					success:function(html)
                            {
                                if(to_numeric(html)!=2){
                                    alert('mã số thuế : '+jQuery("#tax_code").val()+' đang bị trùng');
                                }else{
                                    
                                    EditCustomerForm.submit();
                                }
        					}
        		});
            }
        }
    }else{
        EditCustomerForm.submit();
    }
}
/*function check_TA(){
    $check = true;
    if(jQuery("#group_id").val()=='MS' || jQuery("#group_id").val()=='SỞ BAN NGÀNH'){
        if(jQuery("#name").val().trim()=='' || jQuery("#group_id").val().trim()==''){
            alert('Bạn đang nhập thiếu thông tin bắt buộc, vui lòng kiểm tra lại!');
            $check = false;
        }else{
            Check_Tax_Code()
        }
    }else{
        if(jQuery("#name").val().trim()=='' || jQuery("#address").val().trim()=='' || jQuery("#mobile").val().trim()==''){
            alert('Bạn đang nhập thiếu thông tin bắt buộc, vui lòng kiểm tra lại!');
            $check = false;
        }else{
            Check_Tax_Code()
        }
    }
}*/
function check_TA(){
    $check = true;
    $notify = '';
    if(jQuery('#name').val().trim() == '')
    {
        $notify += 'Bạn chưa nhập mã công ty. \n';
        $check = false;
    }
    if(jQuery('#group_id').val() == '')
    {
        $notify += 'Bạn chưa nhập loại nguồn khách. \n';
        $check = false;        
    }
    $notify += 'Xin cảm ơn!';
    if($check == false)
    {
        alert($notify);
        return false;
    }else
    {
        EditCustomerForm.submit();
    }
}
</script>
