<style>
    @media print {
      #searchform { display: none; }
    }
</style>
<div id="export">
    <table style="width: 100%;">
        <tr>
            <th>
                <?php echo Portal::language('hotel');?>: <?php echo HOTEL_NAME;?><br />
                <?php echo Portal::language('address');?>: <?php echo HOTEL_ADDRESS;?>
            </th>
            <th style="text-align: right;">
                <?php echo Portal::language('template_code');?> <br />
                <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?> <br />
                <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center;">
                <h3 style="text-transform: uppercase;"><?php echo Portal::language('foc_restaurant');?></h3>
                <p><?php echo Portal::language('start_date');?>&nbsp;<?php echo $this->map['start_date'];?> - <?php echo Portal::language('end_date');?>&nbsp;<?php echo $this->map['end_date'];?></p>
            </th>
        </tr>
    </table>
    <form name="SummaryOfRoomsTurnsForm" method="POST" id="searchform">
        <table style="margin: 0px auto;">
            <tr>
                <td><fieldset>
                    <legend><?php echo Portal::language('bar_list');?></legend>
                    <?php if(isset($this->map['bar_list']) and is_array($this->map['bar_list'])){ foreach($this->map['bar_list'] as $key1=>&$item1){if($key1!='current'){$this->map['bar_list']['current'] = &$item1;?>
                    <input  name="bar_list[<?php echo $this->map['bar_list']['current']['id'];?>]['id']" type="checkbox" value="<?php echo $this->map['bar_list']['current']['id'];?>" <?php 
				if((isset($this->map['bar_list']['current']['checked'])))
				{?>checked="checked"
				<?php
				}
				?>  />
                    <?php echo $this->map['bar_list']['current']['name'];?>
                    <?php }}unset($this->map['bar_list']['current']);} ?>
                </fieldset></td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <?php echo Portal::language('portal');?>
                    <select  name="portal_id" id="portal_id" class="w3-border" style="padding: 5px; width: inherit;"><?php
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
	</select>
                    <?php echo Portal::language('start_date');?>
                    <input  name="start_date" id="start_date" class="w3-border" readonly="" style="padding: 5px; width: 100px; text-align: center;" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>">
                    <?php echo Portal::language('end_date');?>
                    <input  name="end_date" id="end_date" class="w3-border" readonly="" style="padding: 5px; width: 100px; text-align: center;" / type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>">
                    <input type="submit" name="do_search" value="<?php echo Portal::language('search');?>" style="padding: 5px;" />
                    <input type="button" id="export_repost" value="<?php echo Portal::language('export_excel');?>" style="padding: 5px;" />
                </td>
            </tr>
        </table>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
    
    <table id="data-report" style="background: #FFF; margin: 0px auto;" cellpadding="10" cellspacing="0" border="1" bordercolor="#CCCCCC">
        <tr>
            <th><?php echo Portal::language('stt');?></th>
            <th><?php echo Portal::language('code');?></th>
            <th><?php echo Portal::language('table_name');?></th>
            <th><?php echo Portal::language('date');?></th>
            <th><?php echo Portal::language('customer');?></th>
            <th><?php echo Portal::language('guest');?></th>
            <th><?php echo Portal::language('product_code');?></th>
            <th><?php echo Portal::language('product_name');?></th>
            <th><?php echo Portal::language('quantity');?></th>
            <th><?php echo Portal::language('total_bill');?></th>
            <th><?php echo Portal::language('total_foc');?></th>
            <th><?php echo Portal::language('note_foc');?></th>
            <th><?php echo Portal::language('cashier');?></th>
        </tr>
        <?php $stt = 1; ?>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
        <tr>
            <td rowspan="<?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>1 <?php }else{ ?><?php echo $this->map['items']['current']['count_child'];?>
				<?php
				}
				?>"><?php echo $stt++; ?></td>
            <td rowspan="<?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>1 <?php }else{ ?><?php echo $this->map['items']['current']['count_child'];?>
				<?php
				}
				?>"><a target="_blank" href="?page=touch_bar_restaurant&cmd=detail<?php echo '&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1'; ?>&id=<?php echo $this->map['items']['current']['id'];?>&bar_id=<?php echo $this->map['items']['current']['bar_id'];?>"><?php echo $this->map['items']['current']['code'];?></a></td>
            <td rowspan="<?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>1 <?php }else{ ?><?php echo $this->map['items']['current']['count_child'];?>
				<?php
				}
				?>"><?php echo $this->map['items']['current']['table_name'];?></td>
            <td rowspan="<?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>1 <?php }else{ ?><?php echo $this->map['items']['current']['count_child'];?>
				<?php
				}
				?>"><?php echo $this->map['items']['current']['time'];?></td>
            <td rowspan="<?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>1 <?php }else{ ?><?php echo $this->map['items']['current']['count_child'];?>
				<?php
				}
				?>"><?php echo $this->map['items']['current']['customer_name'];?></td>
            <td rowspan="<?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>1 <?php }else{ ?><?php echo $this->map['items']['current']['count_child'];?>
				<?php
				}
				?>"><?php echo $this->map['items']['current']['receiver_name'];?></td>
            <?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>
                <td></td>
                <td></td>
                <td></td>
             <?php }else{ ?>
                <?php $child = ''; ?>
                <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['child']['current'] = &$item3;?>
                <?php $child = $this->map['items']['current']['child']['current']['id']; ?>
                <td><?php echo $this->map['items']['current']['child']['current']['product_id'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['name'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['quantity'];?></td>
                <?php break; ?>
                <?php }}unset($this->map['items']['current']['child']['current']);} ?>
            
				<?php
				}
				?>
            <td rowspan="<?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>1 <?php }else{ ?><?php echo $this->map['items']['current']['count_child'];?>
				<?php
				}
				?>" style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['total']); ?></td>
            <td rowspan="<?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>1 <?php }else{ ?><?php echo $this->map['items']['current']['count_child'];?>
				<?php
				}
				?>" style="text-align: right;"><?php echo System::display_number($this->map['items']['current']['total_foc']); ?></td>
            <td rowspan="<?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>1 <?php }else{ ?><?php echo $this->map['items']['current']['count_child'];?>
				<?php
				}
				?>"><?php echo $this->map['items']['current']['note'];?></td>
            <td rowspan="<?php 
				if(($this->map['items']['current']['count_child']==0))
				{?>1 <?php }else{ ?><?php echo $this->map['items']['current']['count_child'];?>
				<?php
				}
				?>"><?php echo $this->map['items']['current']['reception_name'];?></td>
        </tr>
        <?php 
				if(($this->map['items']['current']['count_child']!=0))
				{?>
            <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current']['child']['current'] = &$item4;?>
            <?php if($child != $this->map['items']['current']['child']['current']['id']){ ?>
            <tr>
                <td><?php echo $this->map['items']['current']['child']['current']['product_id'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['name'];?></td>
                <td><?php echo $this->map['items']['current']['child']['current']['quantity'];?></td>
            </tr>
            <?php } ?>
            <?php }}unset($this->map['items']['current']['child']['current']);} ?>
        
				<?php
				}
				?>
        <?php }}unset($this->map['items']['current']);} ?>
        <tr>
            <th colspan="8"><?php echo Portal::language('total');?></th>
            <th><?php echo $this->map['total_quantity'];?></th>
            <th style="text-align: right;"><?php echo System::display_number($this->map['total_bill']); ?></th>
            <th style="text-align: right;"><?php echo System::display_number($this->map['total_foc']); ?></th>
            <th colspan="2"></th>
        </tr>
    </table>
</div>
<script>
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    jQuery('#export_repost').click(function(){
        jQuery("#searchform").remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
        });
        location.reload();
    });
</script>