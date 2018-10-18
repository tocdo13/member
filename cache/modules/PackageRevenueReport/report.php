<?php //System::debug($this->map['items']); ?>
<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left" width="85%"><strong><?php echo HOTEL_NAME;?></strong><br />
              <strong><?php echo HOTEL_ADDRESS;?></strong> </td>
        <td align="right" style="padding-right:10px;" ><strong><?php echo Portal::language('template_code');?></strong>
        
       
         </td>
      </tr>
      <tr>
        <td colspan="2"><div style="width:100%; text-align:center;"> <font class="report_title" ><?php echo Portal::language('package_revenue_report');?><br />
        </font> </div></td>
      </tr>
    </table></td>
  </tr>
</table>
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>

<!---------HEADER----------->

<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <link rel="stylesheet" type="text/css" href="packages/core/skins/default/css/global.css" media="print">
</div>
<!---------SEARCH----------->

<table width="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search"> 
    <tr><td>
    	<link rel="stylesheet" href="skins/default/report.css"/>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto">
                                <tr>  
                                    <td>Gói sử dụng: </td>
                                    <td>
                                        <select  name="package_sale_id" id="package_sale_id" style="width: 120px;"><?php
					if(isset($this->map['package_sale_id_list']))
					{
						foreach($this->map['package_sale_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('package_sale_id',isset($this->map['package_sale_id'])?$this->map['package_sale_id']:''))
                    echo "<script>$('package_sale_id').value = \"".addslashes(URL::get('package_sale_id',isset($this->map['package_sale_id'])?$this->map['package_sale_id']:''))."\";</script>";
                    ?>
	<?php echo $this->map['package_options'];?></select>
                                    </td>   
                                    
                                    <td>Khách hàng: </td>
                                    <td><input  name="customer_name" id="customer_name" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"></td>
                                    
                                    <td>Mã MĐ:</td>
                                    <td><input  name="recode" id="recode" style="width: 50px;" / type ="text" value="<?php echo String::html_normalize(URL::get('recode'));?>"></td>
                                    
                                    <td><?php echo Portal::language('room');?>: </td>
                                    <td> <select  name="room_id" id="room_id" style="width: 80px;"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	<?php echo $this->map['room_options'];?> </select></td>
                                       
                                    <td>Từ ngày: </td>
                                	<td><input  name="from_date" id="from_date" style="width: 100px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>"></td>
                                    
                                    <td>Đến ngày: </td>
                                    <td><input  name="to_date" id="to_date" style="width: 100px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>"></td>
                                    <td><input type="submit" name="do_search" value="<?php echo Portal::language('report');?>"/></td>
                                </tr>
                            </table>
                        <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>
<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr bgcolor="#EFEFEF">
	    <th  class="report-table-header"><?php echo Portal::language('stt');?></th>
        <th  class="report-table-header"><?php echo Portal::language('package');?></th>
        <th  class="report-table-header">Khách hàng sử dụng</th>
        <th  class="report-table-header">Mã MĐ</th>
        <th  class="report-table-header"><?php echo Portal::language('room');?></th>
        <th  class="report-table-header"><?php echo Portal::language('guest_name');?></th>
        <th  class="report-table-header"><?php echo Portal::language('from_date');?></th>
        <th  class="report-table-header"><?php echo Portal::language('to_date');?></th>
        <th  class="report-table-header"><?php echo Portal::language('amount');?></th>
        
	</tr>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <tr>
            <?php
                if($this->map['items']['current']['row_span_package']!=0)
                {
                    ?>
                    <td rowspan="<?php echo $this->map['items']['current']['row_span_package'];?>"><?php echo $this->map['items']['current']['index'];?></td>
                    <td rowspan="<?php echo $this->map['items']['current']['row_span_package'];?>" align="left"><?php echo $this->map['items']['current']['package_name'];?></td>
                    <?php 
                }
                if($this->map['items']['current']['row_span_customer']!=0)
                {
                    ?>
                    <td rowspan="<?php echo $this->map['items']['current']['row_span_customer'];?>" align="left"><?php echo $this->map['items']['current']['customer_name'];?></td>
                    <?php 
                } 
                if($this->map['items']['current']['row_span_recode']!=0)
                {
                    ?>
                    <td rowspan="<?php echo $this->map['items']['current']['row_span_recode'];?>"><a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id']));?>"><?php echo $this->map['items']['current']['reservation_id'];?></a></td>
                    <?php 
                }
                if($this->map['items']['current']['row_span_room']!=0)
                {
                    ?>
                    <td rowspan="<?php echo $this->map['items']['current']['row_span_room'];?>" ><?php echo $this->map['items']['current']['room_name'];?></td>
                    <?php 
                }
                
            ?>
            
            <td align="left"><?php echo $this->map['items']['current']['traveller_name'];?></td>
            <?php
                if($this->map['items']['current']['row_span_room']!=0)
                {
                    ?>
                    <td rowspan="<?php echo $this->map['items']['current']['row_span_room'];?>" ><?php echo $this->map['items']['current']['arrival_time'];?></td>
                    <td rowspan="<?php echo $this->map['items']['current']['row_span_room'];?>" ><?php echo $this->map['items']['current']['departure_time'];?></td>
                    <td rowspan="<?php echo $this->map['items']['current']['row_span_room'];?>"  align="right"><?php echo $this->map['items']['current']['total_amount'];?></td>
                    <?php 
                } 
            ?>
            
        </tr>
        
    <?php }}unset($this->map['items']['current']);} ?>
        <?php
            if($this->map['total_amount']!=0)
            {
                ?>
                <tr>
                    <td align="right" colspan="8"><strong>Tổng tiền</strong></td>
                    <td align="right"><strong><?php echo $this->map['total_amount'];?></strong></td>
                    
                </tr>
                <?php 
            }
        ?>
        
