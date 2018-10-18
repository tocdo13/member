<style>
    .simple-layout-middle{
        width:100%;
    }
    .simple-layout-content{
        padding: 0px; border: none;
    }
</style>

<div style="width: 95%; margin: 0px auto 50px;">
    <table style="width: 100%;">
        <tr>
            <th><h3 style="text-transform: uppercase;"><?php echo Portal::language('vat_invoice_list');?></h3></th>
            <th style="text-align: right;">
                <input type="button" value="<?php echo Portal::language('print_vat_other');?>" onclick="window.location.href='?page=vat_bill&cmd=add';" style="padding: 5px;" />
            </th>
        </tr>
    </table>
    <form name="VatBillListForm" method="POST">
    <table style="width: 100%;">
        <tr>
            <td><?php echo Portal::language('vat_code');?>: <input  name="vat_code" id="vat_code" style="padding: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('vat_code'));?>"></td>
            <td><?php echo Portal::language('start_date');?>: <input  name="start_date" id="start_date" style="padding: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>"></td>
            <td><?php echo Portal::language('end_date');?>: <input  name="end_date" id="end_date" style="padding: 5px;" / type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>"></td>
            <td><?php echo Portal::language('status');?>: <select  name="vat_type" id="vat_type" style="padding: 5px;"><?php
					if(isset($this->map['vat_type_list']))
					{
						foreach($this->map['vat_type_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('vat_type',isset($this->map['vat_type'])?$this->map['vat_type']:''))
                    echo "<script>$('vat_type').value = \"".addslashes(URL::get('vat_type',isset($this->map['vat_type'])?$this->map['vat_type']:''))."\";</script>";
                    ?>
	</select></td>
            <td><?php echo Portal::language('type');?>: <select  name="type" id="type" style="padding: 5px;"><?php
					if(isset($this->map['type_list']))
					{
						foreach($this->map['type_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('type',isset($this->map['type'])?$this->map['type']:''))
                    echo "<script>$('type').value = \"".addslashes(URL::get('type',isset($this->map['type'])?$this->map['type']:''))."\";</script>";
                    ?>
	</select></td>
            <td><input name="search" type="submit" id="search" value="<?php echo Portal::language('search');?>" style="padding: 5px;" /></td>
        </tr>
    </table>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
    <table style="width: 100%; margin: 10px auto;" border="1" bordercolor="#CCCCCC">
        <tr style="background: #EEEEEE; height: 35px; text-align: center;">
            <th><?php echo Portal::language('stt');?></th>
            <th><?php echo Portal::language('code');?></th>
            <th><?php echo Portal::language('customer_name');?></th>
            <th><?php echo Portal::language('type');?></th>
            <th><?php echo Portal::language('invoice_ids');?></th>
            <th><?php echo Portal::language('total');?></th>
            <th><?php echo Portal::language('start_date');?></th>
            <th><?php echo Portal::language('end_date');?></th>
            <th><?php echo Portal::language('creater');?></th>
            <th><?php echo Portal::language('create_time');?></th>
            <th><?php echo Portal::language('last_editer');?></th>
            <th><?php echo Portal::language('last_edit_time');?></th>
            <th><?php echo Portal::language('status');?></th>
            <th><?php echo Portal::language('count_print');?></th>
            <th><?php echo Portal::language('note');?></th>
            <th><?php echo Portal::language('edit');?></th>
            <th><?php echo Portal::language('cancel');?></th>
        </tr>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <tr style="height: 30px; text-align: center;">
            <td><?php echo $this->map['items']['current']['stt'];?></td>
            <td><?php echo $this->map['items']['current']['vat_code'];?></td>
            <td><?php echo $this->map['items']['current']['customer_name'];?></td>
            <td><?php echo $this->map['items']['current']['type'];?></td>
            <td><?php echo $this->map['items']['current']['invoice_ids'];?></td>
            <td style="text-align: right;"><?php echo $this->map['items']['current']['total_amount'];?></td>
            <td><?php echo $this->map['items']['current']['start_date'];?></td>
            <td><?php echo $this->map['items']['current']['end_date'];?></td>
            <td><?php echo $this->map['items']['current']['creater'];?></td>
            <td><?php echo $this->map['items']['current']['create_time'];?></td>
            <td><?php echo $this->map['items']['current']['last_editer'];?></td>
            <td><?php echo $this->map['items']['current']['last_edit_time'];?></td>
            <td><?php 
				if(($this->map['items']['current']['status']=='CANCEL'))
				{?><?php echo Portal::language('cancel');?> <?php }else{ ?><?php 
				if(($this->map['items']['current']['vat_type']=='SAVE_CODE'))
				{?><?php echo Portal::language('save_code');?> <?php }else{ ?><?php 
				if(($this->map['items']['current']['vat_type']=='SAVE_NO_PRINT'))
				{?><?php echo Portal::language('save_and_no_print_vat');?> <?php }else{ ?><?php echo Portal::language('printed');?>
				<?php
				}
				?>
				<?php
				}
				?>
				<?php
				}
				?></td>
            <td><?php echo $this->map['items']['current']['count_print'];?></td>
            <td style="text-align: left;"><?php 
				if(($this->map['items']['current']['status']=='CANCEL'))
				{?><?php echo $this->map['items']['current']['note_cancel'];?> <?php }else{ ?><?php echo $this->map['items']['current']['note'];?>
				<?php
				}
				?></td>
            <td><a href="?page=vat_bill&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="<?php echo Portal::language('edit');?>" /></a></td>
            <td>
                <?php 
				if(($this->map['items']['current']['status']!='CANCEL' and $this->map['items']['current']['vat_type']!='SAVE_NO_PRINT'))
				{?><input type="button" onclick="CancelVAT(<?php echo $this->map['items']['current']['id'];?>,'<?php echo $this->map['items']['current']['vat_code'];?>');" value="<?php echo Portal::language('cancel');?>" />
				<?php
				}
				?>
            </td>
        </tr>
        <?php }}unset($this->map['items']['current']);} ?>
    </table>
    <?php echo $this->map['paging'];?>
</div>
<div id="LightBoxNoteCancel" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(255,255,255,0.5); display: none;">
    
</div>
<script>
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    
    function CancelVAT($vat_id,$vat_code){
        $content = '<div style="width: 640px; height: 320px; margin: calc( (50% - 320px) / 2 ) auto; background: #2D2D30; box-shadow: 0px 0px 3px #1297FB;">';
            $content += '<div style="width: 630px; height: 50px; padding: 5px; border-bottom: 1px solid #4B4B4D;"><h3 style="line-height: 50px; margin: 0px; padding: 0px; color: #1297FB; margin-left: 10px; text-transform: uppercase;"><?php echo Portal::language('vat_cancel_confrim');?> '+$vat_code+'</h3></div>';
            $content += '<div style="width: 630px; height: 190px; padding: 5px;">';
                $content += '<p style="color: #FFFFFF;"><?php echo Portal::language('note');?></p>';
                $content += '<textarea id="note_cancel" style="width: calc(100% - 5px); background: none; border: 1px solid #1297FB; margin: 0px auto; height: 150px; color: #FFFFFF;"></textarea>';
            $content += '</div>';
            $content += '<div style="width: 630px; height: 50px; padding: 5px;">';
                $content += '<input type="button" value="<?php echo Portal::language('cancel');?>" onclick="ResetCancel();" style="float: right; margin-right: 10px; padding: 10px;" />';
                $content += '<input type="button" value="<?php echo Portal::language('action');?>" onclick="ActionCancelVat('+$vat_id+');" style="float: right; margin-right: 10px; padding: 10px;" />';
            $content += '</div>';
        $content += '</div>';
        
        jQuery("#LightBoxNoteCancel").html($content);
        jQuery("#LightBoxNoteCancel").css('display','');
    }
    function ResetCancel() {
        jQuery("#LightBoxNoteCancel").html('');
        jQuery("#LightBoxNoteCancel").css('display','none');
    }
    function ActionCancelVat($vat_id) {
        <?php echo 'var block_id = '.Module::block_id().';';?>
        $note_cancel = jQuery("#note_cancel").val();
        if(to_numeric($note_cancel.length)<3) {
            alert('<?php echo Portal::language('note_must_contain_3_or_more_characters');?>');
            return false;
        } else {
            jQuery.ajax({
    					url:"form.php?block_id="+block_id,
    					type:"POST",
    					data:{actioncancel:'CANCELVAT',vat_bill_id:$vat_id,note_cancel:$note_cancel},
    					success:function(html)
                        {
                            console.log(html);
                            if(html=='error') {
                                alert('<?php echo Portal::language('action_unsuccess');?> !');
                            } else {
                                alert('<?php echo Portal::language('success');?> !');
                                location.reload();
                            }
    					}
		          });
        }
    }
</script>