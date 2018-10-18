<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    function get_folios()
    {
        $data = DB::fetch_all("
                                Select
                                    folio.id,
                                    folio.id as folio_id,
                                    reservation_room.id as reservation_room_id,
                                    reservation.id as reservation_id,
                                    customer.name as customer_name,
                                    traveller.first_name || ' ' || traveller.last_name as guest_name,
                                    room.name as room_name,
                                    folio.total,
                                    row_number() OVER (ORDER BY folio.id desc) AS rownumber,
                                    to_char(FROM_UNIXTIME(folio.create_time),'DD/MM/YYYY') as create_time,
                                    folio.customer_id,
                                    folio.reservation_traveller_id,
                                    traveller.id as traveller_id
                                From
                                    folio
                                    inner join reservation on folio.reservation_id = reservation.id
                                    left join customer on reservation.customer_id = customer.id
                                    left join reservation_traveller on folio.reservation_traveller_id = reservation_traveller.id
                                    left join traveller on reservation_traveller.traveller_id = traveller.id
                                    left join reservation_room on folio.reservation_room_id = reservation_room.id
                                    left join room on reservation_room.room_id = room.id 
                                Where
                                    folio.printer_vat=0
                                ORDER BY
                                    folio.id desc
                            ");
        return $data;
    }
    function get_traveller_folios()
    {
        $cond = '';
		if(strpos(Url::get('folio_ids'),','))
        {
            $folio_ids = explode(",",Url::get('folio_ids'));
			for($i=0;$i<count($folio_ids);$i++)
            {
				if($folio_ids[$i] && $folio_ids[$i]!='')
                {
					$cond .= ($i==0)?(' folio.id ='.$folio_ids[$i]):(' OR folio.id ='.$folio_ids[$i]);  	
				}
			}
		}
        else
        {
            $cond = ' folio.id = '.Url::get('folio_ids').'';
        }
        $traveller_folios = DB::fetch_all("
                                            SELECT
                                                traveller_folio.*
                                            FROM
                                                traveller_folio
                                                inner join folio on folio.id=traveller_folio.folio_id
                                            WHERE
                                                ".$cond."
                                            ORDER BY
                                                traveller_folio.folio_id DESC, traveller_folio.id DESC
                                        ");
        $list_vat = array();
        $data = array();
        $data['total_before_tax'] = 0;
        $data['service_rate'] = 0;
        $data['tax_rate'] = 0;
        $data['total_amount'] = 0;
        $stt = 0;
        foreach($traveller_folios as $key2=>$value2)
        {
            $stt++;
            $total_before_tax = $value2['amount'];
            $list_vat[$key2]['id'] = $value2['id'];
            $list_vat[$key2]['traveller_folio_id'] = $value2['id'];
            $list_vat[$key2]['total_before_tax'] = $total_before_tax;
            $list_vat[$key2]['total_service_rate'] = $total_before_tax*$value2['service_rate']/100;
            $list_vat[$key2]['total_tax_rate'] = ($list_vat[$key2]['total_service_rate']+$total_before_tax)*$value2['tax_rate']/100;
            $list_vat[$key2]['total_amount'] = $total_before_tax + $list_vat[$key2]['total_service_rate'] + $list_vat[$key2]['total_tax_rate'];
            $list_vat[$key2]['description'] = $value2['description'];
            $list_vat[$key2]['service_rate'] = $value2['service_rate'];
            $list_vat[$key2]['tax_rate'] = $value2['tax_rate'];
            if($value2['foc_all']!=0)// mi?n phí toàn b?
            {
                $list_vat[$key2]['description'] .= "(FOC_ALL)";
                if($value2['type']=='DEPOSIT')
                {
                    $data['total_before_tax'] -= $total_before_tax;
                    $data['service_rate'] -= $list_vat[$key2]['total_service_rate'];
                    $data['tax_rate'] -= $list_vat[$key2]['tax_rate'];
                    $data['total_amount'] -= $list_vat[$key2]['total_amount'];
                }
            }
            else// không mi?n phí toàn b?
            {
                if($value2['foc']!='')// mi?n phí ti?n phòng
                {
                    if($value2['type']!='ROOM')
                    {
                        if($value2['type']=='DEPOSIT')
                        {
                            $data['total_before_tax'] -= $total_before_tax;
                            $data['service_rate'] -= $list_vat[$key2]['total_service_rate'];
                            $data['tax_rate'] -= $list_vat[$key2]['total_tax_rate'];
                            $data['total_amount'] -= $list_vat[$key2]['total_amount'];
                        }
                        else
                        {
                            if($value2['type']!='DISCOUNT')
                            {
                                $data['total_before_tax'] += $total_before_tax;
                                $data['service_rate'] += $list_vat[$key2]['total_service_rate'];
                                $data['tax_rate'] += $list_vat[$key2]['total_tax_rate'];
                                $data['total_amount'] += $list_vat[$key2]['total_amount'];
                            }
                        }
                    }
                }
                else// không mi?n phí
                {
                    if($value2['type']=='DEPOSIT' OR $value2['type']=='DISCOUNT')
                    {
                        $data['total_before_tax'] -= $total_before_tax;
                        $data['service_rate'] -= $list_vat[$key2]['total_service_rate'];
                        $data['tax_rate'] -= $list_vat[$key2]['total_tax_rate'];
                        $data['total_amount'] -= $list_vat[$key2]['total_amount'];
                    }
                    else
                    {
                        $data['total_before_tax'] += $total_before_tax;
                        $data['service_rate'] += $list_vat[$key2]['total_service_rate'];
                        $data['tax_rate'] += $list_vat[$key2]['total_tax_rate'];
                        $data['total_amount'] += $list_vat[$key2]['total_amount'];
                    }
                }
            }
            $list_vat[$key2]['in_date'] = Date_Time::convert_orc_date_to_date($value2['date_use'],"/");
            $list_vat[$key2]['foc'] = $value2['foc'];
            $list_vat[$key2]['foc_all'] = $value2['foc_all'];
            $list_vat[$key2]['type'] = $value2['type'];
            $list_vat[$key2]['note'] = '';
            $list_vat[$key2]['total_before_tax'] = System::display_number($list_vat[$key2]['total_before_tax']);
            $list_vat[$key2]['total_service_rate'] = System::display_number($list_vat[$key2]['total_service_rate']);
            $list_vat[$key2]['total_tax_rate'] = System::display_number($list_vat[$key2]['total_tax_rate']);
            $list_vat[$key2]['total_amount'] = System::display_number($list_vat[$key2]['total_amount']);
            $list_vat[$key2]['folio_id'] = $value2['folio_id'];
            $list_vat[$key2]['printer_vat'] = $value2['printer_vat'];
        }
        $data['list_vat'] = $list_vat;
        return $data;
    }
    function get_bar_reservation()
    {
        $sql = DB::fetch_all("SELECT bar_reservation.*,bar_table.code as table_name  FROM bar_reservation inner join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id inner join bar_table on bar_table.id=bar_reservation_table.table_id WHERE bar_reservation.status = 'CHECKOUT' AND bar_reservation.printer_vat=0");
        foreach($sql as $key=>$value)
        {
            $data[$key]['id'] = $value['id'];
            $data[$key]['bar_reservation_id'] = $value['id'];
            $data[$key]['total_before_tax'] = $value['total_before_tax'];
            $data[$key]['total_service_rate'] = $value['total_before_tax'] * $value['bar_fee_rate']/100;
            $data[$key]['total_tax_rate'] = ($data[$key]['total_service_rate'] +  $value['total_before_tax']) * $value['tax_rate']/100;
            $data[$key]['total_amount'] = $value['total'];
            $data[$key]['service_rate'] = $value['bar_fee_rate'];
            $data[$key]['tax_rate'] = $value['tax_rate'];
            $data[$key]['description'] = 'CODE: '.$value['code'].' Table Name: '.$value['table_name'].' Guest Name: '.$value['receiver_name'].' Customer Name: '.$value['agent_name'].' Time: '.date('d/m/Y',$value['time']);
            $data[$key]['in_date'] = date('d/m/Y',$value['time']);
            $data[$key]['type'] = 'BAR';
            $data[$key]['foc'] = '';
            $data[$key]['foc_all'] = 0;
            $data[$key]['note'] = '';
            $data[$key]['printer_vat'] = 100;
        }
        return $data;
    }
    function cancel_vat()
    {
        $code = $_REQUEST['code'];
        $note = $_REQUEST['note'];
        $vat = DB::fetch("SELECT * from VAT_BILL where code='$code'");
        if($vat['department']=='RECEPTION')
        {
            if(strpos($vat['folio_id'],','))
            {
                $folio_ids = explode(",",$vat['folio_id']);
                for($i=0;$i<count($folio_ids);$i++)
                {
     			    DB::update("folio",array('printer_vat'=>0),"id=".$folio_ids[$i]);
        		}
            }
            else
            {
                DB::update("folio",array('printer_vat'=>0),"id=".$vat['folio_id']);
            }
        }
        elseif($vat['department']=='RESTAURANT')
        {
            if(strpos($vat['bar_reservation_id'],','))
            {
                $bar_ids = explode(",",$vat['bar_reservation_id']);
                for($i=0;$i<count($bar_ids);$i++)
                {
     			    DB::update("bar_reservation",array('printer_vat'=>0),"id=".$bar_ids[$i]);
        		}
            }
            else
            {
                DB::update("bar_reservation",array('printer_vat'=>0),"id=".$vat['bar_reservation_id']);
            }
        }
        $status =  DB::update('vat_bill',array('status'=>'CANCEL','note'=>$note),"code='".$code."'");
        
        if($status==true)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    switch($_REQUEST['data'])
    {
        case "get_folios":
        {
            echo json_encode(get_folios()); break;
        }
        case "get_traveller_folios":
        {
            echo json_encode(get_traveller_folios()); break;
        }
        case "get_bar_reservation":
        {
            echo json_encode(get_bar_reservation()); break;
        }
        case "cancel_vat":
        {
            echo json_encode(cancel_vat()); break;
        }
        default: echo '';break;
    }
?>