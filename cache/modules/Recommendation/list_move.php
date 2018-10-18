<style>
.center{text-align:center;}
</style>
<div class="room-type-supplier-bound">
<form name="ListMoveForm" method="post">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td width="100%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('require_transfer_invoice');?></td>
            <td></td>
        </tr>
    </table>
            
    <div class="content">
        <table width="80%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
            <tr class="w3-light-gray" style="text-transform: uppercase; height: 24px;">
              <th width="30" class="center"><?php echo Portal::language('stt');?></th>
              <th width="100" align="center"><?php echo Portal::language('recomment_date');?></th>
                <th width="150" align="center"><?php echo Portal::language('person_recomment');?></th>
                <th width="150" align="center"><?php echo Portal::language('department');?></th>
                <th width="400" align="center"><?php echo Portal::language('description');?></th>
                <th width="40" align="center"><?php echo Portal::language('view');?></th>
          </tr>
          <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
          <?php
          if($this->map['items']['current']['index']%2==0)
          {
            $style='style="background-color: #E8F3FF"';
          } 
          else
            $style ="";
          ?>
            <tr <?php echo $style;?> style="height: 24px;">
              <td align="center"><?php echo $this->map['items']['current']['index'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['recommend_date'];?></td>
                <td><?php echo $this->map['items']['current']['recommend_person'];?></td>
                <td><?php echo $this->map['items']['current']['department'];?></td>
                <td><?php echo $this->map['items']['current']['description'];?></td>
                <td align="center"><?php if(User::can_view(false,ANY_CATEGORY)){?><a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'view_move','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 18px;"></i></a><?php }?></td>
            </tr>
            <?php }}unset($this->map['items']['current']);} ?>      
        </table>
<br />
        
    </div>
    <input  name="cmd" value="" type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			 
</div>


<script type="text/javascript">
</script>