<?php	 	 
/*
* Personnel system: Erase Memories
* Create by: TCV., JSC
* Date: 11/2011
*/
function break_string( $string ) {
    $arr = explode(' ',$string);
    $arr_result = array();
    $stt = 1;
    $str = '';
    foreach($arr as $k => $v)
    {
        if(strlen($str." ". $v) > 50)
        {
            if(!$str)
            {
                $arr_result[$stt++] = $v;
                $str = '';
            }
            else
            {
                $arr_result[$stt++] = $str;
                $str = $v;
            }
        }
        else
        {
            $str .= $str?' '.$v:$v;
        }
    }
    if($str)
        $arr_result[$stt] = $str;
    return $arr_result;
}

function stripVN($str) 
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ|ỳ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);

    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    return $str;
}

class Printer 
{
    static $handler;
    static $printer_name = '';
    function Printer( $printer_name, $options ) 
    {
        Printer::$printer_name = $printer_name;
        if ( !Printer::$handler = printer_open( Printer::$printer_name ) ) 
        {
            die( 'Error: Could not connect to Printer' );
            return false;
        }
        if ( is_array( $options ) and count( $options ) > 0 ) {
            foreach ( $options as $key => $value ) {
                printer_set_option( Printer::$handler, $key, $value );
            }
        }
    }
    function convert( $string ) 
    {
        return stripVN( trim($string) );
    }
    function write( $content ) {
        printer_write( Printer::$handler, Printer::convert( $content ) );
    }
    function write_r( $items, $group, $table, $department, $bill_title = 'ORDER ' ) {
		//CONFIG OPTION
        //System::debug($table);
        //echo '========';
        //System::debug($bill_title);exit();
		$top = 0;
		$left= 0;
		$right = 550;
		$font_size_title = 40;
		$font_width_title = 16;
		$font_size_nomarl= 35;
		$font_width_nomarl= 12;
		$font_family = "Arial";
		printer_start_doc(Printer::$handler, "My Document");
		printer_start_page(Printer::$handler);
		$font_title = printer_create_font($font_family, $font_size_title, $font_width_title, PRINTER_FW_BOLD, false, false, false, 0);		
		$font_nomarl = printer_create_font($font_family, $font_size_nomarl, $font_width_nomarl, PRINTER_FW_NORMAL, false, false, false, 0);		
		$font_nomarl_bold = printer_create_font($font_family, $font_size_nomarl, $font_width_nomarl, PRINTER_FW_BOLD, false, false, false, 0);		
		$font_sign_nature = printer_create_font($font_family, $font_size_nomarl, $font_width_nomarl, PRINTER_FW_NORMAL, true, false, false, 0);
		$font_nomarl_italic = printer_create_font($font_family, $font_size_nomarl, $font_width_nomarl, PRINTER_FW_NORMAL, true, false, false, 0);		
		
		//PRINT HEADER
		printer_select_font(Printer::$handler, $font_title);		
		printer_draw_text(Printer::$handler,iconv("UTF-8", "CP1252",('Bàn: '.$table)),$left,$top);
		printer_select_font(Printer::$handler, $font_sign_nature);
		$center = (($right-$left-12)/2);
		$top+=(2*$font_width_title);
		printer_draw_text(Printer::$handler,iconv("UTF-8", "CP1252",'Nhân viên:'),$left,$top);	
		printer_draw_text(Printer::$handler,iconv("UTF-8", "CP1252",Session::get('user_id')),($left+130),$top);
		$top += (2*$font_width_title);
		printer_select_font(Printer::$handler, $font_title);		
		printer_draw_text(Printer::$handler,Printer::convert($bill_title.$department),100,$top);				
		printer_select_font(Printer::$handler, $font_nomarl);			
		$top += (3.5*$font_width_nomarl);
		printer_draw_text(Printer::$handler,iconv("UTF-8", "CP1252",'Thời gian '.' :'.date('H:i\' d/m/Y')),$left,$top);
		printer_draw_text(Printer::$handler,'No'.' : '.$group,$left+400,$top);
		//PRINT BILL		
		$pen = printer_create_pen(PRINTER_PEN_SOLID, 1, "000000");
		printer_select_pen(Printer::$handler, $pen);
		$top+=(3.5*$font_width_nomarl);
		printer_draw_line(Printer::$handler, $left,$top,$right,$top);		
		$top+=(0.5*$font_width_nomarl);
		printer_select_font(Printer::$handler, $font_nomarl_bold);		
		printer_draw_text(Printer::$handler,'SL',$left+4,$top);	
		printer_draw_text(Printer::$handler,iconv("UTF-8", "CP1252",'Tên món (Ghi chú)'),$left+55,$top);	
		$top+=(3.5*$font_width_nomarl);
		printer_draw_line(Printer::$handler, $left,$top,$right,$top);
		//$top+=$font_width_nomarl;
		foreach($items as $key=>$value)
		{
			if(isset($value['quantity']))
			{
                $top += (3*$font_width_nomarl);
				$font_nomarl = printer_create_font($font_family, $font_size_nomarl, $font_width_nomarl, PRINTER_FW_NORMAL, false, false, false, 0);		
                printer_draw_text(Printer::$handler,$value['quantity'],($left+6),$top);
                $arr_name = break_string(($value['note'])?$value['name'] .' (' .$value['note'] .')':$value['name']);
                $bol = 0;
                foreach($arr_name as $kn => $vn)
                {
                    if(!$bol)
                        $bol = 1;
                    else
                        $top += (3*$font_width_nomarl);
                    printer_draw_text(Printer::$handler,iconv("UTF-8", "CP1252",$vn),$left+55,$top);
                }
			}
			if(isset($value['remain']))			
			{    
                $top += (3*$font_width_nomarl);
				printer_select_font(Printer::$handler, $font_nomarl);		
				printer_draw_text(Printer::$handler,iconv("UTF-8", "CP1252",'HỦY').$value['remain'],($left+6),$top);
                $arr_name = break_string($value['name']);
                $bol = 0;
                foreach($arr_name as $kn => $vn)
                {
                    if(!$bol)
                        $bol = 1;
                    else
                        $top += (3*$font_width_nomarl);
                    printer_draw_text(Printer::$handler,iconv("UTF-8", "CP1252",$vn),$left+95,$top);
                }			
				printer_draw_line(Printer::$handler, $left,$top+$font_width_nomarl/2,$right,$top+$font_width_nomarl/2);
			}
		}
		//PRINT FOOTER	
		$top+=(3*$font_width_nomarl);
		printer_draw_line(Printer::$handler, $left,$top,$right,$top);
		for($i=2;$i<4;$i++)
		{
			$top+=($i*2*$font_width_nomarl);
			printer_draw_text(Printer::$handler," ",55,$top);
		}		
		//DELETE OPTION ,EXIT
		printer_delete_font($font_nomarl);
		printer_delete_font($font_nomarl_bold);
		printer_delete_font($font_title);
		printer_delete_pen($pen);
		printer_end_page(Printer::$handler);
		printer_end_doc(Printer::$handler);
    }
    function write_bill($items) 
    {
		//CONFIG OPTION

        $top = 0;
		$left= 0;
		$right = 550;
		$font_size_title = 40;
		$font_width_title = 16;
		$font_size_nomarl= 35;
		$font_width_nomarl= 12;
		$font_family = "Arial";
		$font_family_italic = "Times New Roman";
		printer_start_doc(Printer::$handler, "My Document");
		printer_start_page(Printer::$handler);
		$font_title = printer_create_font($font_family, $font_size_title, $font_width_title, PRINTER_FW_BOLD, false, false, false, 0);		
		$font_nomarl = printer_create_font($font_family, $font_size_nomarl, $font_width_nomarl, PRINTER_FW_NORMAL, false, false, false, 0);		
		$font_nomarl_bold = printer_create_font($font_family, $font_size_nomarl, $font_width_nomarl, PRINTER_FW_BOLD, false, false, false, 0);		
		$font_sign_nature = printer_create_font($font_family, $font_size_nomarl, $font_width_nomarl, PRINTER_FW_NORMAL, true, false, false, 0);
		$font_nomarl_italic = printer_create_font($font_family_italic, $font_size_nomarl, $font_width_nomarl, PRINTER_FW_NORMAL, true, false, false, 0);
		//PRINT HEADER
        printer_set_option(Printer::$handler, PRINTER_MODE, "RAW");
        printer_draw_bmp(Printer::$handler, substr(dirname(__FILE__),0,-28).'resources\interfaces\images\default\logo.bmp',($left+140),$top,270,90);
		$top+=90;
        printer_select_font(Printer::$handler,$font_title);
		printer_draw_text(Printer::$handler,Printer::convert('INVOICE/HOA DON'),($left+140),$top);
		printer_select_font(Printer::$handler,$font_sign_nature);
		$top+=(2*$font_width_title);
		printer_draw_text(Printer::$handler,'Ngay '.' :'.date('H:i\' d/m/Y'),($left+150),$top);
        $top+=(2*$font_width_title);
		printer_draw_text(Printer::$handler,'Gio checkout '.' :'.$items['table_checkout'],($left+100),$top);
        $top+=(2*$font_width_title);
		printer_draw_text(Printer::$handler,'Table No '.' :'.$items['tables_name'],($left+190),$top);
        
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 1, "000000");
		printer_select_pen(Printer::$handler, $pen);
		$top+=(3.5*$font_width_nomarl);
		printer_draw_line(Printer::$handler, $left,$top,$right,$top);
        
