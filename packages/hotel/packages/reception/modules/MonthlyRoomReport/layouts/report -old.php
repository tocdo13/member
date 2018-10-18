<div><br />
<input  type="hidden" id="stop_value" value="0" />
<div class="div_freezepanes_wrapper">
<div class="div_verticalscroll" onmouseover="this.style.cursor='pointer'">
	<div style="height:50%;" onmousedown="upp();" onmouseup="upp(1);"><img class="buttonUp" src="packages/core/skins/default/images/buttons/up.jpg"></div>
	<div class="v_bar"><img src="packages/core/skins/default/images/panel/action.gif" /></div>
	<div style="height:50%;" onmousedown="down();" onmouseup="down(1);"><img class="buttonDn" src="packages/core/skins/default/images/buttons/down.jpg"></div>
</div>
<div class="div_horizontalscroll" onmouseover="this.style.cursor='pointer'">
	<div style="float:left;width:50%;height:100%;" onmousedown="right();" onmouseup="right(1);"><img class="buttonRight" src="packages/core/skins/default/images/buttons/left.jpg"></div>
	<div style="float:right;width:50%;height:100%;" onmousedown="left();" onmouseup="left(1);"><img class="buttonLeft" src="packages/core/skins/default/images/buttons/right.jpg"></div>
</div>
<table id="t1" cellpadding=1 border="0" cellspacing="0" width="100%">
<tr>
<th class="th" width="50" align="left">[[.room.]]</th>
<?php for($i=1;$i<=[[=days_in_month=]];$i++){?>
<th class="th<?php echo ($i==[[=current_day=]])?' current':'';?>"><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></th>
<?php }?>  
</tr>
<!--LIST:items-->
<tr>
<td class="td1" bgcolor="[[|items.room_level_color|]]">
	<input name="date_from_[[|items.room_id|]]" type="hidden" id="date_from_[[|items.room_id|]]" size="3"> 
	<input name="date_to_[[|items.room_id|]]" type="hidden" id="date_to_[[|items.room_id|]]" size="3"> 	
	<div>[[|items.room_name|]]<span style="color:#999999;font-size:10px;">([[|items.room_level_name|]])</span></div></td>
<?php for($i=1;$i<=[[=days_in_month=]];$i++){?>
<td id="[[|items.room_id|]]_<?php echo $i;?>" onclick="selectColumn(this.id,'[[|items.room_id|]]','<?php echo $i;?>');" class="room-element" style="  <?php echo ($i<[[=current_day=]])?'background:'.[[=disabled_cell_color=]]:'';?>"><div><?php echo  isset($this->map['items']['current'][$i])?''.$this->map['items']['current'][$i]:'';?></div></td>
<?php }?>  
</tr>
<!--/LIST:items-->
</table>
</div>
<div class="booking-toolbar">
<!--IF:reservation(User::can_edit(false,ANY_CATEGORY))-->
<strong>Price: </strong> | <strong>Default:</strong>&nbsp;Time in: <input type="text" id="time_in" value="<?php echo CHECK_IN_TIME;?>" style="width:40px;color:#FF3300">&nbsp;Time out: <input type="text" id="time_out" value="<?php echo CHECK_OUT_TIME;?>" style="width:40px;color:#FF3300">&nbsp;<input type="button" onclick="reservation();" value="[[.reserve.]]" /><br clear="all">
<!--LIST:room_levels-->
<span style="border:1px solid #CCCCCC;float:left;margin:2px;padding:2px;background:#FFFFCC;font-size:11px;">[[|room_levels.name|]] <input type="text" id="room_price_[[|room_levels.id|]]" value="<?php echo System::display_number([[=room_levels.price=]]);?>" style="font-size:10px;width:50px;color:#0033FF"></span>
<!--/LIST:room_levels-->
<!--/IF:reservation-->
</div>
<script type="text/javascript">
  var freezeRow=1; //change to row to freeze at
  var freezeCol=1; //change to column to freeze at
  var myRow=freezeRow;
  var myCol=freezeCol;
  var speed=100; //timeout speed
  var myTable;
  var noRows;
  var myCells,ID;
