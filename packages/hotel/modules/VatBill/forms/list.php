<?php
class VatBillListForm extends Form
{
	function VatBillListForm()
	{
		Form::Form('VatBillListForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
	   $this->map = array();
       
       $cond = '';
       if(Url::get('vat_code')) {
            $cond .= ' and vat_bill.vat_code=\''.Url::get('vat_code').'\'';
       }
       else {
            if(Url::get('start_date') and Url::get('end_date')) {
                $cond .= ' and vat_bill.start_date<=\''.Date_Time::to_orc_date(Url::get('end_date')).'\' and vat_bill.end_date>=\''.Date_Time::to_orc_date(Url::get('start_date')).'\'';
            }
            if(Url::get('vat_type') and Url::get('vat_type')!='CANCEL') {
                $cond .= ' and vat_bill.vat_type=\''.Url::get('vat_type').'\'';
            }
            elseif(Url::get('vat_type')=='CANCEL') {
                $cond.= ' and vat_bill.status=\'CANCEL\'';
            }
            if(Url::get('type') and Url::get('type')!='ALL') {
                if(Url::get('type')=='OTHER')
                    $cond .= ' and (vat_bill.type=\'\' or vat_bill.type is null)';
                else
                    $cond .= ' and vat_bill.type=\''.Url::get('type').'\'';
            }
            else {
                $this->map['type'] = 'ALL';
            }
       }
       
       $this->map['vat_type_list'] = array(''=>Portal::language('all'),'SAVE_CODE'=>Portal::language('save_code'),'PRINT'=>Portal::language('printed'),'SAVE_NO_PRINT'=>Portal::language('save_and_no_print_vat'),'CANCEL'=>Portal::language('cancel'));
       $this->map['type_list'] = array('ALL'=>Portal::language('all'),'BAR'=>Portal::language('restaurant'),'BANQUET'=>Portal::language('party'),'FOLIO'=>Portal::language('reception'),'MICE'=>Portal::language('mice'),'OTHER'=>Portal::language('vat_other'));
        
        require_once 'packages/core/includes/utils/paging.php';
        $item_per_page = 100;
        $sql = '
            SELECT
                count(vat_bill.id) as acount
            FROM
                vat_bill
                left join customer on customer.id=vat_bill.customer_id
            WHERE
                vat_bill.portal_id=\''.PORTAL_ID.'\' 
                '.$cond.'
            order by
                vat_bill.create_time
		';
        $total =  DB::fetch($sql,'acount');
        //System::debug($total);
        $paging =  paging($total,$item_per_page,10,false,'page_no',array('vat_code','start_date','end_date','vat_type','type'));
       $items = DB::fetch_all('
                        SELECT 
                            id,
                            type,
                            vat_code,
                            vat_type,
                            guest_name,
                            customer_id,
                            payment_method,
                            description,
                            note,
                            total_before_tax,
                            service_amount,
                            tax_amount,
                            total_amount,
                            create_time,
                            creater,
                            last_editer,
                            last_edit_time,
                            count_print,
                            portal_id,
                            status,
                            note_cancel,
                            time_cancel,
                            user_cancel,
                            customer_name,
                            customer_tax_code,
                            customer_address,
                            customer_bank_code,
                            print_date,
                            description_room,
                            description_bar,
                            description_banquet,
                            description_service,
                            start_date,
                            end_date,
                            customer_name
                        FROM
                            (SELECT
                                    vat_bill.id,
                                    vat_bill.type,
                                    vat_bill.vat_code,
                                    vat_bill.vat_type,
                                    vat_bill.guest_name,
                                    vat_bill.customer_id,
                                    vat_bill.payment_method,
                                    vat_bill.description,
                                    vat_bill.note,
                                    vat_bill.total_before_tax,
                                    vat_bill.service_amount,
                                    vat_bill.tax_amount,
                                    vat_bill.total_amount,
                                    vat_bill.create_time,
                                    vat_bill.creater,
                                    vat_bill.last_editer,
                                    vat_bill.last_edit_time,
                                    vat_bill.count_print,
                                    vat_bill.portal_id,
                                    vat_bill.status,
                                    vat_bill.note_cancel,
                                    vat_bill.time_cancel,
                                    vat_bill.user_cancel,
                                    vat_bill.customer_name,
                                    vat_bill.customer_tax_code,
                                    vat_bill.customer_address,
                                    vat_bill.customer_bank_code,
                                    vat_bill.print_date,
                                    vat_bill.description_room,
                                    vat_bill.description_bar,
                                    vat_bill.description_banquet,
                                    vat_bill.description_service,
                                    TO_CHAR(vat_bill.start_date,\'DD/MM/YYYY\') as start_date,
                                    TO_CHAR(vat_bill.end_date,\'DD/MM/YYYY\') as end_date,
                                    customer.name as customer_code,
                                    row_number() over (order by vat_bill.create_time DESC) as rownumber
                                FROM
                                    vat_bill
                                    left join customer on customer.id=vat_bill.customer_id
                                WHERE
                                    vat_bill.portal_id=\''.PORTAL_ID.'\' 
                                    '.$cond.'
                                order by
                                    vat_bill.create_time
                        )
                        WHERE
				            rownumber > '.(page_no()-1)*$item_per_page.' and rownumber<='.(page_no()*$item_per_page).'
       ');
       
       $vat_invoice = DB::fetch_all('
                                    SELECT
                                        vat_invoice.*,
                                        CASE
                                            WHEN folio.total=0
                                            THEN folio.id
                                            ELSE folio.code 
                                            END folio_code,
                                        folio.total as folio_total,
                                        bar_reservation.code as bar_code,
                                        party_reservation.id as party_reservation_id,
                                        mice_invoice.id as mice_invoice_id,
                                        mice_invoice.mice_reservation_id,
                                        mice_invoice.bill_id,
                                        mice_invoice.payment_time as payment_time_mice,
                                        mice_invoice.create_time as create_time_mice,
                                        vat_bill.type
                                    FROM
                                        vat_invoice
                                        inner join vat_bill on vat_invoice.vat_bill_id=vat_bill.id
                                        left join folio on folio.id=vat_invoice.invoice_id and vat_bill.type=\'FOLIO\'
                                        left join bar_reservation on bar_reservation.id=vat_invoice.invoice_id and vat_bill.type=\'BAR\'
                                        left join party_reservation on party_reservation.id=vat_invoice.invoice_id and vat_bill.type=\'BANQUET\'
                                        left join mice_invoice on mice_invoice.id=vat_invoice.invoice_id and vat_bill.type=\'MICE\'
                                    WHERE
                                        vat_bill.portal_id=\''.PORTAL_ID.'\' 
                                        '.$cond.'
                                    ');
       //System::debug($vat_invoice);
       $invoice_group = array();
       foreach($vat_invoice as $key=>$value) {
            if(!isset($invoice_group[$value['vat_bill_id']])) {
                $invoice_group[$value['vat_bill_id']]['invoice_ids'] = '';
            }
            if($invoice_group[$value['vat_bill_id']]['invoice_ids']=='') {
                if($value['type']=='FOLIO'){
                    if($value['folio_total']==0)
                        $invoice_group[$value['vat_bill_id']]['invoice_ids'] = 'Ref'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT);
                    else
                        $invoice_group[$value['vat_bill_id']]['invoice_ids'] = 'No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT);
                }
                elseif($value['type']=='BAR')
                    $invoice_group[$value['vat_bill_id']]['invoice_ids'] = $value['bar_code'];
                elseif($value['type']=='BANQUET')
                    $invoice_group[$value['vat_bill_id']]['invoice_ids'] = $value['party_reservation_id'];
                elseif($value['type']=='MICE')
                {
                    $mice_code = ($value['bill_id'] !='')?'BILL - '.$value['bill_id']:'MICE +'.$value['mice_reservation_id'];
                    $invoice_group[$value['vat_bill_id']]['invoice_ids'] = $mice_code;
                }
                else
                    $invoice_group[$value['vat_bill_id']]['invoice_ids'] = $value['invoice_id'];
            }
            else {
                if($value['type']=='FOLIO'){
                    if($value['folio_total']==0)
                        $invoice_group[$value['vat_bill_id']]['invoice_ids'] .= ', '.'Ref'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT);
                    else
                        $invoice_group[$value['vat_bill_id']]['invoice_ids'] .= ', '.'No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT);
                }
                elseif($value['type']=='BAR')
                    $invoice_group[$value['vat_bill_id']]['invoice_ids'] .= ', '.$value['bar_code'];
                elseif($value['type']=='BANQUET')
                    $invoice_group[$value['vat_bill_id']]['invoice_ids'] .= ', '.$value['party_reservation_id'];
                elseif($value['type']=='MICE')
                {
                    $mice_code = ($value['bill_id'] !='')?'BILL - '.$value['bill_id']:'MICE +'.$value['mice_reservation_id'];
                    $invoice_group[$value['vat_bill_id']]['invoice_ids'] .= ', '. $mice_code;
                }
                else
                    $invoice_group[$value['vat_bill_id']]['invoice_ids'] .= ', '.$value['invoice_id'];
            }
       }
       //System::debug($invoice_group);
       $stt = 0;
       foreach($items as $key=>$value) {
            $stt++;
            $items[$key]['stt'] = $stt;
            $items[$key]['create_time'] = date('d/m/Y',$value['create_time']);
            $items[$key]['last_edit_time'] = date('d/m/Y',$value['last_edit_time']);
            $items[$key]['invoice_ids'] = '';
            $items[$key]['total_amount'] = System::display_number($value['total_amount']);
            if(isset($invoice_group[$key])) {
                $items[$key]['invoice_ids'] = $invoice_group[$key]['invoice_ids'];
            }
       }
       $this->map['paging']=$paging;
       $this->map['items'] = $items;
       
       $this->parse_layout('list',$this->map);
       
	}
}
?>