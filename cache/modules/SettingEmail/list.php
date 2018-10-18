<style>
.setting-body{padding:10px}
.etabs { margin: 0px; padding: 0; }
.tab { display: inline-block; zoom:1; *display:inline; background: #eee; border: solid 1px #999; border-bottom: none; -moz-border-radius: 4px 4px 0 0; -webkit-border-radius: 4px 4px 0 0; }
.tab a { font-size: 14px; line-height: 2em; display: block; padding: 0 10px; outline: none; }
.tab a:hover { text-decoration: none; }
.tab.active { background: #fff; padding-top: 6px; position: relative; top: 1px; border-color: #666; }
.tab a.active { color:#0000FF;}
.tab a.active:hover{text-decoration:none;}
.tab-container{}
.tab-content{padding:10px;min-height:700px;}
.tab-container div.active { border:1px solid #666; -moz-border-radius: 0px 4px 4px 4px; -webkit-border-radius: 0px 4px 4px 4px; }
.tab-container .panel-container { background: #fff; border: solid #666 1px; padding: 10px; -moz-border-radius: 0 4px 4px 4px; -webkit-border-radius: 0 4px 4px 4px; }
.setting-field { border-bottom: none !important;}
</style>
<form name="SettingEmailForm" id="SettingEmailForm" method="POST" enctype="multipart/form-data">
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
    	<td>
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="70%"><?php echo Portal::language('setting_manage');?></td>
					<td align="right"><input name="submit11" type="button" value="<?php echo Portal::language('Save');?>" onclick="check_submit_form();" /></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td>
			<div class="setting-body 0" id="setting_info">
				<div class="setting-notice-bound">
				<?php if(Url::get('act')=='succ'){?>
				<div class="setting-notice"><?php echo Portal::language('update_success');?></div>
				<script>jQuery('.setting-notice').fadeOut(2000);</script>
				<?php }?>
				</div>
				<div class="setting-error"><?php Form::$current->error_messages();?></div>
				<div id="setting_tab" class="tab-container">
					<ul>
                        <li class="tab"><a href="#email"><?php echo Portal::language('Email');?></a></li>
					</ul>
                    	
                <div id="email" class="tab-content">
                    
                    <div class="setting-field" style="display: none;">
						<label for="email_invoice_creart"><?php echo Portal::language('send_email_creat_date');?></label>
						<input  name="email_date_creart" id="email_date_creart" / type ="text" value="<?php echo String::html_normalize(URL::get('email_date_creart'));?>">
					</div>
                    
                    <div class="setting-field">
						<label for="email_invoice_creart"><?php echo Portal::language('send_email_creat_folio');?></label>
						<input  name="email_invoice_creart" id="email_invoice_creart" onchange="check_hide_config()" / type ="text" value="<?php echo String::html_normalize(URL::get('email_invoice_creart'));?>">
					</div>
                    
                    <div class="setting-field manager_invoice">
                        <label for="room_invoice"><?php echo Portal::language('room_invoice');?></label>
                        <input  name="room_invoice" type="checkbox" id="room_invoice" <?php if(Url::get('room_invoice')){echo 'checked';}?> value="0"/>
                    </div>
                    
                    <div class="setting-field manager_invoice">
                        <label for="bar_invoice"><?php echo Portal::language('bar_invoice');?></label>
                        <input  name="bar_invoice" type="checkbox" id="bar_invoice" <?php if(Url::get('bar_invoice')){echo 'checked';}?> value="0"/>
                    </div>
                    
                    <div class="setting-field manager_invoice">
                        <label for="spa_invoice"><?php echo Portal::language('spa_invoice');?></label>
                        <input  name="spa_invoice" type="checkbox" id="spa_invoice" <?php if(Url::get('spa_invoice')){echo 'checked';}?> value="0"/>
                    </div>
                    
                    <div class="setting-field">
                        <label for="email_check_out"><?php echo Portal::language('email_check_out');?></label>
                        <input  name="email_check_out" type="checkbox" id="email_check_out" <?php if(Url::get('email_check_out')){echo 'checked';}?> value="0"/>
                    </div>
                
                    
                    <div class="setting-field">
						<label for="email_invoice"><?php echo Portal::language('email_invoice');?></label>
						<input  name="email_invoice" id="email_invoice" / type ="text" value="<?php echo String::html_normalize(URL::get('email_invoice'));?>">
					</div>
                    <div class="setting-field">
						<label for="email_invoice_password"><?php echo Portal::language('email_invoice_password');?></label>
						<input  name="email_invoice_password" id="email_invoice_password" / type ="password" value="<?php echo String::html_normalize(URL::get('email_invoice_password'));?>">
                        <?php if(User::can_admin(false,ANY_CATEGORY)){ ?>
                        <img src="packages/hotel/modules/SettingEmail/view.png" onclick="view_pass('email_invoice_password');" style="cursor: pointer;" />
                        <?php } ?>
					</div>
                    
                    <div style="margin-left: 350px;">
                        <label id="invoice_toggle"><?php echo Portal::language('advance');?></label>
                        <div id="advance_toogle">
                            <div class="setting-field">
                                <label for="email_invoice_smtp"><?php echo Portal::language('email_invoice_SMTP');?></label>
                                <input  name="email_invoice_smtp" id="email_invoice_smtp" value="smtp.gmail.com" / type ="text" value="<?php echo String::html_normalize(URL::get('email_invoice_smtp'));?>">
                            </div>
                            
                            <div class="setting-field">
                                <label for="email_invoice_port"><?php echo Portal::language('email_invoice_PORT');?></label>
                                <input  name="email_invoice_port" id="email_invoice_port" value="465" / type ="text" value="<?php echo String::html_normalize(URL::get('email_invoice_port'));?>">
                            </div>
                            <!--<p><i>Note: Nếu tài khoản của gmail thì để SMTP là smtp.gmail.com,zoho:smtp.zoho.com PORT thường là 465</i></p>-->
                        </div>
                    </div>
                    <div class="setting-field">
						<label for="email_invoice_content"><?php echo Portal::language('email_invoice_content');?></label>
						<textarea  name="email_invoice_content" id="email_invoice_content" style="width: 700px; height: 100px;"><?php echo String::html_normalize(URL::get('email_invoice_content',''));?></textarea> 
					</div>
                    
                    <div class="setting-field">
						<label for="email_marketing"><?php echo Portal::language('email_marketing');?></label>
						<input  name="email_marketing" id="email_marketing" / type ="text" value="<?php echo String::html_normalize(URL::get('email_marketing'));?>">
					</div>
                    <div class="setting-field">
						<label for="email_marketing_password"><?php echo Portal::language('email_marketing_password');?></label>
						<input  name="email_marketing_password" id="email_marketing_password" / type ="password" value="<?php echo String::html_normalize(URL::get('email_marketing_password'));?>">
                        <?php if(User::can_admin(false,ANY_CATEGORY)){ ?>
                        <img src="packages/hotel/modules/SettingEmail/view.png" onclick="view_pass('email_marketing_password');" style="cursor: pointer;" />
                        <?php } ?>
					</div>
                    <div style="margin-left: 350px;">
                        <label id="marketing_toggle"><?php echo Portal::language('advance');?></label>
                        <div id="advance_marketing_toogle">
                            <div class="setting-field">
                                <label for="email_marketing_smtp"><?php echo Portal::language('email_marketing_smtp');?></label>
                                <input  name="email_marketing_smtp" id="email_marketing_smtp" value="smtp.gmail.com" / type ="text" value="<?php echo String::html_normalize(URL::get('email_marketing_smtp'));?>">
                            </div>
                            
                            <div class="setting-field">
                                <label for="email_marketing_port"><?php echo Portal::language('email_marketing_port');?></label>
                                <input  name="email_marketing_port" id="email_marketing_port" value="465" / type ="text" value="<?php echo String::html_normalize(URL::get('email_marketing_port'));?>">
                            </div>
                            <!--<p><i>Note: Nếu tài khoản của gmail thì để SMTP là smtp.gmail.com, PORT thường là 465</i></p>-->
                        </div>
                    </div>
                    
                </div>
			</div>
        </div>
        </td>
    </tr>
</table>

<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
	jQuery(document).ready(function()
    {
		jQuery('#setting_tab').easytabs({animationSpeed:0});
        jQuery("#advance_toogle").hide();
        jQuery("#advance_marketing_toogle").hide();
        jQuery("#email_date_creart").datepicker();	
	})
    function view_pass(id)
    {
        var x = event.clientX;
        var y = event.clientY;
        console.log(x+"--"+y);
        content = '<div id="title_pass" style="position: fixed; top:'+y+'px; left: '+x+'px; border: 1px solid #555555; line-height: 25px; font-size: 15px; font-weight: bold; background: #000000; color: #FFFFFF; padding: 5px;;">'+jQuery("#"+id).val()+'</div>';
        jQuery("body").append(content);
        setTimeout(function(){ jQuery("#title_pass").remove(); },3000);
    }
    function check_submit_form()
    {
        if(jQuery('#email_invoice').val()!='')
        {
            if(jQuery('#email_invoice_password').val()=='')
            {
                alert('Email và mật khẩu không rỗng');
                jQuery('#email_invoice_password').focus().css('border','1px solid red');
                return false;
            }
        }
        else
        {
            jQuery('#email_invoice_password').css('border','1px solid fff');
        }
        
        if(jQuery('#email_marketing').val()!='')
        {
            if(jQuery('#email_marketing_password').val()=='')
            {
                alert('Email và mật khẩu không rỗng');
                jQuery('#email_marketing_password').focus().css('border','1px solid red');
                return false;
            }
        }
        else
        {
            jQuery('#email_marketing_password').css('border','1px solid fff');
        }
        //break;
        //alert('1');
        jQuery('#SettingEmailForm').submit();
        //SettingEmailForm.submit();
    }
    function check_hide_config()
    {
        if(jQuery('#email_invoice_creart').val()=='')
        {
            jQuery('.manager_invoice').css('display','none');
        }
        else
        {
            jQuery('.manager_invoice').css('display','block');
        }
        
    }
    
    jQuery("#invoice_toggle").click(function(){
      jQuery("#advance_toogle").toggle();
    });
    jQuery("#marketing_toggle").click(function(){
      jQuery("#advance_marketing_toogle").toggle();
    });
    
   
	
</script>