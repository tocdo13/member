<?php $biendembanghitrangcuoi=0; $abc='';
?>
<script type="text/javascript">
  function room_detail(x1,x2)
  {
	// alert('?page=reservation&cmd=edit&id='+x1+'&r_r_id='+x2);
     window.open('?page=reservation&cmd=edit&id='+x1+'&r_r_id='+x2);
  }
</script>
<style type="text/css">
hr{ border:1px thin;}
</style>
<!---------REPORT----------->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="0%" align="center" nowrap="nowrap" class="report_table_header">[[.stt.]]</th>
		<th width="1%" align="center" nowrap="nowrap" class="report_table_header">[[.code.]]</th>
        <th nowrap="nowrap" class="report_table_header">[[.room.]]</th>
        <th nowrap="nowrap" class="report_table_header">[[.room_type.]]</th>
        <th nowrap="nowrap" class="report_table_header">[[.price.]]</th>
		<th class="report_table_header" nowrap="nowrap" >[[.customer_name.]]</th>
        <th class="report_table_header"  nowrap="nowrap">[[.nationality.]]</th>
         <th class="report_table_header"  nowrap="nowrap">[[.num_person.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.date_in.]]</th>
		<th nowrap="nowrap" class="report_table_header">[[.date_out.]]</th>
        <th nowrap="nowrap" class="report_table_header">[[.num_night.]]</th>
        <th nowrap="nowrap" class="report_table_header">[[.folio.]]</th>
        <th nowrap="nowrap" class="report_table_header">[[.company.]]</th>
    <?php //muc dich la neu co nhieu dong dau tien khong co stt thi in o trang o stt
	$bienxuongdong=0;
	?>
	<!--LIST:items-->
    <?php $biendembanghitrangcuoi++;?>
    <tr bgcolor="white">
        <?php
		$variable = ([[=itemperonepage=]]*([[=page_no=]]-1)+1);
		// neu ton tai tong so nguoi trong phong ung voi tung nguoi trong phong
		if(isset([[=items.count_traveller=]]) and [[=items.count_traveller=]] != '')
		{
		  $bienxuongdong++;
		 //in ra hang voi stt la rowspan 'so nguoi trong phong' hang
		  echo "<td width='5' rowspan='".[[=items.count_traveller=]]."' align='center' valign='middle' class='report_table_column'>".[[=items.stt=]]."</td>";
		  echo "<td width='5' rowspan='".[[=items.count_traveller=]]."' align='center' valign='middle' class='report_table_column'><a href='". Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.id_res=]],'r_r_id'=>[[=items.r_r_id=]]))."' target='_blank'>".[[=items.r_r_id=]]."</a></td>";
		  echo "<td width='1%' rowspan='".[[=items.count_traveller=]]."' nowrap align='center' class='report_table_column'>".[[=items.room_name=]]."</td>";
		  echo "<td width='1%' rowspan='".[[=items.count_traveller=]]."' nowrap align='center' class='report_table_column'>".[[=items.room_type_name=]]."</td>";
		  echo "<td width='1%' rowspan='".[[=items.count_traveller=]]."' nowrap align='right' class='report_table_column'>".System::display_number([[=items.price=]])."</td>";
		}
		else
		{ //neu trong phong khong co ma traveller vi di theo tour, hoac gi gi do thi in hang do binh thuong rowspan=1
		 if([[=items.first_name=]] =='' and [[=items.last_name=]] =='')
		 {
		 echo "<td width='5' align='center' valign='middle' class='report_table_column'>".[[=items.stt=]]."</td>";
		 echo "<td width='5' align='center' valign='middle' class='report_table_column'><a href='". Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.id_res=]],'r_r_id'=>[[=items.r_r_id=]]))."' target='_blank'>".[[=items.r_r_id=]]."</a></td>";
		 echo "<td width='1%' nowrap align='center' class='report_table_column'>".[[=items.room_name=]]."</td>";
		 echo "<td width='1%' nowrap align='center' class='report_table_column'>".[[=items.room_type_name=]]."</td>";
		 echo "<td width='1%' nowrap align='right' class='report_table_column'>".System::display_number([[=items.price=]])."</td>";
		 $bienxuongdong++;
		 }
		 else
		 {
		   //if([[=items.stt_phu=]]== $variable)
		   if(isset([[=items.stt=]]))
		   {
		     $bienxuongdong++;
		   }
		   else
		   {
		     if($bienxuongdong == 0)
			 {
		     echo "<td width='5' align='center' valign='middle' class='report_table_column' style='border-bottom:none !important; border-top:none !important;'></td>";
			 echo "<td width='5' align='center' valign='middle' class='report_table_column' style='border-bottom:none !important; border-top:none !important;'></td>";
			 echo "<td width='1%' nowrap align='center' class='report_table_column' style='border-bottom:none !important; border-top:none !important;'></td>";
			 echo "<td width='1%' nowrap align='center' class='report_table_column' style='border-bottom:none !important; border-top:none !important;'></td>";
			 echo "<td width='1%' nowrap align='center' class='report_table_column' style='border-bottom:none !important; border-top:none !important;'></td>";
			 }
		   }
		 }
		 //neu khong phai la nguoi dau tien trong so nhung nguoi trong phong thi cot stt se khong in ra
		}
		?>
		<!--<td width="5" align="center" valign="middle" class="report_table_column"><a href="<?php //echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.id_res=]],'r_r_id'=>[[=items.r_r_id=]]));?>" target="_blank">[[|items.r_r_id|]]</a></td>-->
		<!--<td width="1%" nowrap align="center" class="report_table_column">[[|items.room_name|]]</td>-->
       <!-- <td width="1%" nowrap align="center" class="report_table_column">[[|items.room_type_name|]]</td>-->
        <!--<td width="1%" nowrap align="right" class="report_table_column"><?php //echo System::display_number([[=items.price=]]); ?></td>-->
        <!--<td nowrap align="left" class="report_table_column" width="150"><?php //if([[=items.first_name=]].[[=items.last_name=]]!='')
		  //{  // $strmid = [[=items.first_name=]].' '.[[=items.last_name=]];
			  //if(strlen($strmid) < strlen([[=items.customer_name=]]))
			  //{
			    //echo [[=items.customer_name=]];
			  //}
			 // else
			 // {
			   //echo $strmid;
			  //}
		 // }
		  //else
		  //{ echo  //[[=items.tourname=]].'-tour-'.[[=items.customer_name=]]; }
		  ?> </td>-->
          <td nowrap align="left" class="report_table_column" width="150">
		  <?php
		   /* $str='';
			$tongmang = count([[=items.name_all=]]);
			$xuly=0;
		    foreach([[=items.name_all=]] as $kiki=>$vava)
			{
			   $xuly++;
			   $str.=$vava['first_name'].$vava['last_name'];
			   if($tongmang >1 and $xuly >1)
			   { echo "<hr>";}
			   echo "<a href='".Url::build('traveller',array('id'=>[[=items.traveller_level_id=]]))."' target=_blank'>".$vava['first_name'].' '.$vava['last_name'].'</a><br>';
			}
			if($str == '')
			{
				$abc= [[=items.tourname=]].'-tour-'.[[=items.customer_name=]];
			}
			else
			{
			   $abc='';
			}*/
			echo [[=items.first_name=]].' '.[[=items.last_name=]];
		  ?>
           </td>
		<td nowrap align="center" class="report_table_column">
			[[|items.name_country|]]		</td>
        <?php 
		if(isset([[=items.count_traveller=]]) and [[=items.count_traveller=]] != '')
		{
			echo "<td width='5' rowspan='".[[=items.count_traveller=]]."' align='center' valign='middle' class='report_table_column'>".([[=items.num_people=]]+[[=items.num_child=]])."</td>";
			
			echo "<td width='1%' rowspan='".[[=items.count_traveller=]]."' align='center' valign='middle' class='report_table_column'>".date('d/m/Y',[[=items.time_in=]])."</td>";
			
			echo "<td width='1%' rowspan='".[[=items.count_traveller=]]."' align='center' valign='middle' class='report_table_column'>".date('d/m/Y',[[=items.time_out=]])."</td>";
			
			echo "<td width='1%' rowspan='".[[=items.count_traveller=]]."' align='center' valign='middle' class='report_table_column'>".[[=items.num_night=]]."</td>";
		}
		else
		{ 
		   if([[=items.first_name=]] =='' and [[=items.last_name=]] =='')
		 {
			 echo "<td width='5' align='center' valign='middle' class='report_table_column'>".([[=items.num_people=]]+[[=items.num_child=]])."</td>";
			 
			  echo "<td width='1%' align='center' valign='middle' class='report_table_column'>".date('d/m/Y',[[=items.time_in=]])."</td>";
			  
			  echo "<td width='1%'  align='center' valign='middle' class='report_table_column'>".date('d/m/Y',[[=items.time_out=]])."</td>";
			
			echo "<td width='1%'  align='center' valign='middle' class='report_table_column'>".[[=items.num_night=]]."</td>";
		 }
		 else
		 { 
		 }
		}
		?>    
            
      <!-- <td width="1%" nowrap align="center" class="report_table_column"><?php //echo ([[=items.num_people=]] + [[=items.num_child=]]);?></td>
       <td width="1%"  align="center" class="report_table_column"><?php //echo date('d/m/Y',[[=items.time_in=]]);?></td>
	   <td width="1%"  align="center" class="report_table_column"><?php //echo date('d/m/Y',[[=items.time_out=]]);?></td>
	<td  align="center" class="report_table_column">
			[[|items.num_night|]]</td>-->
    <td nowrap align="center" class="report_table_column">
			<a href="" target="_blank">[[|items.rrid|]]</a></td>
    <td nowrap align="center" class="report_table_column">[[|items.customername|]]<?php //echo $abc; ?></td>
	</tr>
	<!--/LIST:items-->
	<!--IF:first_page([[=page_no=]]==[[=total_page=]])-->
    
	<tr bgcolor="white">
		<td colspan="2" align="center" valign="middle" class="report_table_column"><strong>[[.total.]]</strong></td>
		<td nowrap align="center" class="report_table_column"><strong><?php  echo ([[=total_record=]]+$biendembanghitrangcuoi);?></strong></td>
		<td colspan="1"></td>
        <td colspan="1" nowrap align="right" class="report_table_column" width="30"><strong><?php echo System::display_number([[=total_money=]]);?></strong></td>
        
        <td colspan="2" nowrap align="center" class="report_table_column" width="30"></td>
        <td colspan="1" nowrap align="center" class="report_table_column" width="30"><?php echo'<b>'.[[=total_adult=]].'/'.[[=total_childrent=]].'</b>';?></td>
		<td colspan="2"></td>
        <td colspan="1" align="right" style="padding-right:28px;"><strong>[[|total_night|]]</strong></td>
        <td colspan="2"></td>
	</tr>
	<!--/IF:first_page-->
</table>