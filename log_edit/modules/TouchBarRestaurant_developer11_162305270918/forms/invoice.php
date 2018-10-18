<?php
class InvoiceBarForm extends Form
{
	function InvoiceBarForm()
	{
		Form::Form("InvoiceBarForm");
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.css');        
	}
    function on_submit()
    {
        //System::debug($_REQUEST); die;
        if(isset($_REQUEST['invoice'])){
            if(Url::get('invoice_id')){
                $record = array(
                            'last_edit_time'=>time(),
                            'last_editer'=>User::id(),
                            'total_before_tax'=>System::calculate_number(Url::get('total_before_tax')),
                            'tax_amount'=>System::calculate_number(Url::get('tax_amount')),
                            'service_amount'=>System::calculate_number(Url::get('service_amount')),
                            'total_amount'=>System::calculate_number(Url::get('total_amount'))
                            );
                DB::update('bar_invoice',$record,'id='.Url::get('invoice_id'));
                $invoice_id = Url::get('invoice_id');
                /** log update **/
                
                /** end log update **/
            }else{
                
                $record = array(
                            'bar_reservation_id'=>Url::get('id'),
                            'create_time'=>time(),
                            'creater'=>User::id(),
                            'last_edit_time'=>time(),
                            'last_editer'=>User::id(),
                            'total_before_tax'=>System::calculate_number(Url::get('total_before_tax')),
                            'tax_amount'=>System::calculate_number(Url::get('tax_amount')),
                            'service_amount'=>System::calculate_number(Url::get('service_amount')),
                            'total_amount'=>System::calculate_number(Url::get('total_amount'))
                            );
                $invoice_id = DB::insert('bar_invoice',$record);
                /** log insert **/
                
                /** end log insert **/
            }
            
            $detail_id_list = '';
            foreach($_REQUEST['invoice'] as $key=>$value){
                $value['bar_invoice_id'] = $invoice_id;
                $value['total'] = System::calculate_number($value['total']);
                $value['amount_before_tax'] = System::calculate_number($value['amount_before_tax']);
                $value['service_amount'] = System::calculate_number($value['service_amount']);
                $value['tax_amount'] = System::calculate_number($value['tax_amount']);
                $value['amount'] = System::calculate_number($value['amount']);
                
                unset($value['quantity_old']);
                
                if($value['id']==''){
                    unset($value['id']);
                    $detail_id = DB::insert('bar_invoice_detail',$value);
                }else{
                    $detail_id = $value['id'];
                    DB::update('bar_invoice_detail',$value);
                }
                if($value['type']=='DEPOSIT' and !Url::get('invoice_id')){
                    DB::update('bar_invoice',array('payment_time'=>time()),'id='.$invoice_id);
                }
                $detail_id_list .= $detail_id_list==''?$detail_id:','.$detail_id;
                /** log detail **/
                
                /** end log detail **/
            }
            if($detail_id_list!=''){
                DB::delete('bar_invoice_detail',' id not in ('.$detail_id_list.') and bar_invoice_id='.$invoice_id);
            }
            
            $payment_id_list = '';
            if(isset($_REQUEST['payment'])){
                foreach($_REQUEST['payment'] as $k=>$v){
                    $pay = array(
                                'bill_id'=>$invoice_id,
                                'type'=>'BAR_INVOICE',
                                'amount'=>System::calculate_number($v['amount']),
                                'payment_type_id'=>$v['payment_type_id'],
                                'credit_card_id'=>isset($v['credit_card_id'])?$v['credit_card_id']:'',
                                'currency_id'=>'VND',
                                'description'=>$v['description'],
                                'exchange_rate'=>1,
                                'portal_id'=>PORTAL_ID,
                                'bank_acc'=>$v['bank_acc']
                                );
                    if($v['id']!=''){
                        $payment_id = $v['id'];
                        DB::update('payment',$pay,'id='.$payment_id);
                    }else{
                        $pay['time'] = time();
                        $pay['user_id'] = User::id();
                        $payment_id = DB::insert('payment',$pay);
                    }
                    $payment_id_list .= $payment_id_list==''?$payment_id:','.$payment_id;
                    /** log payment **/
                
                    /** end log payment **/
                }
                DB::delete('payment','type=\'BAR_INVOICE\' and id not in ('.$payment_id_list.') and bill_id='.$invoice_id);
            }else{
                DB::delete('payment','type=\'BAR_INVOICE\' and bill_id='.$invoice_id);
            }
            /** save log **/
                
            /** end save log **/
        }else{
            if(Url::get('invoice_id')){
                DB::delete('bar_invoice_detail','bar_invoice_id='.Url::get('invoice_id'));
                DB::delete('payment','type=\'BAR_INVOICE\' and bill_id='.Url::get('invoice_id'));
                DB::delete('bar_invoice','id='.Url::get('invoice_id'));
                /** Log delete **/
                
                /** end Log delete **/
            }
        }
        Url::redirect('touch_bar_restaurant',array('cmd'=>'invoice','id'=>Url::get('id'),'table_id'=>Url::get('table_id'),'bar_area_id'=>Url::get('bar_area_id'),'bar_id'=>Url::get('bar_id')));
    }
	function draw()
	{
	   $this->map = array();
       
       $this->map['payment_type_id_list'] = DB::fetch_all('SELECT * FROM payment_type WHERE def_code is not null and apply=\'ALL\' ORDER BY name_'.Portal::language());
       $this->map['payment_type_option'] = '<option value="">'.Portal::language('select').'</option>';
       foreach($this->map['payment_type_id_list'] as $key=>$value){
            $this->map['payment_type_option'] .= '<option value="'.$value['def_code'].'">'.$value['name_'.Portal::language()].'</option>';
       }
       
       $this->map['credit_card_list'] = DB::fetch_all('SELECT * FROM credit_card ORDER BY name');
       $this->map['credit_card_option'] = '<option value="">'.Portal::language('select').'</option>';
       foreach($this->map['credit_card_list'] as $key=>$value){
            $this->map['credit_card_option'] .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
       }
       
       $this->map['bar_name'] = DB::fetch('select name from bar where id='.Session::get('bar_id'),'name');
       $row = DB::select('bar_reservation',Url::get('id'));
       $this->map += $row;
       
       /** get invoice **/
       $invoice_other = DB::fetch_all('
                                        SELECT
                                            bar_invoice_detail.*
                                            ,bar_invoice_detail.type || \'_\' || bar_invoice_detail.invoice_id as id
                                            ,bar_invoice_detail.id as detail_id
                                        FROM
                                            
                                        ');
       /** end invoice **/
       $product = TouchBarRestaurantDB::get_reservation_product('');
       
       $items = array();
       foreach($product as $key=>$value){
            $quantity = $value['quantity'] - $value['quantity_discount'];
            $amount_before_tax = $value['price'];
            if($row['full_rate']==1){
                $amount_before_tax = $value['price'] / ( (1+$row['bar_fee_rate']/100)*(1+$row['tax_rate']/100) );
            }else if($row['full_charge']==1){
                $amount_before_tax = $value['price'] / (1+$row['bar_fee_rate']/100);
            }
            if($value['discount_rate']!='' and $value['discount_rate']>0){
                $amount_before_tax = $amount_before_tax - ($amount_before_tax*($value['discount_rate']/100));
                $value['name'] .= ' ('.Portal::language('discount').' '.$value['discount_rate'].'%) ';
            }
            
            if($row['discount_percent']!='' and $row['discount_percent']>0 and $row['discount_after_tax']==0){
                // giam gia toan hoa don truoc thue phi
                $amount_before_tax = $amount_before_tax - ($amount_before_tax*($row['discount_percent']/100));
                $value['name'] .= ' ('.Portal::language('discount').' '.$row['discount_percent'].'% '.Portal::language('in_invoice').') ';
            }
            
            $service_amount = $amount_before_tax * ($row['bar_fee_rate']/100);
            $tax_amount = $amount_before_tax * (1+($row['bar_fee_rate']/100)) * ($row['tax_rate']/100);
            $amount = $amount_before_tax * (1+$row['bar_fee_rate']/100)*(1+$row['tax_rate']/100);
            if($row['discount_percent']!='' and $row['discount_percent']>0 and $row['discount_after_tax']==1){
                // giam gia toan hoa don sau thue phi
                $amount = $amount - ($amount*($row['discount_percent']/100));
                $value['name'] .= ' ('.Portal::language('discount').' '.$row['discount_percent'].'% '.Portal::language('in_invoice').') ';
            }
            $total_amount = $amount*$quantity;
            // lay id cua bar reservation product lam key vi lien quan den mon Extra 
            
            $items['PRODUCT_'.$value['id']]['id'] = 'PRODUCT_'.$value['id'];
            $items['PRODUCT_'.$value['id']]['name'] = $value['name'];
            $items['PRODUCT_'.$value['id']]['invoice_id'] = $value['id'];
            $items['PRODUCT_'.$value['id']]['type'] = 'PRODUCT';
            $items['PRODUCT_'.$value['id']]['invoice_list'] = '';
            $items['PRODUCT_'.$value['id']]['quantity_old'] = $quantity;
            $items['PRODUCT_'.$value['id']]['quantity'] = $quantity;
            $items['PRODUCT_'.$value['id']]['amount_before_tax'] = $amount_before_tax;
            $items['PRODUCT_'.$value['id']]['tax_amount'] = $service_amount;
            $items['PRODUCT_'.$value['id']]['service_amount'] = $service_amount;
            $items['PRODUCT_'.$value['id']]['amount'] = $amount;
            $items['PRODUCT_'.$value['id']]['total'] = $total_amount;
       }
       if($row['deposit']!='' and $row['deposit']>0){
            $items['DEPOSIT_'.$row['id']]['id'] = 'DEPOSIT_'.$row['id'];
            $items['DEPOSIT_'.$row['id']]['name'] = portal::language('deposit');
            $items['DEPOSIT_'.$row['id']]['invoice_id'] = $row['id'];
            $items['DEPOSIT_'.$row['id']]['type'] = 'DEPOSIT';
            $items['DEPOSIT_'.$row['id']]['invoice_list'] = '';
            $items['DEPOSIT_'.$row['id']]['quantity_old'] = 1;
            $items['DEPOSIT_'.$row['id']]['quantity'] = 1;
            $items['DEPOSIT_'.$row['id']]['amount_before_tax'] = $row['deposit'];
            $items['DEPOSIT_'.$row['id']]['tax_amount'] = 0;
            $items['DEPOSIT_'.$row['id']]['service_amount'] = 0;
            $items['DEPOSIT_'.$row['id']]['amount'] = $row['deposit'];
            $items['DEPOSIT_'.$row['id']]['total'] = $row['deposit'];
       }
       if($row['discount']!='' and $row['discount']>0){
            $items['DISCOUNT_'.$row['id']]['id'] = 'DISCOUNT_'.$row['id'];
            $items['DISCOUNT_'.$row['id']]['name'] = portal::language('discount');
            $items['DISCOUNT_'.$row['id']]['invoice_id'] = $row['id'];
            $items['DISCOUNT_'.$row['id']]['type'] = 'DISCOUNT';
            $items['DISCOUNT_'.$row['id']]['invoice_list'] = '';
            $items['DISCOUNT_'.$row['id']]['quantity_old'] = 1;
            $items['DISCOUNT_'.$row['id']]['quantity'] = 1;
            $items['DISCOUNT_'.$row['id']]['amount_before_tax'] = $row['discount'];
            $items['DISCOUNT_'.$row['id']]['tax_amount'] = 0;
            $items['DISCOUNT_'.$row['id']]['service_amount'] = 0;
            $items['DISCOUNT_'.$row['id']]['amount'] = $row['discount'];
            $items['DISCOUNT_'.$row['id']]['total'] = $row['discount'];
       }
       
       $this->map['items'] = $items;
       $this->parse_layout('invoice',$this->map);
	}
    
}
?>
