<?php
class WhStartTermDebitForm extends Form{
	function WhStartTermDebitForm()
    {
		Form::Form('WhStartTermDebitForm');
    	$this->add('debit.total',new TextType(true,'total',0,255));
		$this->add('debit.supplier_id',new TextType(true,'supplier_id',0,255));
        $this->add('debit.supplier_id',new UniqueType(false,'duplicate_supplier_id','wh_start_term_debit','supplier_id'));
        $this->add('debit.currency_id',new TextType(true,'currency_id',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
        require_once 'packages/hotel/includes/php/module.php';
	}
    
	function on_submit()
    {
        if(Url::get('save'))
        {
            if($this->check())
            {
                if(URL::get('deleted_ids'))
                {
    				$ids = explode(',',URL::get('deleted_ids'));
    				foreach($ids as $id)
                    {
    					DB::delete_id('vending_start_term_debit',$id);
    				}
    			}
                
                if(isset($_REQUEST['mi_debit']))
                {
                	foreach($_REQUEST['mi_debit'] as $key=>$record)
                	{
                        $record['portal_id'] = PORTAL_ID; 
                		$record['total'] = System::calculate_number($record['total']);
                        $record['exchange_rate'] = DB::fetch('select id, exchange from currency where id = \''.$record['currency_id'].'\'','exchange');
                		
    					if($record['id'] and DB::exists_id('vending_start_term_debit',$record['id']))
                        {
                            DB::update_id('vending_start_term_debit',$record,$record['id']);
                        }
                        else
                        {
                            unset($record['id']);
                            DB::insert('vending_start_term_debit',$record);
                        }
                    }
                    Url::redirect_current();
                }
                else
                {
                	return;
                }
            }
        }

	}	
	function draw()
    {
        $this->map = array();
        $currency = DB::fetch_all('Select * from currency where allow_payment = 1 order by id desc');
        //System::debug($currency_id_list);
        $currency_id_list = '
                             <option value="USD">'.Portal::language('USD').'</option>';
        foreach($currency as $k=>$v)
        {
            if($k!='USD')
                $currency_id_list.= '<option value="'.$k.'">'.$v['name'].'</option>';
        }
        $this->map['currency_id_list'] = $currency_id_list;
        
        
        $supplier = DB::select_all('vending_customer');
        
        //$supplier_id_list = '<option value="">'.Portal::language('select').'</option>';
        $supplier_id_list = '';
        
        foreach($supplier as $k=>$v)
        {
            if($k!='USD')
                $supplier_id_list.= '<option value="'.$k.'">'.$v['name'].'</option>';
        }
        $this->map['supplier_id_list'] = $supplier_id_list;
        
        
		$sql = 'SELECT 
                    vending_start_term_debit.*,
                    vending_customer.name as supplier_name 
                FROM 
                    vending_start_term_debit
                    inner join vending_customer on vending_customer.id = vending_start_term_debit.supplier_id
                where 
                    vending_start_term_debit.portal_id=\''.PORTAL_ID.'\'  
                order by 
                    vending_start_term_debit.id';
		$debit = DB::fetch_all($sql);
        
		foreach($debit as $k=>$v)
        {
            $debit[$k]['total'] = System::display_number($debit[$k]['total']);
        }
        //System::Debug($debits);

		$_REQUEST['mi_debit'] = $debit;
        	
		$this->parse_layout('edit',$this->map);
	}
}
?>