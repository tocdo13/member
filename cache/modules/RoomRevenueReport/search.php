<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<div id="header-search" style="width: 100%;">
    <table style="width: 100%;">
        <tr>
            <td style="width: 120px; text-align: center;"><div style="border-radius: 50%; width: 100px; height: 100px; overflow: hidden; background: #eee; margin: 10px; border:2px solid #eee; box-shadow: 0px 0px 5px #000;"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 100px; height: auto;" /></div></td>
            <td style="width: 300px; text-align: left;">
                <b style="text-transform: uppercase;"><?php echo HOTEL_NAME; ?></b><br />
                <b>Địa chỉ</b>: <?php echo HOTEL_ADDRESS;?><br />
    			<b>Tel</b>: <?php echo HOTEL_PHONE;?> | <b>Fax</b>:<?php echo HOTEL_FAX;?><br />
    			<b>Email</b>: <?php echo HOTEL_EMAIL;?> | <b>Website</b>:<?php echo HOTEL_WEBSITE;?>
            </td>
            <td style="text-align: right; padding-right: 10px;">
                <strong style="float: right;"><?php echo Portal::language('template_code');?></strong>
            </td>
        </tr>
    </table>
</div>
<div id="title-search" style="width: 100%;">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: center;"><h1 style="text-transform: uppercase;"><?php if(Url::get('type')==1){echo Portal::language('hotel_revenue_report');}elseif(Url::get('type')==2){echo Portal::language('room_revenue_report');}else{echo Portal::language('reception_report'); }?></h1></td>
        </tr>
        <tr>
            <!--<td style="text-align: center;">
            <?php 
				if((Portal::language()==1))
				{?>
        	B&aacute;o c&aacute;o n&agrave;y ch&#7881; &#273;&#432;a ra s&#7889; ti&#7873;n th&#7921;c t&#7871; thu &#273;&#432;&#7907;c trong kho&#7843;ng th&#7901;i gian &#273;&#432;&#7907;c l&#7921;a ch&#7885;n (ngh&#297;a l&agrave; khi kh&aacute;ch &#273;&atilde; checkout).  
             <?php }else{ ?>
            This report is based on the current receipt (when receptionist checked guest out). If you want to view expanded revenue, please choose <a href="?page=monthly_room_report" target="_blank">"Room status report"</a>!
            
				<?php
				}
				?>
            </td>-->
        </tr>
    </table>
</div>
<div id="content-search" style="width: 500px; margin: 10px auto;">
    <form name="SearchForm" method="post">
        <fieldset>
            <legend>Step 1</legend>
            <table style="width: 500px; margin: 0px auto;">
                <tr>
                    <td style="text-align: right;"><?php echo Portal::language('hotel');?> :</td>
                    <td style="text-align: left;"><select  name="portal_id" id="portal_id" <!--onchange="SearchForm.submit();"--><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	></select></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width: 400px; margin: 5px auto;">
            <legend>Step 2</legend>
            <table style="width: 400px; margin: 0px auto;">
                <tr>
                    <td style="text-align: right;"><?php echo Portal::language('room_level');?> :</td>
                    <td style="text-align: left;"><select  name="room_level_id" id="room_level_id" style="width: 150px"><?php
					if(isset($this->map['room_level_id_list']))
					{
						foreach($this->map['room_level_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_level_id',isset($this->map['room_level_id'])?$this->map['room_level_id']:''))
                    echo "<script>$('room_level_id').value = \"".addslashes(URL::get('room_level_id',isset($this->map['room_level_id'])?$this->map['room_level_id']:''))."\";</script>";
                    ?>
	</select></td>
                </tr>
                <!--<tr>
                    <td style="text-align: right;"><?php echo Portal::language('customer_id');?> :</td>
                    <td style="text-align: left;"><input  name="customer_code" id="customer_code" style="width: 150px" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_code'));?>"></td>
                </tr>-->
                <tr>
                    <td style="text-align: right;"><?php echo Portal::language('revenue');?> :</td>
                    <td style="text-align: left;">
                        <select   name="revenue" id="revenue" style="width: 150px" >
        					<option value="CHECKOUT" selected="selected"><?php echo Portal::language('revenue_real');?></option>
        					<option value="BOOKED"><?php echo Portal::language('booked');?></option>
        					<option value="ALL"><?php echo Portal::language('revenue_expected');?></option>
        				</select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;"><?php echo Portal::language('reservation_type');?> :</td>
                    <td style="text-align: left;"><select  name="reservation_type_id" id="reservation_type_id" style="width: 150px"><?php
					if(isset($this->map['reservation_type_id_list']))
					{
						foreach($this->map['reservation_type_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('reservation_type_id',isset($this->map['reservation_type_id'])?$this->map['reservation_type_id']:''))
                    echo "<script>$('reservation_type_id').value = \"".addslashes(URL::get('reservation_type_id',isset($this->map['reservation_type_id'])?$this->map['reservation_type_id']:''))."\";</script>";
                    ?>
	</select></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width: 300px; margin: 0px auto;">
            <legend>Step 3</legend>
            <table style="width: 300px; margin: 0px auto;">
                <tr>
                    <td align="right"><?php echo Portal::language('from_day');?>:</td>
                    <td><input  name="date_from" id="date_from" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>"></td>
                </tr>
                <tr>
                    <td align="right"><?php echo Portal::language('to_day');?>:</td>
                    <td><input  name="date_to" id="date_to" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="width: 200px; margin: 5px auto;">
            <legend>Step 4</legend>
            <table style="width: 200px;">
                <tr>
                    <td><?php echo Portal::language('line_per_page');?></td>
                    <td><input  name="line_per_page" type="text" id="line_per_page" value="999" size="4" maxlength="3" style="text-align:right"/></td>
                </tr>
                <tr>
                    <td><?php echo Portal::language('no_of_page');?></td>
                    <td><input name="no_of_page"  type="text" id="no_of_page" value="500" size="4" maxlength="3" style="text-align:right"/></td>
                </tr>
                <tr>
                    <td><?php echo Portal::language('from_page');?></td>
                    <td><input  name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
                </tr>
            </table>
        </fieldset>
        <div style="width: 265px; margin: 0px auto;">
            <input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  " style="width: 120px; height: 40px; line-height: 40px; text-align: center; background: #0161ba; border: none; border-radius:5px; box-shadow:0px 0px 3px #999; margin: 5px; font-size: 13px; font-weight: bold; text-transform: uppercase; cursor: pointer; color: #fff;"/>
            <input type="button" value="  <?php echo Portal::language('cancel');?>  " onclick="location='<?php echo Url::build('home');?>';" style="width: 120px; height: 40px; line-height: 40px; text-align: center; background: #85c4ff; border: none; border-radius:5px; box-shadow:0px 0px 3px #999; margin: 5px; font-size: 13px; font-weight: bold; text-transform: uppercase; cursor: pointer; color: #fff;"/>
        </div>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<script>
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }                
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
    
	jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();

</script>