        $top+=(0.5*$font_width_title);
        if(!empty($items['customer_name']))
		printer_draw_text(Printer::$handler,'Ten Khach '.' :'.Printer::convert($items['customer_name']['full_name'].' / '.' Room: '.Printer::convert($items['room_name'])),($left),$top);
        else
        printer_draw_text(Printer::$handler,'Ten Khach '.' :'.Printer::convert($items['receiver_name']),$left,$top);
        $top+=(2*$font_width_title);
        if(!empty($items['customer_name']))
        printer_draw_text(Printer::$handler,'Cong Ty '.' :'.Printer::convert($items['customer_name']['cname']),($left),$top);
        else
        printer_draw_text(Printer::$handler,'Cong Ty '.' :'.Printer::convert($items['agent_name']),($left),$top);
        $top+=(2*$font_width_title);
        printer_draw_text(Printer::$handler,'Ma '.' :'.Printer::convert($items['order_id']),($left),$top);
        $top+=(2*$font_width_title);
        printer_draw_text(Printer::$handler,'So khach '.' :'.Printer::convert($items['num_people']),($left),$top);
        $top+=(2*$font_width_title);
        printer_draw_text(Printer::$handler,'Thu ngan '.' :'.Printer::convert($items['checkout_user']),($left),$top);
        $top+=(2*$font_width_title);
        printer_draw_text(Printer::$handler,'Nguoi in '.' :'.Printer::convert($items['print_user']),($left),$top);
		//PRINT BILL		
		$top+=(3.5*$font_width_nomarl);
		printer_draw_line(Printer::$handler, $left,$top,$right,$top);		
		$top+=(0.5*$font_width_nomarl);
        printer_select_font(Printer::$handler, $font_nomarl_bold);		
		printer_draw_text(Printer::$handler,'Dien giai/Detail',$left+4,$top);	
        $top+=(3.5*$font_width_nomarl);
        printer_draw_line(Printer::$handler, $left,$top,$right,$top);	
        $top+=(0.5*$font_width_nomarl);
		printer_select_font(Printer::$handler, $font_nomarl_bold);	
		printer_draw_text(Printer::$handler,'SL',$left+4,$top);	
		printer_draw_text(Printer::$handler,'D.G',$left+55,$top);
        printer_draw_text(Printer::$handler,'Giam',$left+255,$top);
        printer_draw_text(Printer::$handler,'tong',$left+455,$top);
        $top+=(3.5*$font_width_nomarl);
        printer_draw_line(Printer::$handler, $left,$top,$right,$top);
		//$top+=$font_width_nomarl;
        $i=1;
		foreach($items['product_items'] as $key=>$value)
		{
			if($value['product__remain_quantity']!=0 AND $value['cancel_all']==0)
			{
				$top += (0.5*$font_width_nomarl);
				printer_select_font(Printer::$handler, $font_nomarl);		
				printer_draw_text(Printer::$handler,$i.' . '.Printer::convert($value['product__name']),($left+6),$top);
                $top+=(3.5*$font_width_nomarl);
                printer_draw_line(Printer::$handler, $left,$top,$right,$top);
                $top+=(0.5*$font_width_nomarl);
				printer_draw_text(Printer::$handler,Printer::convert($value['product__remain_quantity']),6,$top);
                printer_draw_text(Printer::$handler,Printer::convert($value['product__price']),55,$top);
                printer_draw_text(Printer::$handler,Printer::convert($value['product__discount']),255,$top);
                printer_draw_text(Printer::$handler,Printer::convert($value['product__total']),455,$top);
                $top+=(3.5*$font_width_nomarl);
                printer_draw_line(Printer::$handler, $left,$top,$right,$top);
			    $i++;
            }
			if($value['product__quantity_discount']!=0)			
			{
				$top += (0.5*$font_width_nomarl);
				printer_select_font(Printer::$handler, $font_nomarl);		
				printer_draw_text(Printer::$handler,$i.Printer::convert($value['product__name']),($left+6),$top);
                $top+=(3.5*$font_width_nomarl);
                printer_draw_line(Printer::$handler, $left,$top,$right,$top);
                $top+=(0.5*$font_width_nomarl);
				printer_draw_text(Printer::$handler,Printer::convert($value['product__quantity_discount']),6,$top);
                printer_draw_text(Printer::$handler,Printer::convert($value['product__price']),55,$top);
                printer_draw_text(Printer::$handler,' ',255,$top);
                printer_draw_text(Printer::$handler,'0',455,$top);
				$top+=(3.5*$font_width_nomarl);
                printer_draw_line(Printer::$handler, $left,$top,$right,$top);
			    $i++;
            }
		}
        $top+=(1.5*$font_width_nomarl);
        printer_select_font(Printer::$handler, $font_nomarl_bold);
        printer_draw_text(Printer::$handler,'So tien/Amount '.' :',$left+4,$top);
       	printer_draw_text(Printer::$handler,Printer::convert($items['amount']),$left+455,$top);
        if($items['total_discount']!=0.00)
        {
            $top+=(3.5*$font_width_nomarl);
            printer_draw_text(Printer::$handler,'Giam gia san pham/Product Discounted '.' :',$left+4,$top);
       	    printer_draw_text(Printer::$handler,Printer::convert($items['total_discount']),$left+455,$top);
        }
        if($items['order_discount']!=0.00)
        {
            $top+=(3.5*$font_width_nomarl);
            printer_draw_text(Printer::$handler,'('.$items['discount_percent'].' %)'.'Giam gia hoa don/Order Discount '.' :',$left+4,$top);
       	    printer_draw_text(Printer::$handler,Printer::convert(System::display_number($items['order_discount'])),$left+455,$top);
        }
        if($items['bar_fee']!=0.00)
        {
            $top+=(3.5*$font_width_nomarl);
            printer_draw_text(Printer::$handler,'('.$items['bar_fee_rate'].' %)'.'Phi DV/Service charge '.' :',$left+4,$top);
       	    printer_draw_text(Printer::$handler,Printer::convert(($items['bar_fee'])),$left+455,$top);
        }
        if($items['tax']!=0.00)
        {
            $top+=(3.5*$font_width_nomarl);
            printer_draw_text(Printer::$handler,'('.$items['tax_rate'].' %)'.'Thue/Tax '.' :',$left+4,$top);
       	    printer_draw_text(Printer::$handler,Printer::convert(($items['tax'])),$left+455,$top);
        }
        if($items['deposit']!=0.00)
        {
            $top+=(3.5*$font_width_nomarl);
            printer_draw_text(Printer::$handler,'Dat coc/deposit '.' :',$left+4,$top);
       	    printer_draw_text(Printer::$handler,Printer::convert(($items['deposit'])),$left+455,$top);
        }
        if($items['total_payment_traveller']>=1)
        {
            $top+=(3.5*$font_width_nomarl);
            printer_draw_text(Printer::$handler,'total payment traveller '.' :',$left+4,$top);
       	    printer_draw_text(Printer::$handler,Printer::convert(System::display_number($items['total_payment_traveller'])),$left+455,$top);
        }
        
