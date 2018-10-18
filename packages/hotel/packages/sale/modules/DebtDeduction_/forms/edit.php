<?php
class DebtDeductionForm extends Form
{
    function DebtDeductionForm()
    {
        Form::Form();
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css'); 
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css'); 
    }
    function draw()
    {
        $items = array();
        if(Url::get('id'))
        {
            $sql = '
                    SELECT CUSTOMER_REVIEW_DEBT.*,customer.id as customer_id, customer.name as customer_name
                        FROM CUSTOMER_REVIEW_DEBT
                        LEFT JOIN customer ON customer.id = CUSTOMER_REVIEW_DEBT.customer_id
                    WHERE CUSTOMER_REVIEW_DEBT.id='.Url::get('id')    
                ;
            $items = DB::fetch($sql);    
            
        }
        
        $this -> parse_layout('edit',$items );
    }
    function On_submit()
    {
        $currency_id=$_REQUEST['currency_id'];
        $check_debit =  $this -> check_debit($currency_id);
       
        if($check_debit>0)
        {
            ?>
                <script>
                    alert('số tiền thanh toán không được âm');
                </script>    
            <?php            
            return false;
        }
        $row = array();
            $row['date_in'] = Date_Time::to_orc_date($_REQUEST['date_in']);
            $row['customer_id'] = $_REQUEST['customer_id'];
            $row['description'] = $_REQUEST['description'];
            $row['currency_id'] = $_REQUEST['currency_id'];
            $row['recode'] = $_REQUEST['recode'];
            $row['price'] = System::calculate_number($_REQUEST['price']);
            $row['FOLIO_NUMBER'] = $_REQUEST['number_folio'];
            $row['portal_id'] = PORTAL_ID;
            $row['user_id'] = User::id();
        
        if(Url::get('cmd')=='add')
        {
            $id=DB::insert('CUSTOMER_REVIEW_DEBT',$row);
        }    
        elseif(Url::get('cmd')=='edit')
        {
            unset($row['user_id']);
            DB::update('CUSTOMER_REVIEW_DEBT',$row,'id='.Url::get('id')); 
        }
         Url::redirect_current();        
            
    }
    function check_debit($currency_id)
    {
        $payment = DB::fetch('
                        SELECT customer.id,
                               sum(payment.amount) as amount
                        FROM customer
                             INNER JOIN payment ON customer.id = payment.customer_id
                        WHERE  payment.time <='.(Date_Time::to_time(Url::get('date_in'))+86400).'
                               AND payment.payment_type_id=\'DEBIT\' AND payment.portal_id = \''.PORTAL_ID.'\' 
                               AND customer.id='.Url::get('customer_id').'
                               AND payment.currency_id=\''.$currency_id.'\'
                        GROUP BY customer.id                    
                    ');     
        $debit = DB::fetch('
                        SELECT customer.id,
                               sum(customer_review_debt.price) as price
                        FROM customer
                             INNER JOIN customer_review_debt ON customer.id = customer_review_debt.customer_id
                        WHERE customer_review_debt.date_in <= \''.Date_Time::to_orc_date(Url::get('date_in')).'\'
                              AND customer_review_debt.portal_id = \''.PORTAL_ID.'\'
                              AND customer_review_debt.customer_id='.Url::get('customer_id').'
                              AND customer_review_debt.currency_id=\''.$currency_id.'\'
                        GROUP BY customer.id 
                    ');
        $debit = $debit['price']+ System::calculate_number(Url::get('price')) - $payment['amount'];  
        return $debit;            
    }
    
}
?>