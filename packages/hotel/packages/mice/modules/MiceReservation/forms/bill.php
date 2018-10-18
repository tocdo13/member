<?php
class BillMiceReservationForm extends Form
{
	function BillMiceReservationForm()
    {
		Form::Form('BillMiceReservationForm');
	}
    function on_submit()
    {
    }
	function draw()
    {
        require_once 'packages/core/includes/utils/currency.php';
        
        $this->map = array();
        $invoice = DB::fetch("
                            SELECT
                                mice_invoice.*,
                                traveller.first_name || ' ' || traveller.last_name as traveller_name,
                                customer.name as customer_name
                            FROM
                                mice_invoice
                                left join reservation_traveller on reservation_traveller.id=mice_invoice.reservation_traveller_id
                                left join traveller on traveller.id=reservation_traveller.traveller_id
                                inner join mice_reservation on mice_reservation.id=mice_invoice.mice_reservation_id
                                left join customer on customer.id=mice_reservation.customer_id
                            WHERE
                                mice_invoice.id=".Url::get('invoice_id')."  
                            ");
        $this->map = $invoice;
        $this->map['service_charge'] = 0;
        $this->map['vat_charge'] = 0;
        $this->map['sub_total'] = 0;
        $this->map['total'] = $invoice['extra_vat'];
        $this->map['extra_amount'] = $invoice['extra_amount'];
        $this->map['extra_vat'] = $invoice['extra_vat'];
        
        $this->map['discount'] = 0;
        $this->map['deposit'] = 0;
        
        $this->map['room_name'] = '';
        $invoice_detail = DB::fetch_all("
                                SELECT
                                    mice_invoice_detail.*
                                FROM
                                    mice_invoice_detail
                                WHERE
                                    mice_invoice_detail.mice_invoice_id=".Url::get('invoice_id')."
                                ORDER BY
                                    mice_invoice_detail.id
                                ");
        $items = array();
        $stt = 1;
        foreach($invoice_detail as $key=>$value)
        {
            if($value['type']=='DEPOSIT_MICE')
            {
                $this->map['deposit'] += $value['total_amount'];
            }
            else
            {
                $items[$key]['id'] = $value['id'];
                $items[$key]['stt'] = $stt;
                $items[$key]['description'] = $value['description'];
                $items[$key]['quantity'] = '';
                $items[$key]['amount'] = $value['amount'];
                if($value['type']=='DISCOUNT')
                {
                    //$this->map['service_charge'] -= $value['service_amount'];
                    //$this->map['vat_charge'] -= $value['tax_amount'];
                    $this->map['sub_total'] -= $value['amount'];
                    $this->map['total'] -= $value['amount'];
                    $this->map['discount'] += $value['amount'];
                }
                else
                {
                    $this->map['service_charge'] += $value['service_amount'];
                    $this->map['vat_charge'] += $value['tax_amount'];
                    $this->map['sub_total'] += $value['amount'];
                    $this->map['total'] += $value['total_amount'];
                }
                
            }
        }
        $this->map['items']=$items;
        $this->map['payment'] = DB::fetch_all("SELECT payment.id,payment_type.name_".Portal::language()." as payment_type_name,payment.amount FROM payment inner join payment_type on payment_type.def_code=payment.payment_type_id where payment.type='BILL_MICE' AND payment.bill_id=".Url::get('invoice_id'));
        $this->map['total_amount_in_word'] = currency_to_text(round(($this->map['total']-$this->map['deposit']),0));
        //System::debug($this->map);
        $this->parse_layout('bill',$this->map);
    }
}
?>