        $top+=(3.5*$font_width_nomarl);
        printer_draw_line(Printer::$handler, $left,$top,$right,$top);
        $top+=(0.5*$font_width_nomarl);
        printer_draw_text(Printer::$handler,'Tong/Grant Total '.' :',$left+4,$top);
   	    printer_draw_text(Printer::$handler,Printer::convert(($items['sum_total'])),$left+455,$top);
        if($items['total_payment_traveller']>=1)
        {
            $top+=(3.5*$font_width_nomarl);
            printer_draw_text(Printer::$handler,'total remain traveller '.' :',$left+4,$top);
       	    printer_draw_text(Printer::$handler,Printer::convert(System::display_number($items['total_payment_traveller']-System::calculate_number($items['sum_total']))),$left+455,$top);
        }
        
        $top+=(3.5*$font_width_nomarl);
        printer_draw_line(Printer::$handler, $left,$top,$right,$top);
        $top+=(0.5*$font_width_nomarl);
        printer_draw_text(Printer::$handler,'Tong USD/Grant Total USD'.' :',$left+4,$top);
   	    printer_draw_text(Printer::$handler,Printer::convert(($items['sum_total_usd'])),$left+455,$top);
        $top+=(3.5*$font_width_nomarl);
        printer_draw_line(Printer::$handler, $left,$top,$right,$top);
        
