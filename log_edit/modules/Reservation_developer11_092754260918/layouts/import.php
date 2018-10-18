<!--tieubinh add from import dữ liệu từ file excel -->
<style>

.khung {
  
}
.khung2 {
  max-height: 500px;
  overflow-y: scroll;
  padding: 0px;
  width: 100%;
}
.table_error td{border:1px solid #9B8D8D;}
.center{text-align:center;}
.data_previe{padding-left:10px;  white-space: nowrap;}
.yellow{background:#EAF2D3}
.while{background:#fff}
.title_p{font-style:italic;font-size:13px;font-weight:normal;  line-height: 30px;
  padding-right: 51px;}
.font_a{font-size:14px;  text-decoration: underline;}
</style>

<div class="">

<ul class="khung2">
  <li>  
   <form name="UpdateTravellerForm" method="post" enctype="multipart/form-data">
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound"  >
    		<tr>
            	<td width="70%" class="form-title">[[.import_from_excel.]]</td>
                <td width="30%" align="right" nowrap="nowrap">
                	
                    <?php if(User::can_add(false,ANY_CATEGORY)){?>
                    <input name="save" type="submit" value="save" class="button-medium-save"/>
                    <?php }?>
    				
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                   <a href="#" onclick="onBackEvent();"  class="button-medium-back">[[.back.]]</a>
                    <?php }?>
                </td>
            </tr>
</table>
</form>

 <form name="UpdateTravellerForm" method="post" enctype="multipart/form-data">
<div style="border: 1px solid #cdcdcd;margin-bottom:20px"></div>
<a href="packages\hotel\packages\sale\modules\Customer\file_import_traveller.rar" 
style="background: -webkit-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -o-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -moz-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); padding: 10px; color: #555555; font-weight: bold; border: 5px solid #ccfefa; text-decoration: none;">[[.download_excel_pattern.]]
</a>
<br /><br /><br />
<div>

<table>
    <tr>
        <td class="title_p">
         Xem mã quốc tịch
        </td>
        <td>
         <a class="font_a" target="_blank" href="?page=customer&cmd=list_national&search_na=national">ở đây</a>
        </td>
      
    </tr>
    
    <tr>
         <td class="title_p">
        Danh sách Tỉnh/thành phố
        </td>
        <td>
         <a class="font_a" target="_blank" href="?page=province&cmd=list">ở đây</a>
        </td>
    </tr>
    <tr>
         <td class="title_p">
       Xem tên hạng khách
        </td>
        <td>
         <a class="font_a" target="_blank" href="?page=guest_level&cmd=list">ở đây</a>
        </td>
    </tr>
    <tr>
       <td><p style="color:red">Bắt buộc nhập thời gian check in, check out </td>
    </tr>
  
</table>
<p class="title_p">
<?php
if(isset([[=room_double=]])){
    if(count([[=room_double=]])>0){
    
        echo '<p style="color:red;display: none;">Các phòng dưới đây bắt buộc phải nhập thời gian checkin checkout</p><table class="table_error" style="display: none;">
            
            <tr style="background:#cdcdcd;">
            <td>Phòng</td>
            <td>STT</td>         
            <td>Thời gian checkin</td> 
            <td>Thời gian checkout</td>
            </tr> 
            ';
        foreach([[=room_double=]] as $key=>$val){
            //System::debug(sizeof($val));
            echo '<tr>
                    <td rowspan="'.sizeof($val).'" style="padding:5px 10px;">'.$key.'</td>';
            $i=0;        
            foreach($val as $v){
                    $i++;
                  echo '
                    <td style="padding:5px 10px;">'.$i.'</td>
                    <td style="padding:5px 10px;">'.date('H:i d/m/Y',$v['time_in']).'</td>
                    <td style="padding:5px 10px;">'.date('H:i d/m/Y',$v['time_out']).' </td>
                  </tr>';
            }
           
        }
        echo '</table>';
      
    }}
  ?>
  </p>
</div>
<fieldset>
	<legend class="title">[[.upload.]]</legend>
	<table border="0" cellspacing="0" cellpadding="2">
		<tr>
            <td><input name="path" id="path" type="file"  /></td>
            <td><input name="do_upload" type="submit" id="do_upload" value="[[.upload_and_preview.]]" style="width: 180px;"/></td>
		</tr>
	</table>
</fieldset>
<table style="width:100%; " class="table_error">
    <tr style="background: #cdcdcd;">

        <?php
    
            if(count([[=col_name=]])>0){
            foreach([[=col_name=]] as $key=>$val){
                echo '<td class="center">'.$val.'</td>';
            }
            echo '<td class="center"> Error</td>';
            }
       
        ?>
         
    </tr>
    
    <?php
    $i=0;
    if(isset([[=result=]])){
  foreach([[=result=]] as $key=>$val){
        $i++;
        if($i%2==0)
            $bg='yellow';
        else
            $bg='while';     
        echo "<tr class=".$bg.">";
            foreach($val as $k=>$v)
            {
                if($k !='error')
                     echo "<td class='data_previe'>".$v."</td>";
                  
            }
               echo "<td class='data_previe'>".$val['error']."</td>";
           echo "</tr>"; 
           
  }}
    ?>
  <?php
    
    if(isset([[=preview=]])){
  foreach([[=preview=]] as $key=>$val){
        echo "<tr>";
            echo "<td class='data_previe' style='text-align: center;'>".$val['mi_traveller_room_name']."</td>";
            echo "<td class='data_previe' style='text-align: center;'>".$val['first_name']."</td>";
            echo "<td class='data_previe' style='text-align: center;'>".$val['last_name']."</td>";
            echo "<td class='data_previe'>".$val['traveller_level_id']."</td>";
            //echo "<td class='data_previe'>".str_replace('|',':',$val['arrival_hour']).'-'.$val['traveller_arrival_date']."</td>";
            //echo "<td class='data_previe'>".str_replace('|',':',$val['departure_hour']).'-'.$val['traveller_departure_date']."</td>";
            echo "<td class='data_previe'>".str_replace('|',':',$val['arrival_hour']);
            echo "<td class='data_previe'>".str_replace('|',':',$val['departure_hour']);
            echo "<td class='data_previe'></td>";
        echo "</tr>"; 
           
  }}
    ?>
</table>
</form>
</li>  
 </ul>
 <br /><br />
<div style="display:inline-block;padding-left: 450px; color: red;"> 
<?php
    if(isset([[=success=]])){
        
        echo [[=success=]];
    }
?>
</div>
</div>
<script>
jQuery(document).ready(function(){
    jQuery('#do_upload').click(function(){
        if(!jQuery('#path').val())
        {
            alert('[[.you_must_choose_file.]]')
            return false;
        }
        
    })
})
</script>
