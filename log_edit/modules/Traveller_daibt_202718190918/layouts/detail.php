<?php //System::debug([[=items=]]); ?>
<style>
@media print 
{
    .edit_delete{
        display: none;
    }
}
</style>
<?php //System::debug($this->map);exit(); ?>
<script>
function comment_add_row(index,id)
{
	var st='<span class="multi-input-header">\<span>\
				<img src="packages/core/skins/default/images/buttons/delete.gif" onClick="$(\'input_group_'+comment_item_count+'\').parentElement.removeChild($(\'input_group_'+index+'\'));event.returnValue=false;" style="cursor:hand;"/>\
			</span>\
			</span>\
				<input  name="mi_traveller_comment['+index+'][id]" type="hidden" id="id_'+index+'" value="'+(id?id:'')+'">\
			<span class="multi-edit-input">\
					<input  name="mi_traveller_comment['+index+'][time]" style="border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;border-bottom:1px solid white;width:60px;" class="multi_edit_text_input" type="text" id="time_'+index+'" value="'+(id?comments[id].time:'<?php echo date('d/m/Y');?>')+'" tabindex="2">\
			</span>\
			<span>\
				<a href="#a" name="time_date_in_'+index+'" id="time_date_in_'+index+'" onClick="cal.select($(\'time_'+index+'\'),\'time_date_in_'+index+'\',\'dd/MM/yyyy\');"><img width="20" border="0" src="skins/default/images/calendar.gif"></a>\
			</span><span class="multi-edit-input">\
					<input  name="mi_traveller_comment['+index+'][hour]" style="border-top:1px solid white;border-right:1px solid white;border-left:1px solid white;border-bottom:1px solid white;width:50px;" class="multi_edit_text_input" type="text" id="hour_'+index+'"  value="'+(id?comments[id].hour:'<?php echo date('H:i:s');?>')+'" tabindex="2">\
			</span><span class="multi-edit-input">\
				<span style="width:100px;" class="multi_edit_text_input"  id="user_id_'+index+'" >'+(id?comments[id].user_id:'[[|user_id|]]')+'</span>\
				'+(id?'<input type="hidden" name="mi_traveller_comment['+index+'][user_id]" value="<?php echo Session::get('user_id');?>">':'')+'\
			</span><span class="multi-edit-input" style="white-space:nowrap" >\
				<textarea rows="5" name="mi_traveller_comment['+index+'][content]" style="width:100%;" class="multi_edit_text_input" type="text" id="content_'+index+'"  tabindex="2">'+(id?comments[id].content:'')+'</textarea>\
			</span>\
			<br>';
	$('input_group_'+index).innerHTML = st;
	editor_generate("content_"+index);
}
</script>
<div class="traveller-bound" style="padding:5px;">
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="border:1px solid #CCCCCC">
  <tr>
    <td colspan="8" bgcolor="#C8DEF9"><font class=""><b>[[.traveller_detail.]]</b></font></td>
    <td align="right" bgcolor="#C8DEF9"></td>
  </tr>
  <tr>
    <td colspan="9">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" rowspan="7" align="center" valign="top"><i class="fa fa-user w3-text-orange" style="font-size: 150px;"></i></td>
    <td width="89" align="left" nowrap style="border-bottom:1px dotted #AAD5FF;">[[.full_name.]]</td>
    <td width="1">:</td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[|first_name|]]&nbsp;[[|last_name|]]</td>
    <td colspan="3" rowspan="9" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #EEEEEE;">
      <tr>
        <th width="93%" align="left" bgcolor="#FFE680" scope="col">&nbsp;[[.note.]]</th>
        <th width="7%" bgcolor="#FFE680" scope="col"><img src="skins/default/images/news_23.gif" width="8" height="7" /></th>
      </tr>
      <tr>
        <th colspan="2" align="left" valign="top" scope="col" style="font-weight:normal;font-style:italic;padding:0 0 0 5;">[[|note|]]</th>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td nowrap style="border-bottom:1px dotted #AAD5FF;">[[.gender.]]</td>
    <td>:</td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[|gender|]]</td>
  </tr>
  <tr>
    <td style="border-bottom:1px dotted #AAD5FF;">[[.birth_date.]]</td>
    <td>:</td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[|birth_date|]]</td>
  </tr>
  <tr>
    <td style="border-bottom:1px dotted #AAD5FF;">[[.passport.]]</td>
    <td>:</td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[|passport|]]</td>
  </tr>
  <tr>
    <td style="border-bottom:1px dotted #AAD5FF;">[[.visa.]]</td>
    <td>:</td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[|visa|]]</td>
  </tr>
  <tr>
    <td style="border-bottom:1px dotted #AAD5FF;">[[.nationality_id.]]</td>
    <td>:</td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[|nationality_id|]]</td>
  </tr>
  <tr>
    <td style="border-bottom:1px dotted #AAD5FF;">[[.phone.]]</td>
    <td>:</td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[|phone|]]</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><u><strong>Code:&nbsp;[[|id|]]</strong></u></td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[.fax.]]</td>
    <td>:</td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[|fax|]]</td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[.address.]]</td>
    <td>&nbsp;</td>
    <td style="border-bottom:1px dotted #AAD5FF;">[[|address|]]</td>
  </tr>
  <tr>
    <td width="69">&nbsp;</td>
    <td width="4">&nbsp;</td>
    <td width="66">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td bgcolor="#FFFFCC">&nbsp;</td>
    <td bgcolor="#FFFFCC">&nbsp;</td>
    <td align="right" bgcolor="#FFFFCC" class="edit_delete"><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" align="center">[[.edit.]]</a> | <a href="<?php echo Url::build_current(array('cmd'=>'delete','id'));?>"><img src="packages/core/skins/default/images/buttons/delete.gif" align="center">[[.delete.]]</a></td>
  </tr>
