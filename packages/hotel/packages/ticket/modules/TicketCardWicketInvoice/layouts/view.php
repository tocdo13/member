<style type="text/css">
	 @media print{
	   #printer_logo{
	       display: none;
	   }
       th{
         font-weight:normal;
       }
  }
  *{font-family: sans-serif, Arial, Tahoma;}	
</style>
<!---------------------------------- LAYOUT KHO GIAY K8 ---------------------------------------------->
<!--Start - them button in.khi in goi ajax cap nhat truong intamtinh-->
<div id="printer_logo">
<!--<a onclick="var content = jQuery('#printer').html();printWebPart_Tan(content);printed_tmp_bill();" title="Print" style="float: right;"><img src="packages/core/skins/default/images/printer.png" height="40"></a>-->
<!--<a href="<?php //echo '?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1&id='.Url::get('id').'&bar_id='.Url::get('bar_id').'&table_id='.Url::get('table_id').'&bar_area_id='.Url::get('bar_area_id').'&print_automatic_bill=1&portal='.Url::get('portal'); ?>" class="button-medium" style="float: right;">[[.print_automatic_bill.]]</a>-->
</div>
<!--End - them button in.khi in goi ajax cap nhat truong intamtinh-->
<div id="printer" style="width:300px; padding:0px; text-align:left;">
    <div class="restaurant-invoice-bound" style="width: 100%;">
        <table style="border-bottom: 1px solid #dddddd;" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style="text-align: center;"><div style="width: 100%; height: 115px; overflow: hidden; text-align: center;"><img src="[[|logo_src|]]" style="width: 150px; height: auto;" /></div></td>
            </tr>
            <tr>
                <td style="font-size: 14px;  text-align: left;">Địa chỉ/Add: <?php echo HOTEL_ADDRESS; ?></td>
            </tr>
            <tr>
                <td style="font-size: 14px;  text-align: left;">Điện thoại/Tel: <?php echo HOTEL_PHONE; ?></td>
            </tr>
            <tr>
                <td style="font-size: 18px;  text-align: center; line-height: 25px; font-weight:bold;"><br/>HÓA ĐƠN BÁN VÉ</td>
            </tr>
            <tr>
                <td style="font-size: 14px;  text-align: left;"><br/>Hóa đơn/Bill No : [[|bill_no|]]</td>
            </tr>
            <tr>
                <td style="font-size: 14px;  text-align: left;">Ngày in/Print date :<?php echo date('d/m/Y H:i\'');?></td>
            </tr>
            <tr>
                <td style="font-size: 14px;  text-align: left;">Thu ngân/Cashier: [[|user_name|]]</td>
            </tr>
            <tr>
                <td style="font-size: 14px;  text-align: left;">Khách hàng/Customer : <span style="font-size:18px;"><?php echo $this->map['ticket_card_wicket']['customer_name']; ?></span></td>
                
            </tr>
        </table>
        <table width="100%" cellpadding="2" cellspacing="0" border="0" bordercolor="#000000" style="">
            <tr>
          <td colspan="4">
              <table width="100%" cellpadding="2" cellspacing="0" style="border-bottom:1px solid #000000; ">
              <tr valign="top">
                <th colspan="5" width="100%" align="left" style="border-bottom:1px solid #B8B9BF;font-size: 14px;">Tên/Name </th> 
              </tr>
              <tr>
                    <th width="20%" align="center" style="border-bottom:none !important; font-size: 14px;">S.L/Unit</th>
                    <th width="25%" align="left"  style="font-size: 14px;">Giá/Price</th>
                    <!--<th width="13%" align="left"  style="font-size: 14px;">GG%</th> -->
                    <th width="25%" align="right"  style="font-size: 14px;">KM%</th>
                    <th width="30%" align="right"  style="font-size: 14px;">Tổng</th>
                </tr>
              </table>
          </td>
          </tr>
            <?php $i=1;?>
          <!--LIST:ticket_card_wicket_detail-->  
          <tr>
              <td colspan="4">
                  <table width="100%" cellpadding="2" cellspacing="0" style="border-bottom:1px dashed #000000;">
                  <tr valign="top">
                    <td width="100%" align="left" colspan="4" style="border-bottom:1px solid #B8B9BF; font-size: 14px;text-transform: capitalize;"><?php echo $i;?>. [[|ticket_card_wicket_detail.name|]]</td> 
                  </tr>
                  <tr>
                    <td width="20%" align="center" style=" font-size: 14px;">[[|ticket_card_wicket_detail.quantity|]]</td>
                    <td width="25%" align="left" style=" font-size: 14px;"><?php echo System::display_number([[=ticket_card_wicket_detail.price=]]); ?></td>
                    <!--<td width="13%" align="right;" style=" font-size: 14px;">0</td>--> 
                    <td width="25%" align="right" style=" font-size: 14px;"><?php echo System::display_number([[=ticket_card_wicket_detail.discount_percent=]]); ?>%</td>
                    <td width="30%" align="right" style="font-size: 14px;"><?php echo System::display_number([[=ticket_card_wicket_detail.total=]]); ?></td>
                  </tr>
                  </table>
              </td>
          </tr>
          <?php $i++;?>
          <!--/LIST:ticket_card_wicket_detail--> 
          <tr>
            <td colspan="3" style="font-size: 14px;">TÔNG THANH TOÁN : </td>
            <td align="right" style="font-size: 14px;"><?php echo System::display_number($this->map['ticket_card_wicket']['total']); ?></td>
          </tr>
          <!--LIST:payment_info-->
              <tr>
                <td colspan="3" style="font-size: 14px; font-style: italic; padding-left: 20px;"> + [[|payment_info.payment_name|]] : </td>
                <td align="right" style="font-size: 14px;">[[|payment_info.amount|]]</td>
              </tr>
          <!--/LIST:payment_info-->
          <tr>
            <td colspan="4" style="font-size: 14px;">Bằng chữ : <span id="letter_total"></span></td>
          </tr>
          
          <tr>
            <td colspan="3" style="font-size: 14px;">Số tiền khách đưa : </td>
            <td align="right" style="font-size: 14px;"><?php echo System::display_number($this->map['ticket_card_wicket']['customer_money']); ?></td>
          </tr>
          <tr>
            <td colspan="3" style="font-size: 14px;">Số tiền trả lại : </td>
            <td align="right" style="font-size: 14px;"><?php echo $this->map['ticket_card_wicket']['customer_money']!=0?System::display_number($this->map['ticket_card_wicket']['customer_money']-$this->map['ticket_card_wicket']['total']):0; ?></td>
          </tr>
          <tr>
            <td colspan="4"></td>
          </tr>
          <tr>
            <td colspan="4" align="center">Cảm ơn quý khách đã sử dụng dịch vụ của Baaraland</br></br></br></br></br></td>
          </tr>
          <tr>
            <td colspan="4" align="center" style="font-size:11px;"><i>Vé được in từ phần mềm Newway; http://quanlyresort.com</i></td>
          </tr>
        </table>
    </div>
