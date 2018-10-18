<?php $biendembanghitrangcuoi=0;?>
<script type="text/javascript">
  function room_detail(x1,x2)
  { 
	// alert('?page=reservation&cmd=edit&id='+x1+'&r_r_id='+x2);
     window.open('?page=reservation&cmd=edit&id='+x1+'&r_r_id='+x2);
    
  }
</script>
<!---------REPORT----------->	

<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
    
		<th width="1%" align="center" nowrap="nowrap" class="report_table_header">[[.stt.]]</th>
        
		<th width="1%" align="center" nowrap="nowrap" class="report_table_header">[[.code.]]</th>
        
        <th  width="1%" class="report_table_header">[[.room.]]</th>
        
        <th  width="6%" class="report_table_header">[[.room_type.]]</th>
        
        <th width="10%" class="report_table_header">[[.price.]]</th>
        
		<th width="10%" class="report_table_header" >[[.customer_name.]]</th>
        
        <th width="10%" class="report_table_header"  >[[.nationality.]]</th>
        
         <th width="1%" class="report_table_header"  >[[.num_person.]]</th>
        
		<th  class="report_table_header">[[.date_in.]]</th>
        
		<th  class="report_table_header">[[.date_out.]]</th>
        
        <th  width="10%" class="report_table_header">[[.company.]]</th>
        
        <th  class="report_table_header">[[.note.]]</th>
        
       
         
	<!--LIST:items-->
    <?php $biendembanghitrangcuoi++;?>
    <tr bgcolor="white">
    
		<td width="1%" align="center" valign="middle" class="report_table_column">[[|items.stt|]]</td>
        
		<td width="1%" align="center" valign="middle" class="report_table_column">[[|items.id|]]</td>
        
		<td width="6%"  align="center" class="report_table_column">[[|items.room_name|]]</td>
        
        <td width="10%"  align="center" class="report_table_column">[[|items.room_type_name|]]</td>
        
        <td width="10%"  align="center" class="report_table_column"><?php echo System::display_number([[=items.price=]]); ?></td>
        
        <td  align="left" class="report_table_column" width="13%"><?php if([[=items.first_name=]].[[=items.last_name=]]!='')
		  {   $strmid = [[=items.first_name=]].' '.[[=items.last_name=]];
			  if(strlen($strmid) < strlen([[=items.customer_name=]]))
			  { 
			    echo [[=items.customer_name=]];
			  }
			  else
			  {
			   echo $strmid;
			  }
		  }
		  else
		  {
			  // echo  [[=items.tourname=]].'-tour-'.[[=items.customer_name=]]; 
		  }
		  ?> </td>
          
		
		<td  align="center" class="report_table_column" width="13%">
			[[|items.name_country|]]		</td>
            
       <td width="1%"  align="center" class="report_table_column"><?php echo ([[=items.num_people=]] + [[=items.num_child=]]);?></td>
            
		
        
       <td width="10%"  align="center" class="report_table_column">
			<?php echo date('d/m/Y',[[=items.time_in=]]);?></td>
            
            
	   <td width="10%"  align="center" class="report_table_column">
			<?php echo date('d/m/Y',[[=items.time_out=]]);?></td>
              	
	   <td width="10%"  align="center" class="report_table_column">[[|items.customername|]]</td>
       
        <td width="6%"  align="center" class="report_table_column">[[|items.note|]]</td>
    
     
            
	</tr>
	<!--/LIST:items-->
	<!--IF:first_page([[=page_no=]]==[[=total_page=]])-->
    
	<tr bgcolor="white">
        
		<td colspan="1%" align="center" valign="middle" class="report_table_column"><strong>[[.total.]]</strong></td>
		<td align="center" class="report_table_column"><strong><?php  echo ([[=total_record=]]+$biendembanghitrangcuoi);?></strong></td>
		<td colspan="10"  align="center" class="report_table_column" width="30"></td>
        
	</tr>
	<!--/IF:first_page-->
</table>