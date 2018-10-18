<style>
@media print{
    .search{
        display: none;
    }
}
</style>
<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?><?php $input_count = 0;?>
<table border="0" cellspacing="0" style="width: 98%; margin: 0px auto;">
<tr>
	<td align="center">
		<table cellSpacing=0 cellPadding=0 width="100%" border="0" style="border-collapse:collapse; font-size:12px;" bordercolor="#97ADC5">
			<tr height="25">
				<td align="center">
					<table width="100%"><tr align="center" valign="top">
                        <tr>
                            <td align="left">
        					<div>
        						<a href="#" onclick="if(document.getElementById('search_box').style.display=='none'){document.getElementById('search_box').style.display='';}else{document.getElementById('search_box').style.display='none';}"><img src="<?php echo HOTEL_LOGO;?>" width="75"></a><br />
        					</div>
        					</td>
                            <td align="right">
                            <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                            <br />
                            <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2">
        					<div>
        					<font style="font-size:20px" class="report-title"><br/><br/><?php echo Portal::language('guest_by_countries');?></font>
                            <br />
        					<span class="report-sub-title"><?php echo Portal::language('month');?> <?php echo $this->map['month'];?> <?php echo Portal::language('year');?> <?php echo $this->map['year'];?></span>
        					</div>
        					</td>
                        </tr>
                    
                    </table>
				</td>
			</tr>
			<tr>
			  <td>
<!---------PARAMERTERS----------->
			<!---------SEARCH----------->
			<div class="search">
            <form method="post" name="SearchMonthlyTravellerReportForm">
            <span id="search_box">

			<link href="skins/default/datetime.css" rel="stylesheet" type="text/css" />
            <br />
            <div style="margin: 0 auto; width:550px"><span><?php echo Portal::language('Report_options');?>: </span><select  name="option" id="option"><?php
					if(isset($this->map['option_list']))
					{
						foreach($this->map['option_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('option',isset($this->map['option'])?$this->map['option']:''))
                    echo "<script>$('option').value = \"".addslashes(URL::get('option',isset($this->map['option'])?$this->map['option']:''))."\";</script>";
                    ?>
	</select><span><?php echo Portal::language('hotel');?>: </span><select  name="portal_id" id="portal_id"><?php
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
	</select><span><input type="button" value="  <?php echo Portal::language('view_report');?>  " onclick="SearchMonthlyTravellerReportForm.submit();" /></span></div>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F0F6DE">
				<tr>
					<td width="50%">&nbsp;</td>
					<?php if(isset($this->map['years']) and is_array($this->map['years'])){ foreach($this->map['years'] as $key1=>&$item1){if($key1!='current'){$this->map['years']['current'] = &$item1;?>
					<td nowrap><a class="datetime_button<?php echo $this->map['years']['current']['selected'];?>" href="<?php echo URL::build_current(array('month','day'));?>&year=<?php echo $this->map['years']['current']['year'];?>"><?php echo $this->map['years']['current']['year'];?></a></td>
					<?php }}unset($this->map['years']['current']);} ?>
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F0F6DE">
				<tr>
					<td width="50%">&nbsp;</td>
					<?php if(isset($this->map['months']) and is_array($this->map['months'])){ foreach($this->map['months'] as $key2=>&$item2){if($key2!='current'){$this->map['months']['current'] = &$item2;?>
					<td><a class="datetime_button<?php echo $this->map['months']['current']['selected'];?>" href="<?php echo URL::build_current(array('year','day'));?>&month=<?php echo $this->map['months']['current']['month'];?>" onclick="if(event.shiftKey){select_date_time_range('<?php echo URL::build_current(array('year','day'=>$this->map['day']));?>&month=',<?php echo $this->map['month'];?>,<?php echo $this->map['months']['current']['month'];?>); event.returnValue=false;}"><?php echo $this->map['months']['current']['month'];?></a></td>
					<?php }}unset($this->map['months']['current']);} ?>
					<td width="50%">&nbsp;</td>
				</tr>
			</table>
			<!--
            <?php echo Portal::language('nationality');?> : 
			<select  name="country_id">
			<?php
				foreach($this->map['country_id_list'] as $id=>$name)
				{
					echo '<option value="'.$id.'" '.((URL::get('country_id')==$id)?'selected':'').'>'.$name.'</option>';
				}
			?></select>
			<input type="submit" value="<?php echo Portal::language('search');?>"/>
            -->
            </span>
			<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
            </div>
          </td>
        </tr>
       </table>
      </td>
    </tr>
  </table>    
			
