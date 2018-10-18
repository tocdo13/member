<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<style>
#content_search{
    background: #fff;
}
#content_search:hover{
    background: #f6f6f6;
}
</style>
<div id="header_search" style="margin: 5px auto; width: 500px;">
    <table>
        <tr>
            <td><div style="border-radius: 50%; width: 150px; height: 150px; overflow: hidden; background: #eee; margin: 10px; border:2px solid #eee; box-shadow: 0px 0px 5px #000;"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 150px; height: auto;" /></div></td>
            <td>
                <h3 style="text-transform: uppercase;"><?php echo HOTEL_NAME; ?></h3>
                <p><b>ADD:</b><i> <?php echo HOTEL_ADDRESS; ?></i><br />
                <b>TEL:</b> <i></i><?php echo HOTEL_PHONE; ?></i> | <b>FAX:</b> <i><?php echo HOTEL_FAX; ?></i><br />
                <b>EMAIL:</b> <i><?php echo HOTEL_EMAIL; ?></i><br />
                 <b>WEBSITE:</b> <i><?php echo HOTEL_WEBSITE; ?></i></p>
            </td>
        </tr>
    </table>
</div>
<div id="content_search" style="margin: 5px auto; width: 500px; border: 1px solid #999; border-radius:10px;">
    <h4 style="text-transform: uppercase; width: 100%; text-align: center; margin: 10px auto;"><?php echo Portal::language('detail_sale_in_date');?></h4>
    <form name="SearchForm" method="post">
    <fieldset style="width: 300px; margin: 10px auto; background: #fff;">
        <legend>STEP 1</legend>
        <table>
            <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
            <tr>
                <td> <?php echo Portal::language('hotel');?> </td>
                <td><select  name="portal_id" id="portal_id" style="width: 200px;"><?php
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
	</select></td>
            </tr>
            <?php }?>
            <!-- code sale
            <tr>
                <td width="1%" nowrap="nowrap"><p><?php echo Portal::language('sale_code');?></p></td>
                <td nowrap="nowrap"><p><select  name="sale_code" id="sale_code" style="width: 100px;"><?php
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
	</select></p></td>
            </tr>
            -->
        </table>
    </fieldset>
    <fieldset style="width: 200px; margin: 10px auto; background: #fff;">
        <legend>STEP 2</legend>
        <table>
            <tr>
                <td align="right"><?php echo Portal::language('date_from');?></td>
                <td><input  name="date_from" id="date_from" tabindex="1" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>"></td>
            </tr>
            <tr>
                <td align="right"><?php echo Portal::language('date_to');?></td>
                <td><input  name="date_to" id="date_to" tabindex="2" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"></td>
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
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<script type="text/javascript">
	jQuery("#date_from").datepicker();
	jQuery("#date_to").datepicker();
	$('portal_id').value = '<?php echo PORTAL_ID;?>';
</script>