        if($items['prepaid']!=0.00)
        {
            $top+=(0.5*$font_width_nomarl);
            printer_draw_text(Printer::$handler,'Dat coc/deposit '.' :',$left+4,$top);
       	    printer_draw_text(Printer::$handler,Printer::convert(($items['prepaid'])),$left+455,$top);
            $top+=(3.5*$font_width_nomarl);
            printer_draw_text(Printer::$handler,'Tra lai/Remain '.' :',$left+4,$top);
       	    printer_draw_text(Printer::$handler,Printer::convert(($items['remain_prepaid'])),$left+455,$top);
        }
		//PRINT FOOTER
		$top+=(3*$font_width_nomarl);
		printer_select_font(Printer::$handler, $font_nomarl);
        printer_draw_text(Printer::$handler,'Chu ki',($left+350),$top);
        $top+=(3*$font_width_nomarl);
        printer_draw_text(Printer::$handler,'Thu ngan',($left+50),$top);
        printer_draw_text(Printer::$handler,'So phong',($left+200),$top);
        printer_draw_text(Printer::$handler,'Khach hang',($left+350),$top);
        $top+=(3*$font_width_nomarl);
        printer_draw_text(Printer::$handler,'Cashier',($left+50),$top);
        printer_draw_text(Printer::$handler,'Room No',($left+200),$top);
        printer_draw_text(Printer::$handler,"Customer's",($left+350),$top);
        $top+=(3*$font_width_nomarl);
        printer_draw_text(Printer::$handler,"signature",($left+350),$top);
		for($i=2;$i<5;$i++)
		{
			$top+=($i*2*$font_width_nomarl);
			printer_draw_text(Printer::$handler," . ",0,$top);
		}
		//DELETE OPTION ,EXIT
		printer_delete_font($font_nomarl);
		printer_delete_font($font_nomarl_bold);
		printer_delete_font($font_title);
		printer_delete_pen($pen);
		printer_end_page(Printer::$handler);
		printer_end_doc(Printer::$handler);
    }
    function get_option( $option ) {
        return printer_get_option( Printer::$handler, $option );
    }
    function close() {
        if ( Printer::$handler ) {
            printer_close( Printer::$handler );
        }
    }
