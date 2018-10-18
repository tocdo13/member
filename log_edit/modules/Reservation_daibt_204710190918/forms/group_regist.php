<?php
class GroupRegistForm extends Form
{
	function GroupRegistForm()
	{
		Form::Form('GroupRegistForm');
        $this->link_js('packages/core/includes/js/common.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    function on_submit()
	{
        $reservation = $_REQUEST['reservation'];
        $bcf_payment = "";
        $need_deposit = 0;
        $cut_of_date = '';
       
        $bol = 0;
        for($i = 1;$i <= 6;$i++)
        {
            if(isset($_REQUEST['bcf_payment_'.$i]))
            {
                if($bol == 0)
                {
                    $bcf_payment = $_REQUEST['bcf_payment_'.$i];
                    $bol = 1;
                }
                else
                    $bcf_payment .= ",".$_REQUEST['bcf_payment_'.$i];
            } 
        }
            
        if(isset($_REQUEST['need_deposit']))
            $need_deposit = $_REQUEST['need_deposit'];
            
        if(isset($_REQUEST['cut_of_date']))
            $cut_of_date = Date_Time::to_orc_date($_REQUEST['cut_of_date']);
        
        
        
        $array_update = array();
        $array_update['BCF_PAYMENT'] = $bcf_payment;
        $array_update['NEED_DEPOSIT'] = System::calculate_number($need_deposit);
        $array_update['CUT_OF_DATE'] = $cut_of_date;
        $cond = "ID = ".$reservation;
        DB::update("RESERVATION",$array_update,$cond);
        
       /*
       require_once 'packages/core/includes/utils/PHPWord.php';
                
                // New Word Document
                $PHPWord = new PHPWord();
                
                // New portrait section
                $section = $PHPWord->createSection();
                
                // Add text elements
                $section->addText('Hello dat!');
                $section->addTextBreak(2);
                
                $section->addText(utf8_encode('KH�C Ng?c �?T'), array('name'=>'Arial', 'color'=>'006699'));
                $section->addTextBreak(2);
                
                $PHPWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>true, 'size'=>16));
                $PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
                $section->addText('I am styled by two style definitions.', 'rStyle', 'pStyle');
                $section->addText('I have only a paragraph style definition.', null, 'pStyle');
                
                
                
                // Save File
                $fileName = 'Text1.docx';
                $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
                $objWriter->save($fileName);
                
                if(file_exists($fileName))
                            {
        			echo '<script>';
        			echo 'window.location.href = \''.$fileName.'\';';
        			echo '</script>';
        		}else{
        			echo '<script>';
        			echo 'alert(" Export d? li?u kh�ng th�nh c�ng !");';
        			echo '</script>';
        		}
       */
    }
	function draw()
	{
		$this->map = array();
		//System::debug($_REQUEST);
        $revertion_id = $_REQUEST['id'];
        
        $infor_comon = DB::fetch("select reservation.CUT_OF_DATE as dealine_deposit,reservation.NEED_DEPOSIT as need_deposit,reservation.NOTE, BCF_STATUS, BCF_PAYMENT,
            customer.NAME as ctm_name, customer.ADDRESS as ctm_add, customer.mobile as ctm_phone, customer.FAX as ctm_fax,
            tour.TOUR_LEADER, tour.name_set,
            PARTY.FULL_NAME user_name,PARTY.PHONE user_phone
            from reservation
            left join customer on customer.id = reservation.customer_id
            left join tour on tour.id = reservation.tour_id
            left JOIN PARTY ON  PARTY.USER_ID = RESERVATION.USER_ID
            where reservation.id = ".$revertion_id);
            
        if(isset($infor_comon['dealine_deposit']))
            $infor_comon['dealine_deposit'] = Date_Time::convert_orc_date_to_date($infor_comon['dealine_deposit'],"/");
         
        $infor_comon['booking_code'] = $revertion_id;
        
        $array_room = DB::fetch_all("select reservation_room.id,
            traveller.FIRST_NAME||' '||traveller.LAST_NAME as name_guest, 
            reservation_room.ARRIVAL_TIME as arrive, 
            reservation_room.DEPARTURE_TIME as departure, 
            reservation_room.price as price, 
            reservation_room.note as note, 
            ROOM_LEVEL.brief_name as room_level,
            reservation_room.foc_all as foc_all,
            reservation_room.foc as foc_room,
            reservation_room.DEPOSIT as deposit,
            room.name as room_name
            from reservation_room
            inner join ROOM_LEVEL on ROOM_LEVEL.id = reservation_room.ROOM_LEVEL_ID
            left join room on reservation_room.room_id = room.id
            left join traveller on  traveller.id = reservation_room.traveller_id
            where reservation_room.reservation_id = ".$revertion_id." 
            and reservation_room.status != 'NOSHOW'  and reservation_room.status != 'CANCEL'
            order by ROOM_LEVEL, reservation_room.ARRIVAL_TIME,reservation_room.DEPARTURE_TIME,reservation_room.price");
            
        
        $array_extrabed = DB::fetch_all("select reservation_room.id,
            traveller.FIRST_NAME||' '||traveller.LAST_NAME as name_guest, 
            reservation_room.EXTRA_BED_FROM_DATE as arrive, 
            reservation_room.EXTRA_BED_TO_DATE as departure, 
            reservation_room.EXTRA_BED_RATE as price, 
            '' as note, 
            'Extra bed' as room_level,
            0 as foc_all,
            0 as foc_room,
            0 as deposit,
            room.name as room_name
            from reservation_room
            left join room on reservation_room.room_id = room.id
            left join traveller on  traveller.id = reservation_room.traveller_id
            where reservation_room.reservation_id = ".$revertion_id." and reservation_room.EXTRA_BED > 0 
            and reservation_room.status != 'NOSHOW' and reservation_room.status != 'CANCEL'
            order by ROOM_LEVEL, reservation_room.ARRIVAL_TIME,reservation_room.DEPARTURE_TIME,reservation_room.price");
            
            
        $reservation_room_type = array();
        
        
        $index1 = 1;
        $temp = array();
        $deposit = 0;
        foreach($array_room as $v)
        {
            $deposit += $v['deposit'];
            $temp[$index1++] = $v;
        }
        
        $infor_comon['deposit'] = $deposit;
        
        $index1 = 1;
        $coun = count($temp);
        for($i = 1; $i <= $coun; $i++)
        {
            if(!isset($temp[$i]))
            {
                continue;
            }
            $reservation_room_type[$index1] = $temp[$i];
            $reservation_room_type[$index1]['total'] = 1;
            $reservation_room_type[$index1]['foc'] = 0;
            if($reservation_room_type[$index1]['foc_all']!=0 or isset($reservation_room_type[$index1]['foc_room']))
            {
                $reservation_room_type[$index1]['foc'] = 1;
            }
            $reservation_room_type[$index1]['index'] = $index1;
            for($j = $i+1; $j <= $coun; $j++)
            {
                if(!isset($temp[$j]))
                {
                    continue;
                }
                
                if( $temp[$j]['room_level'] == $temp[$i]['room_level'] 
                    and $temp[$j]['arrive'] == $temp[$i]['arrive']
                    and $temp[$j]['departure'] == $temp[$i]['departure']
                    and $temp[$j]['price'] == $temp[$i]['price'])
                    {
                        $reservation_room_type[$index1]['total']++;
                        
                        $reservation_room_type[$index1]['id'] .= ",".$temp[$j]['id'];
                        $reservation_room_type[$index1]['room_name'] .= ",".$temp[$j]['room_name'];
                        
                        if($temp[$j]['foc_all']!=0 or isset($temp[$j]['foc_room']))
                        {
                            $reservation_room_type[$index1]['foc'] ++;
                        }
                        
                        if(isset($temp[$j]['name_guest']))
                        {
                            if(isset($reservation_room_type[$index1]['name_guest']))
                                $reservation_room_type[$index1]['name_guest'] .= "<br>".$temp[$j]['name_guest'];
                            else
                                $reservation_room_type[$index1]['name_guest'] .= $temp[$j]['name_guest'];
                        }
                        unset($temp[$j]);
                    }
            }
            $index1++;
        }
        //System::debug($reservation_room_type);
        //extra_bed
        $index1--;
        $index2 = 1;
        $temp = array();
        foreach($array_extrabed as $v)
        {
            $temp[$index2++] = $v;
        }
        
        $index2 = 1;
        $coun = count($temp);
        for($i = 1; $i <= $coun; $i++)
        {
            if(!isset($temp[$i]))
            {
                continue;
            }
            $reservation_room_type[$index2 + $index1] = $temp[$i];
            $reservation_room_type[$index2 + $index1]['total'] = 1;
            $reservation_room_type[$index2 + $index1]['index'] = $index2 + $index1;
            
            
            for($j = $i+1; $j <= $coun; $j++)
            {
                if(!isset($temp[$j]))
                {
                    continue;
                }
                if( $temp[$j]['room_level'] == $temp[$i]['room_level'] 
                    and $temp[$j]['arrive'] == $temp[$i]['arrive']
                    and $temp[$j]['departure'] == $temp[$i]['departure']
                    and $temp[$j]['price'] == $temp[$i]['price'])
                    {
                        $reservation_room_type[$index2 + $index1]['total']++;
                        $reservation_room_type[$index2 + $index1]['id'] .= ",".$temp[$j]['id'];
                        $reservation_room_type[$index2 + $index1]['room_name'] .= ",".$temp[$j]['room_name'];
                        if(isset($temp[$j]['name_guest']))
                        {
                            if(isset($reservation_room_type[$index2 + $index1]['name_guest']))
                                $reservation_room_type[$index2 + $index1]['name_guest'] .= "<br>".$temp[$j]['name_guest'];
                            else
                                $reservation_room_type[$index2 + $index1]['name_guest'] .= $temp[$j]['name_guest'];
                        }
                        unset($temp[$j]);
                    }
            }
            $index2++;
        }
        //System::debug($reservation_room_type);
        $this->map['customer'] = $infor_comon;
        $this->map['reservation_room_type'] = $reservation_room_type;
        //System::debug($this->map);
        //exit();
    
		$this->parse_layout("group_regist",$this->map);
        
	}
}
?>