</div>
<script>
    var total = <?php echo $this->map['ticket_card_wicket']['total']; ?>;
    var ChuSo=new Array(" không "," một "," hai "," ba "," bốn "," năm "," sáu "," bảy "," tám "," chín ");
    var Tien=new Array( "", " nghìn", " triệu", " tỷ", " nghìn tỷ", " triệu tỷ");
    function DocSo3ChuSo(baso)
    {
        var tram;
        var chuc;
        var donvi;
        var KetQua="";
        tram=parseInt(baso/100);
        chuc=parseInt((baso%100)/10);
        donvi=baso%10;
        if(tram==0 && chuc==0 && donvi==0) return "";
        if(tram!=0)
        {
            KetQua += ChuSo[tram] + " trăm ";
            if ((chuc == 0) && (donvi != 0)) KetQua += " linh ";
        }
        if ((chuc != 0) && (chuc != 1))
        {
                KetQua += ChuSo[chuc] + " mươi";
                if ((chuc == 0) && (donvi != 0)) KetQua = KetQua + " linh ";
        }
        if (chuc == 1) KetQua += " mười ";
        switch (donvi)
        {
            case 1:
                if ((chuc != 0) && (chuc != 1))
                {
                    KetQua += " mốt ";
                }
                else
                {
                    KetQua += ChuSo[donvi];
                }
                break;
            case 5:
                if (chuc == 0)
                {
                    KetQua += ChuSo[donvi];
                }
                else
                {
                    KetQua += " lăm ";
                }
                break;
            default:
                if (donvi != 0)
                {
                    KetQua += ChuSo[donvi];
                }
                break;
            }
        return KetQua;
    }
    function DocTienBangChu(SoTien)
    {
        var lan=0;
        var i=0;
        var so=0;
        var KetQua="";
        var tmp="";
        var ViTri = new Array();
        if(SoTien<0) return "Số tiền âm !";
        if(SoTien==0) return "Không đồng !";
        if(SoTien>0)
        {
            so=SoTien;
        }
        else
        {
            so = -SoTien;
        }
        if (SoTien > 8999999999999999)
        {
            return "Số quá lớn!";
        }
        ViTri[5] = Math.floor(so / 1000000000000000);
        if(isNaN(ViTri[5]))
            ViTri[5] = "0";
        so = so - parseFloat(ViTri[5].toString()) * 1000000000000000;
        ViTri[4] = Math.floor(so / 1000000000000);
         if(isNaN(ViTri[4]))
            ViTri[4] = "0";
        so = so - parseFloat(ViTri[4].toString()) * 1000000000000;
        ViTri[3] = Math.floor(so / 1000000000);
         if(isNaN(ViTri[3]))
            ViTri[3] = "0";
        so = so - parseFloat(ViTri[3].toString()) * 1000000000;
        ViTri[2] = parseInt(so / 1000000);
         if(isNaN(ViTri[2]))
            ViTri[2] = "0";
        ViTri[1] = parseInt((so % 1000000) / 1000);
         if(isNaN(ViTri[1]))
            ViTri[1] = "0";
        ViTri[0] = parseInt(so % 1000);
        if(isNaN(ViTri[0]))
            ViTri[0] = "0";
        if (ViTri[5] > 0)
        {
            lan = 5;
        }
        else if (ViTri[4] > 0)
        {
            lan = 4;
        }
        else if (ViTri[3] > 0)
        {
            lan = 3;
        }
        else if (ViTri[2] > 0)
        {
            lan = 2;
        }
        else if (ViTri[1] > 0)
        {
            lan = 1;
        }
        else
        {
            lan = 0;
        }
        for (i = lan; i >= 0; i--)
        {
           tmp = DocSo3ChuSo(ViTri[i]);
           KetQua += tmp;
           if (ViTri[i] > 0) KetQua += Tien[i];
           if ((i > 0) && (tmp.length > 0)) KetQua += ' ';
        }
       if (KetQua.substring(KetQua.length - 1) == ' ')
       {
            KetQua = KetQua.substring(0, KetQua.length - 1);
       }
       KetQua = KetQua.substring(1,2).toUpperCase()+ KetQua.substring(2)+' đồng';
       return KetQua;
    }
    jQuery("#letter_total").html(DocTienBangChu(total));
    /** START - an button in cua menu**/
    var txt=document.getElementById("chang_language").innerHTML;
    txt = txt.replace('<a onclick="printWebPart(\'printer\');" title="Print"><img src="packages/core/skins/default/images/printer.png" height="40"></a> |', "");
    txt = txt.replace('<a onclick="printWebPart(\'printer\');" title="In"><img src="packages/core/skins/default/images/printer.png" height="40"></a> |', "");
    document.getElementById("chang_language").innerHTML = txt;
    /** END - an button in cua menu**/
    /** ham cap nhat trang thai da in cua hoa don tam tinh**/
    var bar_reservation_id = '<?php //echo ($_REQUEST['id']);  ?>';
    function printed_tmp_bill(){
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","packages/hotel/packages/restaurant/modules/TouchBarRestaurant/ajax.php?cmd=printed_tmp_bill&bar_reservation_id="+bar_reservation_id,true);        
        opener.location.reload();
        xmlhttp.send();
    }
    
	var printt = '<?php //echo (Url::get(md5('preview'))?Url::get(md5('preview')):'');?>';
    if(printt != ''){
		jQuery("#note").css('display','none');	
	}

    function printWebPart_Tan(content){
    	if(content)
    	{
    	   //alert(jQuery("button.print").html());
    		var html = "";
    		html = "<html><head>"+
    		"<link rel=\"stylesheet\" type=\"text/css\" href=\"packages/core/skins/default/css/global.css\" media=\"print\" ></link>"+
            "<style type=\"text/css\">"+
            "*{ font-family: sans-serif, Arial, Tahoma; }"+
            "</style>"+
    		"</head><body >"+
    		content+
    		"</body></html>";
    		width = jQuery(document).width();
    		height = jQuery(document).height();
    		html = html.replace('packages/core/includes/js/common.js','');
    		var printWP = window.open("","_blank");
    		printWP.document.open();
    		printWP.document.write(html);
            //printWP.document.body.style.zoom= "40%";
    		printWP.print();
            //printWP.document.getElementsByClassName("print").trigger("click");
    		printWP.document.close();
    		printWP.close();
    	}
    }
	var printt = '<?php //echo (Url::get(md5('preview'))?Url::get(md5('preview')):'');?>';
    if(printt ==''){
        //console.log('aaa');
		//printWebPart('printer');
        //console.log('aaa');
        //var content = jQuery("#printer").html();	
	    //window.close();
        //printWebPart_Tan(content);
    }
	function handleKeyPress(evt) {  
		var nbr;  
		var nbr = (window.event)?event.keyCode:evt.which;
		if(nbr==14 || nbr==80){
			//if(!confirm('[[.Are_you_sure_to_add_reservation.]]?')){
			return false;
			//}
		}
		return true;
	}
	document.onkeydown= handleKeyPress;
	jQuery('body').disableTextSelect();
	jQuery(document).ready(function(){ 
		   jQuery(document).bind("contextmenu",function(e){
				  return false;
		   }); 
	});
	var itemBodySize = jQuery(".item-body").size();
	var subItemBodySize = jQuery(".sub-item-body").size();
	var maxLine = 16;
	var i = 1;
	var j = 0;
	var page = 1;
	/*if((itemBodySize + subItemBodySize) < maxLine){
		jQuery(".item-body").each(function(){
			if(j == itemBodySize -2 ){
				for(var c = 0;c <= (maxLine - itemBodySize);c++){
					jQuery(this).after('<div class="item-body"><div class="date">&nbsp;</div><div class="description">&nbsp;</div><div class="amount">&nbsp;</div></div>');
				}
			}
			j++;
		});
	}else */
	{
		jQuery(".item-body").each(function(){
			if(i<(itemBodySize + subItemBodySize)){
				var mode = maxLine;
				if(jQuery(this).attr('class') == 'item-body total-group'){
					if(i + subItemBodySize < maxLine){
						for(var c = 0;c <= (maxLine - (i + subItemBodySize));c++){
							jQuery(this).before('<div class="item-body"><div class="date">&nbsp;</div><div class="description">&nbsp;</div><div class="amount">&nbsp;</div></div>');
						}		
					}
					mode = maxLine - subItemBodySize;
				}
				if(i%(mode) == 0){
					jQuery(this).after('<div style="page-break-after:always;text-align:center;color:#666666;">-[[.page.]] '+page+'-</div><div style="float:left;width:100%;height:100px;">&nbsp;</div>');
					page++;
					i = 0;
				}
			}
			i++;
		});
	}
	//printWebPart('printer');
	//window.close();
</script>