/**
 * Hàm tìm vị trí cần cắt chuỗi
 * $max_length = số kí tự cần cắt - 1 vì max_length dem tu 0 con so ki tu thi dem tu 1
 */
    function cut_position($str,$max_length,$charset='UTF-8')
    {
        $len = mb_strlen($str,$charset);
        $str_1 = mb_substr($str,0,$max_length,$charset);
        $str_2 = mb_substr($str,$max_length,$len,$charset);
       
        if(isset($str_2[0]) && $str_2[0] == " ")
            return $max_length;
        else
            return mb_strrpos($str_1," ",0,$charset);
    }
    
    function write_ticket($paper_width, $paper_height, $ticket_name, $ticket_group_name, $ticket_prefix, $ticket_price, $ticket_code,  $service , $font_face = 'Times New Roman' )
    {
        $description = Printer::convert($description);
        
        //Tạo font
        $font_height_detail = 58;
        $font_width_detail = 25;
        $font_detail = printer_create_font($font_face,$font_height_detail, $font_width_detail,PRINTER_FW_NORMAL,false,false,false,0);
        
        $font_height_group_name = 70;
        $font_width_group_name = 35;
        $font_group_name = printer_create_font($font_face,$font_height_group_name, $font_width_group_name,PRINTER_FW_NORMAL,false,false,false,0);
        
        $font_height_price = 65;
        $font_width_price = 30;
        $font_price = printer_create_font($font_face,$font_height_price, $font_width_price,PRINTER_FW_BOLD,false,false,false,0);
        
        
        $font_description = printer_create_font($font_face,$font_height, $font_width,PRINTER_FW_NORMAL,true,true,false,0);
        
        //Tạo trang in
        printer_start_doc( Printer::$handler, "Invoice" );
        printer_start_page( Printer::$handler );
        
        //chọn font
        printer_select_font(Printer::$handler,$font_group_name);
        
        $top = 10;
        //Cach lề phải 5% + độ rộng của tên nhóm
        $left = 95/100 * $paper_width - strlen($ticket_group_name)*$font_width_group_name;
        // left = 0 vi mặc định đã cách lề sẵn rồi
        //$left = 0;
        //In tên khách sạn
        printer_draw_text(Printer::$handler,Printer::convert($ticket_group_name),$left,$top);
        
        /*
        //In ngày tháng
        printer_select_font(Printer::$handler,$font);
        printer_draw_text(Printer::$handler,'Date : '.date('d/m/Y - H\hi '),$left,$top+$font_height_big);
        //In mã vé
        printer_select_font(Printer::$handler,$font_big);
            //tính độ rộng của mã vé (+4 chữ No : )
        $code_width = (strlen($ticket_code) + 4) * $font_width_big;
        //Cách lề phải 10%
        $left = $paper_width - (10/100 * $paper_width) -  $code_width ;
        printer_draw_text(Printer::$handler,'No : '.$ticket_code,$left,$top);
        //In user thực hiện
        printer_select_font(Printer::$handler,$font);
        printer_draw_text(Printer::$handler,Session::get('user_id'),$left,$top+$font_height_big);
        //In tên vé
            //tính độ rộng tên vé
        printer_select_font(Printer::$handler,$font_big);
        //Nhân 1.5 vì tên vé đc viết hoa
        $ticket_name_width = strlen( Printer::convert( $ticket_name ) ) * $font_width_big * 1.5;
            //Căn giữa
        $left = ($paper_width - $ticket_name_width)/2;
        $top += 3*$font_height;
        printer_draw_text(Printer::$handler, strtoupper( Printer::convert($ticket_name) ) ,$left,$top);
        //In giá vé
            //tính độ rộng của giá vé (+4 chữ  VND )
        $ticket_price_width = (strlen($ticket_price) + 4) * $font_width_big;
            //Căn giữa
        $left =  ($paper_width - $ticket_price_width)/2;
        $top += $font_height_big;
        printer_draw_text(Printer::$handler,$ticket_price.' VND',$left,$top);
        //In ghi chú
        printer_select_font(Printer::$handler,$font_description);
        $top += 1.5 * $font_height_big;
        $description_width = strlen($description) * $font_width;
        $left =  ($paper_width - $description_width)/2;
        printer_draw_text(Printer::$handler,$description,$left,$top);
        
        //In time_print : chiếm 17 ô (dạng Time print : 23h09)
/**
 *         $top += 2*$font_height;
 *         $time_width = 17 * $font_width;
 *             //Cách lề phải 5%
 *         $left = $paper_width - (5/100 * $paper_width) -  $time_width ;
 *         printer_draw_text(Printer::$handler,'Time print : '.date('H\hi'),$left,$top);
 */
        
        //printer_draw_line( Printer::$handler, $paper_width , 0, $paper_width , $top+2*$font_height );
        
        //printer_delete_font( $font );
        //printer_delete_font( $font_description );
        //printer_delete_font( $font_big );
        
        $pen = printer_create_pen( PRINTER_PEN_DOT, 1, "000000" ); 
        printer_select_pen(Printer::$handler,$pen);
        /*
        //cach le phai 5%
        $right = $paper_width;
        printer_draw_line( Printer::$handler, 0, 0, $right, 0 );
        printer_draw_line( Printer::$handler, 0, 0, 0, $top +  $font_height_big );
        printer_draw_line( Printer::$handler, 0, $top +  $font_height_big,$right, $top +  $font_height_big );
        printer_draw_line( Printer::$handler, $right , 0, $right, $top +  $font_height_big );
        */
        //sprinter_draw_line( Printer::$handler, 0, -10, $right, -10 );
        
        printer_delete_pen( $pen );
        
        printer_end_page( Printer::$handler );
        printer_end_doc( Printer::$handler );
    }

    function draw_grid($left=0, $top =0)
    {
        $pen = printer_create_pen( PRINTER_PEN_SOLID, 1, "000000" ); 
        printer_select_pen(Printer::$handler,$pen);
        
        printer_start_doc( Printer::$handler, "Grid" );
        printer_start_page( Printer::$handler );
        $pos_x = 0;
        for($i = 1; $i <= 46; $i++)
        {
            printer_draw_line( Printer::$handler, $left + $pos_x, $top, $left + $pos_x, $top + 5500 );
            //printer_draw_text(Printer::$handler,'\'',$left + $pos_x, $top);
            $pos_x+=100;
        }
        
        $pos_x_name = 0;
        for($i = 1; $i <= 46; $i++)
        {
            printer_draw_text(Printer::$handler,$pos_x_name,$left + $pos_x_name, $top);
            $pos_x_name+=500;
        }
        
        $pos_y = 0;
        for($i = 1; $i <= 56; $i++)
        {
            printer_draw_line( Printer::$handler, $left, $top + $pos_y, $left+4500, $top+$pos_y );
            //printer_draw_text(Printer::$handler,'-',$left, $pos_y + $pos);
            $pos_y+=100;
        }
        
        $pos_y_name = 0;
        for($i = 1; $i <= 56; $i++)
        {
            printer_draw_text(Printer::$handler,$pos_y_name,$left, $top + $pos_y_name);
            $pos_y_name+=500;
        }
        printer_delete_pen( $pen );
        printer_end_page( Printer::$handler );
        printer_end_doc( Printer::$handler );
    }
    
    function write_invoice_vat($header, $content, $left = 0, $top = 0, $font_face = 'Times New Roman' )
    {
        $font_height = 14;
        $font_width = 8;
        $font = printer_create_font($font_face,$font_height, $font_width,PRINTER_FW_NORMAL,false,false,false,0);
        
        printer_start_doc( Printer::$handler, "Invoice" );
        printer_start_page( Printer::$handler );
        
        //in ngày tháng
        printer_select_font(Printer::$handler,$font);
        printer_draw_text(Printer::$handler,date('d'),392.57+ $left, 65.16+ $top);
        printer_draw_text(Printer::$handler,date('m'),475.34+ $left, 65.16+ $top);
        printer_draw_text(Printer::$handler,date('Y'),553.38+ $left, 65.16+ $top);
        
        //in thông tin khách hàng
        //printer_draw_text(Printer::$handler,Printer::convert($header['representative']),2050+ $left, 1550+ $top);
        printer_draw_text(Printer::$handler,Printer::convert($header['customer_name']),283.78+ $left, 192.67+ $top);
        
        if(mb_strlen($header['address'],'UTF-8') > 93)
        {
            $cut = Printer::cut_position($header['address'], 92);
            printer_draw_text(Printer::$handler,Printer::convert(mb_substr($header['address'],0, $cut,'UTF-8')),222.30+ $left, 205.25+ $top);
            printer_draw_text(Printer::$handler,Printer::convert(mb_substr($header['address'],$cut, mb_strlen($header['address'],'UTF-8'),'UTF-8')),222.30+ $left, 219.25+ $top); 
        }
        else
            printer_draw_text(Printer::$handler,Printer::convert($header['address']),222.30+ $left, 211.09+ $top);
            
        
        //printer_draw_text(Printer::$handler,'USD',2000+ $left, 2000+ $top);
        printer_draw_text(Printer::$handler,Printer::convert($header['tax_code']),681.08+ $left, 229.51+ $top);
        
        
        //in t?ng ti?n
        printer_draw_text(Printer::$handler,System::display_number($header['service_charge']),775.67 + (15-strlen(System::display_number($header['service_charge'])))*$font_width + $left, 521.33+ $top);
        printer_draw_text(Printer::$handler,'10',283.78+ $left, 544+ $top);
        printer_draw_text(Printer::$handler,System::display_number($header['vat']),775.67 + (15-strlen(System::display_number($header['vat'])))*$font_width + $left, 544+ $top);
        printer_draw_text(Printer::$handler,System::display_number($header['total_payment']),775.67 + (15-strlen(System::display_number($header['total_payment'])))*$font_width + $left, 566.67+ $top);
        
        $str = Printer::convert($header['total_in_words']);
        if(mb_strlen($str,'UTF-8') > 70)
        {
            $cut = Printer::cut_position($str, 69);
            printer_draw_text(Printer::$handler,mb_substr($str,0, $cut,'UTF-8'),364.19+ $left, 590.75+ $top);
            printer_draw_text(Printer::$handler,mb_substr($str,$cut,mb_strlen($str,'UTF-8'),'UTF-8'),78.04+ $left, 612+ $top); 
        }
        else
            printer_draw_text(Printer::$handler,$str,364.19+ $left, 590.75+ $top);
        
        //In n?i dung
        $top += 330.08;
        $stt = 1;
        foreach ($content as $key=>$items)
        {
            foreach($items as $k=>$v)
            {
                printer_draw_text(Printer::$handler,$stt,73.31+ $left, $top);
                //printer_draw_text(Printer::$handler,$value['3'],2400, $top);
                //printer_draw_text(Printer::$handler,$v['billing_period'],2800+ $left, $top);
                //printer_draw_text(Printer::$handler,System::display_number($v['charge_month']),3000 + (15-strlen(System::display_number($v['charge_month'])))*$font_width + $left, $top);
                printer_draw_text(Printer::$handler,System::display_number($v['total_before_discount']),775.67 + (15-strlen(System::display_number($v['total_before_discount'])))*$font_width + $left, $top);
                //48
                
                while(mb_strlen($v['name'],'UTF-8') > 44)
                {
                    $cut = Printer::cut_position($v['name'],43);
                    printer_draw_text(Printer::$handler,Printer::convert(mb_substr($v['name'],0,$cut,'UTF-8')),127.70+ $left, $top);
                    $top += $font_height;
                    $v['name'] = mb_substr($v['name'],$cut,mb_strlen($v['name'],'UTF-8'),'UTF8');
                }
                printer_draw_text(Printer::$handler,Printer::convert($v['name']),127.70+ $left, $top); 
                
                $top += 1.5 * $font_height;
                
                if(isset($v['description_discount_money']))
                {
                    printer_draw_text(Printer::$handler,Printer::convert($v['description_discount_money']),127.70+ $left, $top);
                    printer_draw_text(Printer::$handler,System::display_number($v['total_discount_money']),775.67 + (15-strlen(System::display_number($v['total_discount_money'])))*$font_width + $left, $top);
                    $top += 1.5 * $font_height;
                }
                
                if(isset($v['description_initial_charge']))
                {
                    printer_draw_text(Printer::$handler,Printer::convert($v['description_initial_charge']),127.70+ $left, $top);
                    printer_draw_text(Printer::$handler,System::display_number($v['initial_charge']),775.67 + (15-strlen(System::display_number($v['initial_charge'])))*$font_width + $left, $top);
                    $top += 1.5 * $font_height;
                }
            }
            $stt++;
        }
        printer_delete_font( $font );
        printer_end_page( Printer::$handler );
        printer_end_doc( Printer::$handler );
   
    }
}

?>
