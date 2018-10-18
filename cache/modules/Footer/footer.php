<?php 
if(User::is_admin() and (Url::get('DEBUG')==1 or Url::get('debug')==1))
{	
	echo $this->map['information_query_in_page'].'<br>';
	echo '<b>Page '.Url::get('page').' have : <span style="color:#ff0000">'.$this->map['total_query'].' </span>query</b>';
}
?>
<div class="div-footer" <?php if(Url::get('print')){echo  ' style="display:none"';}?>>
    <div id="<?php echo $this->map['button_name'];?>" style="display:none;">
	<?php 
				if((User::can_admin()))
				{?>
        <hr>
        <center>Time:<?php echo $this->map['number_format'];?> | Query: <?php echo $this->map['number_query'];?>
        | <a href="<?php echo $this->map['link_structure_page'];?>">B&#7889; c&#7909;c trang</a> | <a href="<?php echo $this->map['link_edit_page'];?>">S&#7917;a trang</a>
			| <a href="<?php echo $this->map['delete_cache'];?>">Xo&#225; cache</a>
		<?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key1=>&$item1){if($key1!='current'){$this->map['languages']['current'] = &$item1;?>
		<a href="<?php echo Url::build('change_language',array('language_id'=>$this->map['languages']['current']['id'],'href'=>'?'.$_SERVER['QUERY_STRING']));?>">
			<?php 
				if(($this->map['languages']['current']['id']==Portal::language()))
				{?>
				<b><?php echo $this->map['languages']['current']['name'];?></b>
			 <?php }else{ ?>
				<?php echo $this->map['languages']['current']['name'];?>
			
				<?php
				}
				?>
		</a>
		<?php }}unset($this->map['languages']['current']);} ?>
		</center>
	
				<?php
				}
				?>
	 </div>
</div>