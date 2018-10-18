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
       
        <span style="font-size: 14px;"><?php echo Portal::language('date');?> <?php echo $this->map['date'];?></span>
        </div></td>      
        <td align="right" style="padding-right:10px;" ><strong><?php echo Portal::language('template_code');?></strong>
        
       
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
              <th class="report-table-header" align="center" width="100px"><?php echo Portal::language('room_type');?></th>
              <th class="report-table-header" align="center" width="100px">Room status</th>
			  <th class="report-table-header" align="center">Change status</th>
              <th class="report-table-header" align="center">Attendance</th>
			  <th class="report-table-header" align="center">Remark</th>
		  </tr>
          
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr>
                <td align="center"><?php echo $this->map['items']['current']['index'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['room_status'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['name'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['brief_name'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['status'];?></td>
                <td align="center"></td>
                <td align="center"><?php echo $this->map['items']['current']['staff_name'];?></td>
                <td align="center"></td>
            </tr>
		  <?php }}unset($this->map['items']['current']);} ?>		
		</table>
	</div>
	<input  name="save" id="save" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('save'));?>">
    <input  name="re_add" id="re_add" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('re_add'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>