function setUp(){
	if(!myTable){myTable=document.getElementById("t1");}
 	myCells = myTable.rows[0].cells.length;
	noRows=myTable.rows.length;
	for( var x = 0; x < myTable.rows[0].cells.length; x++ ) {
		colWdth=myTable.rows[0].cells[x].offsetWidth;
		myTable.rows[0].cells[x].setAttribute("width",colWdth-4);
	}
}
function right(up){
	if(up){window.clearTimeout(ID);return;}
	if(!myTable){setUp();}
	if(myCol<(myCells)){
		for( var x = 0; x < noRows; x++ ) {
			myTable.rows[x].cells[myCol].style.display="";
		}
		if(myCol >freezeCol){myCol--;}
		ID = window.setTimeout('right()',speed);
	}
}
function left(up){
	var currentDay = <?php echo intval(date('d'));?>;
	if(currentDay<5){
		var STOP_P =0.5;
	}else{
		var STOP_P = currentDay/10;
	}
	if((STOP_P < 0.6 && to_numeric($('stop_value').value)*10 == STOP_P*10 - 1) || (to_numeric($('stop_value').value)*10 == STOP_P*10 - 0.5*10)){
		window.clearTimeout(ID);
		window.clearInterval(I_ID);
		$('stop_value').value = -1;
		return;
	}
	if(up){window.clearTimeout(ID);return;}
	if(!myTable){setUp();}
	if(myCol<(myCells-1)){
		for( var x = 0; x < noRows; x++ ) {
			myTable.rows[x].cells[myCol].style.display="none";
		}
		myCol++
		ID = window.setTimeout('left()',speed);
	}
}
function down(up){
	if(up){window.clearTimeout(ID);return;}
	if(!myTable){setUp();}
	if(myRow<(noRows-1)){
			myTable.rows[myRow].style.display="none";
			myRow++	;
			ID = window.setTimeout('down()',speed);
	}
}
function upp(up){
	if(up){window.clearTimeout(ID);return;}
	if(!myTable){setUp();}
	if(myRow<=noRows){
		myTable.rows[myRow].style.display="";
		if(myRow >freezeRow){myRow--;}
		ID = window.setTimeout('upp()',speed);
	}
}
var j = 0;
function count(){
	j = j + 1/10;
	$('stop_value').value = roundNumber(j,2);
}
I_ID = window.setInterval('count()',100);
left();
var selectedRooms = '';
function selectColumn(columnId,roomId,day){
	$(columnId).className = 'room-element-selected';
	dateFrom = $('date_from_'+roomId).value?$('date_from_'+roomId).value:(day+'/[[|month|]]/[[|year|]]');
	dateTo = $('date_to_'+roomId).value?$('date_to_'+roomId).value:((to_numeric(day) + 1)+'/[[|month|]]/[[|year|]]');
	if(selectedRooms.search('#'+roomId+'#') == -1){
		dayFrom = day;
		dayTo = to_numeric(day) + 1;
	}else{
		var re =  new RegExp("(@#"+roomId+"#(.)+@)","g");
		var matchStr = selectedRooms.match(re);
		selectedRooms = selectedRooms.replace(re,"");
		newStr = matchStr[0];
		newStrArr = newStr.split(',');
		newDateFrom = newStrArr[1];
		newDateTo = newStrArr[2];
		newDateFromArr = newDateFrom.split('/');
		dayFrom = to_numeric(newDateFromArr[0]);
		newDateToArr = newDateTo.split('/');
		dayTo = to_numeric(newDateToArr[0]);
		day = to_numeric(day);
		/*if(selectedRooms){
			selectedRooms += '|';
		}else{
			selectedRooms += '@#'+roomId + '#,' + dateFrom +','+ dateTo + '@';
		}*/
		if(day==dayFrom && day==dayTo-1){
			$(columnId).className = 'room-element';
			dayFrom = 0;
			dayTo = 0;
		} else if(day==dayFrom && day < dayTo-1) {
			dayFrom = day;
			dayTo = to_numeric(day) + 1;
		} else if(day > dayFrom){
			if(selectedRooms){
				selectedRooms += '|';
			}	
			dayTo = day+1;
		}
	}
	for(j=1;j<=<?php echo [[=days_in_month=]]?>;j++){
		if($(roomId+'_'+j)){
			if(j<dayFrom || j>dayTo-1){
				$(roomId+'_'+j).className = 'room-element';
			}else{
				$(roomId+'_'+j).className = 'room-element-selected';
			}
		}
	}
	if(selectedRooms){
		selectedRooms += '|';
	}
	if(dayFrom){
		dateFrom = dayFrom+'/[[|month|]]/[[|year|]]';
		dateTo = dayTo+'/[[|month|]]/[[|year|]]';
	}else{
		dateFrom = '';
		dateTo = '';
	}
	if(dateTo && dateTo){
		selectedRooms += '@#'+roomId + '#,' + dateFrom +','+ dateTo + '@';
	}
	//alert(selectedRooms);
	$('date_from_'+roomId).value = dateFrom;
	$('date_to_'+roomId).value = dateTo;	
}
var query_string = '';
function reservation()
{
	selectedRooms = selectedRooms.replace(/#/g,"");
	selectedRooms = selectedRooms.replace(/@/g,"");
	query_string += selectedRooms;
	<!--LIST:room_levels-->
	query_string += '&room_prices['+[[|room_levels.id|]]+']='+$('room_price_'+[[|room_levels.id|]]).value;
	<!--/LIST:room_levels-->
	window.open('?page=reservation&cmd=add&time_in='+$('time_in').value+'&time_out='+$('time_out').value+'&rooms='+query_string);
}
</script>
