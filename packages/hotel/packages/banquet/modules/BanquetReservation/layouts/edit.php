<!--?php echo"tet7";?-->
<div style="text-align:center;">
<div style="border:1px solid #CCCCCC;width:100%;margin-left:auto;margin-right:auto;margin-top:3px;">
<table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr><td width="100%">
		<form name="AddBanquetOrderForm" method="post">

        <!--Thông tin ngày tháng, tiêu đề, trạng thái-->
		<table cellspacing="0" cellpadding="5" border="0" width="100%" style="border-collapse:collapse;" bordercolor="#97ADC5">
		<tr height="25" bgcolor="#FFFFFF">
			<td align="left" bgcolor="#AAD5FF">
			<table width="100%" border="0">
			<tr>
				<td width="25%">
                    [[.create_date.]]: [[|date|]]
			    </td>
				<td width="50%" align="center" style="padding:0px" nowrap="nowrap">
                    <font style="font-size:20px; text-transform:uppercase;">[[.Banquet_order.]]</font>
                </td>
				<td width="25%" align="right" nowrap>
                    [[.currency.]]: <?php echo HOTEL_CURRENCY;?>
					<input  type="hidden" name="curr" value="<?php echo HOTEL_CURRENCY;?>"/>
				</td>
			</tr>
			</table>
            </td>
		</tr>
		<tr bgcolor="#F4F4F4">
    		<td bgcolor="#FFFFFF">
        		
               <!-- <?php if(Form::$current->is_error())
                {
                ?>
                <?php echo Form::$current->error_messages();?><br/>
                <?php
                }
                ?> -->

                <?php echo Form::$current->error_messages();?>
                <div align="left">
                    <br />
                    <br />
                    <table cellpadding="3" cellspacing="0" width="100%">
                        <tr>
                        <td>[[.banquet_type.]] <em style="color:red;">(*)</em></td>
                        <td class="full_price" <?php echo Url::get('party_category')=='FULL_PRICE'?'':'style="display:none;"';?>>[[.price_per_one.]] <em style="color:red;">(*)</em></td>
                    </tr>
                    <tr>
                        <td><select name="party_type" id="party_type" ></select></td> 
                        <td class="full_price" <?php echo Url::get('party_category')=='FULL_PRICE'?'':'style="display:none;"';?>>
                            <input name="price_per_people" type="text" id="price_per_people" style="text-align:right;" onkeyup="calculate();" class="input input_number format_number" maxlength="14"/>
                        </td> 
                                        
                    </tr>
                    <tr><td width="1%" align="right"><a href="#" onclick="window.location='?page=banquet_reservation&cmd='+$('party_type').value+'&portal=<?php echo PORTAL_ID;?>';" class="button-medium-add">Đặt tiệc</a></td></tr>
                    </table>
                </div>
    		</td>
		</tr>
		</table>
  </form>
  </td>
  </tr>
</table>
</div>
</div>