<style>

</style>
<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width=""><strong><img src="<?php echo HOTEL_LOGO;?>" width="100"></strong><br />
               </td>
         <td ><div style="width:100%; text-align:center;"> <font class="report_title" >FL/SUPERVISOR CHECK LIST<br />
        </font> 
       
        <span style="font-size: 14px;">[[.date.]] [[|date|]]</span>
        </div></td>      
        <td align="right" style="padding-right:10px;" ><strong>[[.template_code.]]</strong>
        
       
         </td>
      </tr>
    </table></td>
  </tr>
</table>
<div  id="style_edit">
<form name="table" id="table" method="post"> 
		<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; margin-top: 15px;">  
          <tr class=table-header>
              <th class="report-table-header" align="center" width="20px">STT</th>
              <th class="report-table-header" align="center" width="100px">IO</th>
			  <th class="report-table-header" align="center" width="100px">Room</th>
              <th class="report-table-header" align="center" width="100px">[[.room_type.]]</th>
              <th class="report-table-header" align="center" width="100px">Room status</th>
			  <th class="report-table-header" align="center">Change status</th>
              <th class="report-table-header" align="center">Attendance</th>
			  <th class="report-table-header" align="center">Remark</th>
		  </tr>
          
		  <!--LIST:items-->
            <tr>
                <td align="center">[[|items.index|]]</td>
                <td align="center">[[|items.room_status|]]</td>
                <td align="center">[[|items.name|]]</td>
                <td align="center">[[|items.brief_name|]]</td>
                <td align="center">[[|items.status|]]</td>
                <td align="center"></td>
                <td align="center">[[|items.staff_name|]]</td>
                <td align="center"></td>
            </tr>
		  <!--/LIST:items-->		
		</table>
	</div>
	<input name="save" type="hidden" id="save" value="" />
    <input name="re_add" type="hidden" id="re_add" value="" />
</form>	
</div>