</table>
<form name="EditTravellerCommentForm" method="post" ></td>
<input  name="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
<fieldset>
<legend class="title">[[.archives.]]</legend>
	<?php if(Form::$current->is_error()){?><div><?php echo Form::$current->error_messages();?></div><?php }?>
	<table cellpadding="2" cellspacing="0" width="85%" border="1">
        <tr class="table-header">
            <th style="text-align: center; width: 15px;">[[.no.]]</th>
            <th style="text-align: center; width: 15px;">[[.re_code.]]</th>
            <th style="text-align: center; width: 50px;">[[.arrival_date.]]</th>
            <th style="text-align: center; width: 50px;">[[.departure_date.]]</th>
            <th style="text-align: center; width: 25px;">[[.room.]]</th>
            <th style="text-align: center; width: 25px;">[[.customer_name.]]</th>
            <th style="text-align: center; width: 25px;">[[.folio.]]</th>
            <th style="text-align: center; width: 25px;">[[.total.]]</th>
            <th style="text-align: center; width: 60px;">[[.note.]]</th>
        </tr>
        <?php 
        $reservation_id = '';
        $reservation_room_id = '';
        $j = 0;
        ?>
        <!--LIST:items-->
        <tr>
            <?php 
                if([[=items.reservation_id=]] != $reservation_id){
                    $reservation_id = [[=items.reservation_id=]];
                    $rowspan = $this->map['count'][$reservation_id]['count'];
            ?>
            <td style="text-align: center;" rowspan="<?php echo $rowspan; ?>"><?php echo ++$j;?></td>
            <td style="text-align: center;" rowspan="<?php echo $rowspan; ?>"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.reservation_id=]]));?>">[[|items.reservation_id|]]</a></td>
            <?php 
                }
            ?>
            <?php 
                if([[=items.reservation_id=]].'_'.[[=items.reservation_room_id=]] != $reservation_id.'_'.$reservation_room_id){
                    $reservation_room_id = [[=items.reservation_room_id=]];
                    $rowspan_child = $this->map['count'][$reservation_id][$reservation_room_id];
            ?>
            <td  style="text-align: center;"rowspan="<?php echo $rowspan_child; ?>"><?php echo date('d-m-Y',[[=items.arrival_time=]]);?></td>
            <td  style="text-align: center;"rowspan="<?php echo $rowspan_child; ?>" ><?php echo date('d-m-Y',[[=items.departure_time=]]);?></td>
            <td style="text-align: center;"rowspan="<?php echo $rowspan_child; ?>" >[[|items.room_name|]]</td>
            <td style="text-align: center;"rowspan="<?php echo $rowspan_child; ?>" >[[|items.customer_name|]]</td>
            <?php 
                }
            ?>
            
            <td style="text-align: center;"><?php if([[=items.check_r_id=]] !=''){ ?><a target="_blank" href="<?php echo Url::build('view_traveller_folio',array('cmd'=>'invoice','traveller_id'=>[[=items.re_traveller_id=]],'folio_id'=>[[=items.folio=]]))?>">[[|items.folio|]]</a><?php }else{?><a target="_blank" href="<?php echo Url::build('view_traveller_folio',array('cmd'=>'group_invoice','id'=>[[=items.reservation_id=]],'folio_id'=>[[=items.folio=]]))?>">[[|items.folio|]]</a> <?php }?></td>
            <td style="text-align: right;"><?php echo System::display_number([[=items.total_folio=]]) ?></td>
            <td>[[|items.note|]]</td>
                
        </tr>
        <!--/LIST:items-->
	</table>
</fieldset>
</form>
</div>
<script type="text/javascript">
	var comments = <?php if(isset($_REQUEST['mi_traveller_comment'])){echo String::array2js($_REQUEST['mi_traveller_comment']);}else{echo '[]';}?>;
	comment_item_count = 0;
	for(var i in comments)
	{
		comment_item_count++;
		var st = '<span style="white-space:nowrap;width:100%;'+((comment_item_count%2==1)?'background-color:#FCF9F8;':'')+'" id="input_group_'+comment_item_count+'">\
			<span>\
				<table cellspacing=0 cellpadding=5 border=1 bordercolor="#CCCCCC">\
				<tr valign="top"><td width="55">\
				<span class="multi-input">'+comments[i].time+'</span></td><td width="55">\
				<span class="multi-input">'+comments[i].hour+'</span></td><td width="80">';
				st+='<span class="multi-input">'+comments[i].user_id+'</span></td><td width="400">\
				<span class="multi-input">'+comments[i].content+'</span></td></tr></table>\
				</span><br clear="all">\
			</span>\
		</span>';

		$('mi_traveller_comment_all_elems').innerHTML += st;
	}
</script>