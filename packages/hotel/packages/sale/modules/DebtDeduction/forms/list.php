<?php
class DebtDeductionListForm extends Form
{
    function DebtDeductionListForm()
    {
        Form::Form();
    }
    function draw()
    {
        require_once 'packages/core/includes/utils/paging.php';
        $this -> map = array();
        $item_per_page = 50;
        
        $sql = '
                    SELECT 
                        count(*) as count
                    FROM 
                        CUSTOMER_REVIEW_DEBT
                        LEFT JOIN customer ON customer.id = CUSTOMER_REVIEW_DEBT.customer_id
                    WHERE 
                        CUSTOMER_REVIEW_DEBT.PORTAL_ID=\''.PORTAL_ID.'\'
                   
                ';
        $this->map['total'] =  DB::fetch($sql,'count');   
        $sql = 'SELECT * FROM
                (
                    SELECT 
                        CUSTOMER_REVIEW_DEBT.*,customer.id as cid, customer.name, party.name_1 as name_1,
                        ROW_NUMBER() OVER(ORDER BY CUSTOMER_REVIEW_DEBT.id DESC) AS rownumber
                    FROM 
                        CUSTOMER_REVIEW_DEBT
                        LEFT JOIN customer ON customer.id = CUSTOMER_REVIEW_DEBT.customer_id
                        INNER JOIN party ON party.user_id = CUSTOMER_REVIEW_DEBT.user_id
                    WHERE 
                        CUSTOMER_REVIEW_DEBT.PORTAL_ID=\''.PORTAL_ID.'\'
                    ORDER 
                        BY CUSTOMER_REVIEW_DEBT.id DESC
                 )
                 WHERE
                 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'   
                ';
        $this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array('action'));      
        $this -> map['items'] = DB::fetch_all($sql);       
        $this -> parse_layout('list',$this -> map);
    }
}       
?>