</table>
 
<p>&nbsp;</p>

<table  cellpadding="5" cellspacing="0" width="80%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
    <tr bgcolor="#EFEFEF">
        <th class="report-table-header" colspan="5">THỐNG KÊ SỬ DỤNG GÓI PACKAGE</th>
        
    </tr>
	<tr bgcolor="#EFEFEF">
	    <th  class="report-table-header"><?php echo Portal::language('stt');?></th>
        <th  class="report-table-header">Gói sử dụng</th>
        <th  class="report-table-header">Đơn giá</th>
        <th  class="report-table-header">Số lượng</th>
        <th  class="report-table-header">Thành tiền</th>
	</tr>
    <?php if(isset($this->map['package_summary']) and is_array($this->map['package_summary'])){ foreach($this->map['package_summary'] as $key2=>&$item2){if($key2!='current'){$this->map['package_summary']['current'] = &$item2;?>
        <tr>
        <td><?php echo $this->map['package_summary']['current']['index'];?></td>
        <td align="left"><?php echo $this->map['package_summary']['current']['name'];?></td>
        <td align="right"><?php echo $this->map['package_summary']['current']['price'];?></td>
        <td><?php echo $this->map['package_summary']['current']['num_rr_id'];?></td>
        <td align="right"><?php echo $this->map['package_summary']['current']['total_amount'];?></td>
        </tr>
    <?php }}unset($this->map['package_summary']['current']);} ?>
    <?php
        if($this->map['total_amount']!=0)
        {
            ?>
            <tr>
            <td colspan="4" align="right"><strong>Tổng tiền</strong></td>
            <td align="right"><strong><?php echo $this->map['total_amount'];?></strong></td>
            </tr>
            <?php 
        } 
    ?>
    
 </table>
<p>&nbsp;</p>
<script type="text/javascript">
full_screen();
</script>

<style type="text/css">
th,td{white-space:nowrap;}
input[id="from_date"]{width:70px;}
input[id="to_date"]{width:70px;}
input[type="submit"]{width:100px;}

a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>

<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
        jQuery('#to_date').datepicker();
    }
);

</script>
