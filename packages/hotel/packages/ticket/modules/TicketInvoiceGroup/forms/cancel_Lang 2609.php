<?php 
class CancelTicketForm extends Form
{
    function CancelTicketForm()
	{
		Form::Form('CancelTicketForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
	}
    function on_submit()
    {
        if(isset($_REQUEST['cancel']) and Url::get('invoice_id'))
        {
            foreach($_REQUEST['cancel'] as $key => $value)
            {
                $sql = 'select 
                            ticket_invoice.*,
                            ticket.name as ticket_name 
                    from 
                        ticket_invoice inner join ticket on ticket.id = ticket_invoice.ticket_id
                    where ticket_invoice.id = '.Url::get('invoice_id');
                $invoice_detail = DB::fetch($sql);
                $data['ticket_id'] = $invoice_detail['ticket_id'];
                $data['ticket_invoice_id'] = $invoice_detail['id'];
                $data['user_id'] = Session::get('user_id');
                $data['time'] = time();
                $data['ticket_reservation_id'] = $invoice_detail['ticket_reservation_id'];
                $data['ticket_serie'] = $value;
                $data['note'] = Url::get('note');
                $reduce_money = $invoice_detail['total']/($invoice_detail['quantity'] - $invoice_detail['discount_quantity']);
                $invoice_data['total'] = $invoice_detail['total'] - $reduce_money;
                $invoice_data['quantity'] = $invoice_detail['quantity'] - 1;
                $invoice_data['ticket_date'] = $invoice_detail['ticket_date'];
                if(!DB::fetch('select * from ticket_cancelation where ticket_invoice_id = '.Url::get('invoice_id').' and ticket_serie ='.$value))
                {
                    $cancel_id = DB::insert('ticket_cancelation',$data);   
                }
                if(isset($cancel_id) and $cancel_id != '')
                {
                    DB::update('ticket_invoice',$invoice_data,'id = '.$invoice_detail['id']);
                    $row = DB::fetch('select total, discount_rate, num_cancel from ticket_reservation where id = '.$invoice_detail['ticket_reservation_id']);
                    DB::update('ticket_reservation',array('total'=>($row['total'] - $reduce_money + $reduce_money*$row['discount_rate']/100),'num_cancel' => ($row['num_cancel'] + 1)),'id = '.$invoice_detail['ticket_reservation_id']);
                }
            }
            if(!$this->is_error())
			{
				echo '<script>
                        alert("'.Portal::language('cancel_ticket_success').'");
                        //var at_path = window.parent.location.toString();
                        //var ts_path = at_path.replace("id='.Url::get('from_code').'","id='.Url::get('to_code').'");
                        //alert(ts_path);
                        window.parent.location.reload();
                    </script>';
                //Url::redirect(array('id'=>Url::get('to_code')));
			}
            //System::debug($_REQUEST);
            //exit();    
        } 
    }
    function draw()
    {
        if(Url::get('invoice_id'))
        {
            $sql = 'select 
                            ticket_invoice.*,
                            ticket.name as ticket_name 
                    from 
                        ticket_invoice inner join ticket on ticket.id = ticket_invoice.ticket_id
                    where ticket_invoice.id = '.Url::get('invoice_id');
            $invoice_detail = DB::fetch($sql);
            //System::debug($invoice_detail);
            if($invoice_detail['last_code'] == '')
            {
                echo '<h1 style = "color: red;">'.Portal::language('ticket_is_not_printed').'</h1>';
                exit();
            }
            $this->map['ticket_quantity'] = $invoice_detail['quantity'];
            $this->map['ticket_name'] = $invoice_detail['ticket_name'];
            $this->map['create_user'] = $invoice_detail['user_id'];
            $this->map['last_code'] = $invoice_detail['last_code'];
            $this->map['start_code'] = $invoice_detail['start_code'];
            $this->map['invoice_id'] = $invoice_detail['id'];
            $this->parse_layout('cancel',$this->map);
            //System::debug($invoice_detail);
        }  
        else
        {
            echo '<h1 style = "color: red;">'.Portal::language('is_not_saved').'</h1>';
            exit();
        } 
    }
}
?>