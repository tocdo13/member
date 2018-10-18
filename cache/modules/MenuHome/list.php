<div class="home-menu-wrapper">
	<div>
		<table border="1" cellspacing="0" cellpadding="5" width="100%" bordercolor="#CCCCCC">
			<tr class="table-header">
				<td align="center" class="title"><?php echo Portal::language('select_hotel');?></td>
			</tr>
			<tr>
			  	<td><div class="hotel-list">
                    
					<?php if(isset($this->map['hotels']) and is_array($this->map['hotels'])){ foreach($this->map['hotels'] as $key1=>&$item1){if($key1!='current'){$this->map['hotels']['current'] = &$item1;?>
						<div><a <?php echo ($this->map['hotels']['current']['id']==PORTAL_ID)?'class="selected"':'';?> href="<?php echo Url::build_current(array('selected_portal_id'=>$this->map['hotels']['current']['id']));?>"><?php echo $this->map['hotels']['current']['name'];?></a></div>
                    <?php }}unset($this->map['hotels']['current']);} ?>					
					</div></td>
		  </tr>
		</table>
	</div>
</div>