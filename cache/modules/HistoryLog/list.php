<form name="LogListForm"  method="post">
    <table width="100%" style="text-align: center;">
        <tr>
            <th><h1 style="text-transform: uppercase;"><?php echo Portal::language('log');?> Recode <?php echo Url::get('recode'); ?></h1></th>
        </tr>
    </table>
    <table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
        <tr class="table-header">
        	<th width="1%"></th>
        	<th nowrap align="left">
        		<?php echo Portal::language('time');?>
        	</th>
        	<th nowrap align="left">
        		<?php echo Portal::language('type');?>
        	</th>
        	<th nowrap align="left">
        		<?php echo Portal::language('user_id');?>
        	</th>
        	<th align="left">
        		<?php echo Portal::language('title');?>
        	</th>
        	<th align="left" nowrap="nowrap">
        		<?php echo Portal::language('note');?>
        	</th>
        </tr>
        <?php $last_date = false;?>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <?php
        if($last_date!=$this->map['items']['current']['in_date'])
        {
        	$last_date=$this->map['items']['current']['in_date'];
        	echo '<tr bgcolor="#FFFF80"><td colspan="9">'.$this->map['items']['current']['in_date'].'</td></tr>';
        }
        ?><tr bgcolor="white" valign="top">
        	<td></td>
        	<td nowrap align="left">
        		<?php echo date('d/m/Y H:i:s',$this->map['items']['current']['time']);?>
        	</td>
        	<td nowrap align="left">
        		<?php echo $this->map['items']['current']['type'];?>
        	</td>
        	<td nowrap align="left">
        	  <strong><?php echo $this->map['items']['current']['user_id'];?>						    </strong></td>
        	
        	<td align="left" width="100%">
        		<?php echo $this->map['items']['current']['title'];?>
        	</td>
        	<td nowrap align="left">
        		<?php echo $this->map['items']['current']['note'];?>
        	</td>
        </tr>
        <tr bgcolor="#EEEEEE">
            <td colspan="6"><?php echo $this->map['items']['current']['description'];?></td>
        </tr>
        <?php }}unset($this->map['items']['current']);} ?>
    </table>	
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
