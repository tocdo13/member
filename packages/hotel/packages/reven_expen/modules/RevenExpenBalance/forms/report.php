<?php 
class RevenExpenBalanceForm extends Form{
    function RevenExpenBalanceForm(){
        Form::Form('RevenExpenBalanceForm');
    }
    function on_submit()
    {
        //System::debug($_REQUEST);exit();
        if(isset($_REQUEST))
        {
            foreach($_REQUEST as $key=>$record)
            {
                if(strpos($key,"balance_") !== false)
                {
                    $upda = array();
                    $upda['CURENCY_ID'] = str_replace("balance_","",$key);
                    $upda['amount'] = str_replace(",","",$record);
                    $upda['portal_id'] = PORTAL_ID;
                    if(DB::exists('select id from REVEN_EXPEN_BALANCE where CURENCY_ID=\''.$upda['CURENCY_ID'].'\' and PORTAL_ID=\''.$upda['portal_id'].'\''))
                    {
                        DB::update('REVEN_EXPEN_BALANCE',$upda,'CURENCY_ID=\''.$upda['CURENCY_ID'].'\' and PORTAL_ID=\''.$upda['portal_id'].'\'');
                    }
                    else
                    {
                        $id = DB::insert('REVEN_EXPEN_BALANCE',$upda);
                    }
                }
            }
        }
    }
    function draw(){
        $balances= DB::fetch_all("select 
                                    CURRENCY.id, 
                                    CURRENCY.id as name,
                                    REVEN_EXPEN_BALANCE.amount
                                from CURRENCY 
                                    left join REVEN_EXPEN_BALANCE on (REVEN_EXPEN_BALANCE.curency_id = CURRENCY.id and REVEN_EXPEN_BALANCE.portal_id = '".PORTAL_ID."')
                                where CURRENCY.allow_payment = 1 
                                order by name");
        
        //System::debug($balances);exit();
		$this->parse_layout('report',array("balances"=>$balances));
    }
}
?>
