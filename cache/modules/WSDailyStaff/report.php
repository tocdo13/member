<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
a.report_table_header
{
	background-color:#FFFFFF;
	font-weight:bold;
	font-size:12px;
	line-height:20px;
	color:#000000;
	text-align:center;
}
.report_table_header
{
	background-color:#FFFFFF;
	font-weight:bold;
	font-size:12px;
	padding:5px;
	color:#000000;
}
.report_table_column
{
	padding:3px;
}
.report_table_column1
{
	padding:0px;
}
a.td_title
{
	color:#000000;
}
.report_title
{
	font-size:20px;
	font-weight:bold;
	text-transform:uppercase;
	text-align:center;
}
.report_sub_title
{
	font-weight:normal;
	padding:5px;
}
.report-bound
{
}
@page port {size: portrait;}
@page land {size: landscape;}
.landscape {page: land;}
#printer
{
	width:99%;
	height:100%;
	text-align:center;
	vertical-align:middle;
	background-color:white;
}
/*-----------Common-------------------*/
body *{outline:none;}
body,table{font: 11px arial;margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;color:#000000;}
span,div,pre,a,code, tr{font-size:12px;line-height:15px;color:#000000;margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}
td,input,select{font-size:12px;color:#000000;}
a {color:#003399;text-decoration:none;cursor:pointer;}
a:visited{color:#999999;text-decoration:none;cursor:pointer;}
a:hover,a:visited:hover{color:#039;text-decoration:underline;}
form{margin:0;display:inline;padding:0px;}
ul{margin:0;padding:0;}
img{border:0px;margin:0px 0px 0px 0px;padding:0px 0px 0px 0px;}
.multi-item-wrapper{margin:0px;padding:0px;text-align:left;}
.multi-input-header{float:left;background:url(../images/bg_arrcordion.png);height:15px; font-weight:bold;font-size:11px; border:1px solid #CCC;padding:1px;margin:0px 1px 2px 0px;}
.multi-input-header span,
.multi_edit_input_header span{display:inline-block;}
.multi-input{padding:0px;marging:0px;margin-right:1px;float:left;}
.multi-input input[type="text"]{margin:0px;line-height:15px;font-size:11px;}
.multi-input select{height:21px;font-size:12px;}
input.normal-input{	border:2px solid white;	width:100%;}
.normal-input-text{border:2px solid white;	width:100%;padding:3px 0px 4px 3px;}
.normal-input{border:2px solid #FFFFFF;width:100%;}
.selected-input{border:2px solid black;width:100%;}
label{cursor:pointer;}
form{margin:0;display:inline;padding:0px;}
.main-title{font-size:18px;font-weight:bold;line-height:30px;margin-left:20px;text-transform:uppercase;color:#333333;}
.title{font-size:14px;font-weight:bold;line-height:20px;color:#0066CC;}
.sub-title{font-size:11px;line-height:20px;color:#0066CC;text-transform:uppercase;background-color:#FFF;border:1px solid #CCCCCC;font-weight:bold;}
fieldset{padding:5px;text-align:left;border:1px solid #C6E2FF;}
#error_messages_content1 li{margin-left:20px;#margin-left:10px;}
.error-notice{font-weight:bold;color:#F00;text-transform:uppercase;font-size:11px;}
.error-notice.link{text-transform:none;color:#03C;font-weight:normal;border-bottom:1px dotted #CCC;line-height:20px;}
/*load ding*/
#loading-layer{ padding:25% 0; background:#000000;opacity:0.8;color:#FFFFFF;font-size:100%;position:fixed;z-index:10;width:100%;height:100%;}
* html #loading-layer{ position:absolute;top:expression(eval(document.compatMode && document.compatMode=='CSS1Compat')? documentElement.scrollTop : document.body.scrollTop)}
#loading-layer div{
	text-align:center;
	color:#FFFFFF;
	font-weight:bold;
}
/*------------TABLE-------------------*/
table{border-collapse:collapse;}
.table-title{font-weight:bold;}
.table-header{font-size:11px;font-weight:bold;color:#000066;background:url(../../../../hotel/skins/default/images/reservation_bg.jpg) 0% 0% repeat-x;}
.table-bound{	}
.table-banner-bound{border-collapse:collapse;background-color:#E6E6E6;}
.category-group{background:url(../images/buttons/add.jpg) no-repeat 5px 50%;background-color:#FF9;border-top:1px solid #FC0;border-bottom:1px solid #FC0; text-transform:uppercase;color:#000;padding-left:25px;}
/*-----------/TABLE-------------------*/
.form-title{font-size:14px;font-weight:bold;background:url(../images/list_icon.png) no-repeat 1% 50%;text-indent:45px;text-transform:uppercase;vertical-align:middle;}
.form-title-button{	text-align:center;width:1%;white-space:nowrap;}
.report_title,.report-title{ text-transform:uppercase;font-size:14px; font-weight:bold;}
.report_table_header,.report-table-header{background-color:#EBE9ED;font-size:11px;}
.report_table_column,.report-table-column{font-size:11px;}
input.view-order-button,a.view-order-button{font-weight:bold; border:0px;padding-right:5px;min-width:20px;height:20px;line-height:20px;text-align:left;color:#FF6600;background:url(../images/buttons/order.png) no-repeat 2px 50%; background-color:none;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:20px;cursor:pointer;}
input.print-order-button,a.print-order-button{font-weight:bold; border:0px;padding-right:5px;min-width:20px;height:20px;line-height:20px;text-align:left;color:#FF6600;background:url(../images/vat-icon.png) no-repeat 2px 50%; background-color:none;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:20px;cursor:pointer;}
a.view-order-button{height:16px;padding:2px 2px 0px 5px;}
input.add-order-button,a.add-order-button{font-weight:bold; border:0px;padding-right:5px;min-width:20px;height:20px;line-height:20px;text-align:left;color:#FF6600;background:url(../images/buttons/add_button.gif) no-repeat 2px 50%; background-color:none;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:20px;cursor:pointer;}
a.add-order-button{height:16px;padding:2px 2px 0px 5px;}
/*------------Medium button-------------------*/
input.button-medium,a.button-medium{color:#0A366B;font-size:11px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background-color:#E5E5E5;display:block;min-width:80px;height:22px;padding-top:2px;text-align:left;margin-left:2px;margin-right:2px;float:left;text-decoration:none;cursor:pointer;}
.button-medium.search{background:url(../images/buttons/search.gif) no-repeat 5% 30%;background-color:#E5E5E5;text-indent:25px;}
a.button-medium{height:18px;padding:2px 2px 0px 5px; white-space:nowrap;}
input.button-medium-add,a.button-medium-add{color:#0A366B;font-size:11px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background:url(../images/buttons/add_button.gif) no-repeat 5% 50%;background-color:#E5E5E5;display:block;min-width:80px;height:22px;padding-top:2px;text-align:left;margin-left:2px;margin-right:2px;float:left;text-decoration:none; text-indent:20px;cursor:pointer;}
a.button-medium-add{height:18px;padding:2px 2px 0px 5px; white-space:nowrap;}
input.button-medium-edit,a.button-medium-edit{color:#0A366B;font-size:11px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background:url(../images/buttons/save.jpg) no-repeat 5% 50%;background-color:#E5E5E5;display:block;min-width:80px;height:22px;text-align:left;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:20px;cursor:pointer;}
a.button-medium-edit{	height:18px;padding:2px 2px 0px 5px;}
input.button-medium-delete,a.button-medium-delete{color:#0A366B;font-size:11px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background:url(../images/buttons/delete.gif) no-repeat 5% 50%;background-color:#E5E5E5;display:block;min-width:80px;height:22px;text-align:left;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:20px;cursor:pointer;}
a.button-medium-delete{	height:18px;padding:2px 20px 0px 5px;}
input.button-medium-save,a.button-medium-save{color:#0A366B;font-size:11px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background:url(../images/buttons/save.jpg) no-repeat 5% 50%;background-color:#E5E5E5;display:block;min-width:80px;height:22px;text-align:left;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:20px;cursor:pointer;}
a.button-medium-save{	height:18px;padding:2px 2px 0px 5px;}
input.button-medium-save.stay,a.button-medium-save.stay{
	background:url(../images/buttons/save_and_stay.jpg) no-repeat 5% 50% !important;
}



a.button-medium-back,input.button-medium-back{color:#0A366B;font-size:11px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background:url(../images/buttons/left.jpg) no-repeat 5% 50%;background-color:#E5E5E5;display:block;min-width:80px;height:22px;text-align:left;color:#0A366B;font-size:11px;font-weight:bold;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:20px;cursor:pointer;}
a.button-medium-back{	height:18px;padding:2px 2px 0px 5px;}
a.button-medium-export{	border:1px solid #FFAE5E;display:block;width:120px;height:20px;line-height:19px;text-align:center;color:#000000;font-weight:bold;margin-left:2px;margin-right:2px;float:left;text-decoration:none;background:url(../../../../cms/skins/default/images/admin/mnu_settings.gif) no-repeat 5% 100%;text-indent:20px;cursor:pointer;}
a.button-medium.fullscreen,input.button-medium.fullscreen{color:#0A366B;font-size:11px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background:url(../images/buttons/fullscreen.gif) no-repeat 5% 50%;background-color:#E5E5E5;display:block;min-width:80px;height:22px;text-align:left;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:20px;cursor:pointer;}
a.button-large.fullscreen,input.button-large.fullscreen{color:#0A366B;font-size:14px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background:url(../images/buttons/fullscreen.gif) no-repeat 5% 50%;background-color:#E5E5E5;display:block;min-width:80px;height:40px;text-align:left;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:20px;cursor:pointer;}
a.button-medium.fullscreen{	height:18px;padding:2px 2px 0px 5px;}
a.button-large.fullscreen{	height:40px;padding:2px 2px 0px 5px;}
a.button-medium.checkin,input.button-medium.checkin{color:#0A366B;font-size:11px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background:url(../images/buttons/checkin.png) no-repeat 5% 50%;background-color:#E5E5E5;display:block;min-width:80px;height:22px;text-align:left;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:20px;cursor:pointer;}
a.button-medium.checkin{	height:18px;padding:2px 2px 0px 5px;}
a.button-medium.booked,input.button-medium.booked{color:#0A366B;font-size:11px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background:url(../images/buttons/booked.png) no-repeat 5% 50%;background-color:#E5E5E5;display:block;min-width:80px;height:22px;text-align:left;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:23px;cursor:pointer;}
a.button-medium.booked{	height:18px;padding:2px 2px 0px 5px;}
a.button-medium.payment,input.button-medium.payment{color:#0A366B;font-size:11px;font-weight:bold;border:1px solid #EFEFEF;border-bottom:2px solid #999;border-right:2px solid #999;background:url(../images/buttons/rate.jpg) no-repeat 5% 50%;background-color:#E5E5E5;display:block;min-width:80px;height:22px;text-align:left;margin-left:2px;margin-right:2px;float:left;text-decoration:none;text-indent:23px;cursor:pointer;}
a.button-medium.payment{	height:18px;padding:2px 2px 0px 5px;}
/*------------Touch Button-------------------*/
.touch-button div{
	background:url(/newwaysv2/hongngoc.tcv.vn/packages/hotel/skins/default/images/iosstyle/number_button_bg.png);
}
/*-----------------------------------------------------------*/
input.big{font-size:16px;font-weight:bold;height:40px;}
/*-----------------------------------------------------------*/

.paging-bound{	margin-top:5px;margin-bottom:5px;}
.paging-bound span,
.paging-bound font,
.paging-bound a,
.paging-bound a:visited{	padding:1px 2px 1px 2px;display:inline-block;border:1px solid #FFFFFF;background-color:#D1D1D1;color:#000000;}
.paging-bound span{	margin-right:5px;background-color:#FF9900;color:#FFFFFF;border:1px solid #FFFFFF;}
.paging-bound font{	background-color:#FF9900;text-decoration:underline;color:#FFFFFF;border:1px solid #FFFFFF;}
.paging-bound a,
.paging-bound a:visited,
.paging-bound a:hover,
.paging-bound a:visited:hover{	border:1px solid #FFFFFF;text-decoration:none;}
.paging-bound a:hover,
.paging-bound a:visited:hover{	text-decoration:none;background-color:#FF9900;color:#FFFFFF;}
@page port {size: portrait;}
@page land {size: landscape;}
.global-tab{	float:left;width:100%;}
.global-tab .header{	padding-top:5px;width:100%;float:left;}
.global-tab .header a{	border:1px solid #0482FF;float:left;margin-right:1px;background:#46A3FF;padding:2px 5px 2px 5px;border-bottom:0px;color:#FFFFFF;font-size:12px;}
.global-tab .header a.selected{	border:1px solid #CCCCCC;border-bottom:0px;font-weight:bold;background:#FFFFFF;color:#144686;}
.global-tab .body{	border:1px solid #CCCCCC;width:100%;float:left;padding:5px;display:inline-table;}
.simple-layout-bound{ float:left;width:100%;background:#999999;text-align:center;min-height:500px;}
.simple-layout-middle{	margin:0px auto;width:1150px;}
.simple-layout-content{min-height:600px;float:left;text-align:left;width:100%;padding:0px;border-left:5px solid #CCCCCC;border-right:5px solid #CCCCCC;border-bottom:5px solid #D5E9FD;background-color:#FFFFFF;padding-bottom:30px;}
.row-even{	background:#E8F3FF;border-bottom:1px solid #D9ECFF;}
.row-odd{border-bottom:1px solid #CCE4FF;background:#FFFFFF;}
.date-input{width:66px;border:1px solid #CCCCCC;border-bottom:2px solid #CCCCCC;border-right:2px solid #CCCCCC;}
.note{color:#333333;font-style:italic;font-size:11px;}
.price{text-align:right;}
.hidden{display:none;}
.div-footer{padding:5px;border-top:1px dotted #CCCCCC;margin-top:50px;color:#CCCCCC;}
.div-footer div{color:#999999;text-align:right}
input[id="from_date"]{width:70px;}
input[id="to_date"]{width:70px;}
input[type="submit"]{width:100px;}

a:visited{color:#003399}
@media print
{
    #search{display:none}
}
@media print{
    .print_hide{
        display:none;
    }
}
a.report_table_header
{
	background-color:#FFFFFF;
	font-weight:bold;
	font-size:12px;
	line-height:20px;
	color:#000000;
	text-align:center;
}
.report_table_header
{
	background-color:#FFFFFF;
	font-weight:bold;
	font-size:12px;
	padding:5px;
	color:#000000;
}
.report_table_column
{
	padding:3px;
}
.report_table_column1
{
	padding:0px;
}
a.td_title
{
	color:#000000;
}
.report_title
{
	font-size:20px;
	font-weight:bold;
	text-transform:uppercase;
	text-align:center;
}
.report_sub_title
{
	font-weight:normal;
	padding:5px;
}
.report-bound
{
}
@page port {size: portrait;}
@page land {size: landscape;}
.landscape {page: land;}
#printer
{
	width:99%;
	height:100%;
	text-align:center;
	vertical-align:middle;
	background-color:white;
}
</style>
<table  cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td ><table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
      <tr style="font-size:12px;">
        <td align="left"><strong><img src="<?php echo HOTEL_LOGO;?>" width="100"></strong><br />
        <td >
            <div style="text-align:center;"> <font class="report_title" >WORK SHEET DAILY<br />
            </font> 
            <span style="font-size: 14px;">Ngày <?php echo $this->map['day'];?> tháng <?php echo $this->map['month'];?> năm <?php echo $this->map['year'];?></span>
            </div>
        </td>
        <td ><strong><?php echo Portal::language('template_code');?></strong>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
<div style="width: 100%;text-align: right;">Nhân viên: <input  name="staff_name" id="staff_name" style="width: 150px; font-size: 14px; border: none;" readonly="readonly"/ type ="text" value="<?php echo String::html_normalize(URL::get('staff_name'));?>"></div><!--border:none-->
<!---------REPORT----------->	
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; margin-top: 5px;">
	<tr bgcolor="#EFEFEF">
	    
        <th width="30px"  class="report-table-header">STT</th>
        <th width="50px"  class="report-table-header">IO</th>
        <th width="50px"  class="report-table-header">Status</th>
        <th width="80px"  class="report-table-header">Room</th>
        <th width="80px"  class="report-table-header">Room type</th>
        <th width="80px"   class="report-table-header">Checkin Date</th>
        <th width="80px" class="report-table-header">Checkout Date</th>
        <th width="80px" class="report-table-header">Guest(A/C)</th>
        <th width="150px" class="report-table-header">Guest's Name</th>
        <th width="100px" class="report-table-header">Work in</th>
        <th width="100px" class="report-table-header">Work out</th>
        <th width="100px" class="report-table-header">Remark</th>
        <th class="print_hide"  class="report-table-header">Notice SPECIAL</th>
	</tr>
    
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <tr>
        <td><?php echo $this->map['items']['current']['index'];?></td>
         <td><?php echo $this->map['items']['current']['room_status'];?></td>
        <td><?php echo $this->map['items']['current']['room_guest_status'];?></td>
        <td><?php echo $this->map['items']['current']['name'];?></td>
        <td><?php echo $this->map['items']['current']['brief_name'];?></td>
        <td  ><?php echo $this->map['items']['current']['arrival_time'];?></td>
        <td><?php echo $this->map['items']['current']['departure_time'];?></td>
        <td><?php echo $this->map['items']['current']['adult'];?>/<?php echo $this->map['items']['current']['child'];?></td>
        <td align="left"><?php echo $this->map['items']['current']['guest_name'];?></td>
        <td><?php echo $this->map['items']['current']['work_in'];?></td>
        <td><?php echo $this->map['items']['current']['work_out'];?></td>
        <td><?php echo $this->map['items']['current']['remark'];?></td>
        <td class="print_hide"><?php echo $this->map['items']['current']['reservation_note'];?></td>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
   
</table>
<div id="footer" style="width: 100%;">
<p><span style="float: left;width:30%;">Housekeeping Assign </span><span style="width: 40%;"></span><span style="float: right; width:30%">Housekeeping Supervisor</span></p>
</div>
<p>&nbsp;</p>
<script type="text/javascript">
full_screen();
jQuery(document).ready(
    function()
    {
        jQuery('#from_date').datepicker();
    }
);
</script>
