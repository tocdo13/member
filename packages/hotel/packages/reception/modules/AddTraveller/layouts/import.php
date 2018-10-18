<!--tieubinh add from import dữ liệu từ file excel -->
<style>
.none{display:none;}
.khung {
  display: block;
  background: #fff;
  width: 90%;
  margin:0 auto;
  top: 2%;
  border: 3px solid #AF8888;
  padding: 0;
}
.testTable {
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
</style>
<div id="popub">
<div class="khung">
<ul class="testTable">
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
<div style="border: 1px solid #cdcdcd;margin-bottom:20px"></div>
<a href="packages\hotel\packages\sale\modules\Customer\mau_khach.rar" 
style="background: -webkit-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -o-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -moz-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); padding: 10px; color: #555555; font-weight: bold; border: 5px solid #ccfefa; text-decoration: none;">[[.download_excel_pattern.]]
</a><br /><br /><br />
<div>
<span style="color: red; font-weight: bold; text-transform: uppercase;">Chú ý:</span>Download file excel mẫu và làm theo hướng dẫn bên dưới
<p>Cột <strong>E</strong>: Nhập quốc tịch (mã quốc tịch tồn tại <a target="_blank" href="?page=customer&cmd=list_national&search_na=national">ở đây</a>)</p>
<p>Cột <strong>F</strong>: Nhập tỉnh/thành phố (danh sách Tỉnh/thành phố thuộc VN <a target="_blank" href="?page=customer&cmd=list_national&search_na=city">ở đây</a>)</p>
<p>Cột <strong>G</strong>: Nhập quận huyện (danh sách Quận/huyện thuộc VN <a target="_blank" href="?page=customer&cmd=list_national&search_na=district">ở đây</a>)</p>
<p> Nhập quận huyện (danh sách Quận/huyện thuộc VN <a target="_blank" href="?page=province&cmd=list">ở đây</a>)</p>
<p> Nhập quận huyện (danh sách Quận/huyện thuộc VN <a target="_blank" href="?page=guest_level&cmd=list">ở đây</a>)</p>
<p><?php
    if(count([[=room_double=]])>0){
        $room_double ='';
        foreach([[=room_double=]] as $key=>$val){
            if($room_double =='')
                $room_double = $key;
            else
                $room_double = $room_double.','.$room_double;    
        }
        echo '<span style="color:red">Các phòng sau đây bắt buộc phải nhập ngày thời gian và ngày check in check out:'.$room_double.'</span>';
    }
  ?></p>
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
        }
    
        ?>
        <td class="center"> Error</td>    
    </tr>
    
    <?php
    $i=0;
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
           
  }
    ?>
</table>
</form>
</li>  
 </ul>
</div>
</div>