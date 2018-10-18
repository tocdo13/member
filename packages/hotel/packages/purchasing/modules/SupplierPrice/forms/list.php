<?php
class ListSupplierPriceForm extends Form
{
	function ListSupplierPriceForm()
	{
		Form::Form('ListSupplierPriceForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
	}
    function on_submit()
    {
        if(isset($_REQUEST['submit_edit']))
        {
            $item_check_book = 0;
            foreach($_REQUEST['item_check_box'] as $value)
            {
                $item_check_book .= ','.$value;
            }
            echo $item_check_book;
            Url::redirect('supplier_price',array('cmd'=>'edit','ids'=>$item_check_book));
        } 
    }
	function draw()
	{
	    require_once 'packages/core/includes/utils/vn_code.php';   
		$this->map = array();
        //trung add: $this->map['suppliers']                                                                 //System::debug($this->map['supplier_id_list']);
        $this->map['suppliers'] = DB::fetch_all('
			SELECT
				supplier.code as id,
                supplier.id as supplier_id,
                supplier.name,
                supplier.address,
                supplier.tax_code
			FROM
				supplier
			ORDER BY
				supplier.code
		');
		/* trung start : tim kiem theo ten sp ,ma sp,nha cung cap */
        $cond =' 1=1 ';
        if(Url::get('supplier') != '')
        {
            System::debug('aa');
            $cond .= ' AND pc_sup_price.supplier_id='.Url::get('supplier_id');
        }else
        {
            
        }
        if (Url::get('code_product'))
        {
           $cond .=' AND pc_sup_price.product_id LIKE \'%'.mb_strtoupper(Url::get('code_product')).'%\''; 
                    
        }
        if (Url::get('product_name'))
        {
            $cond .=' AND product.name_1 LIKE \'%'.mb_strtoupper(Url::get('product_name'), 'utf-8').'%\'';
        }
        // Daund vi giam thoi gian load bang cach phan trang 
        $item_per_page = 100;
        DB::query("
    			SELECT 
    				count(pc_sup_price.id) as acount
                FROM 
                    pc_sup_price
                    INNER JOIN product ON pc_sup_price.product_id=product.id
                    INNER JOIN supplier ON pc_sup_price.supplier_id=supplier.id
                WHERE ".$cond."
		");
        $count = DB::fetch();
        require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('cmd','code_product','product_name','supplier'));
        $sql = "SELECT 
                    * 
                FROM
                    (SELECT 
                        pc_sup_price.id,
                        pc_sup_price.product_id,
                        pc_sup_price.supplier_id,
                        pc_sup_price.price,
                        pc_sup_price.starting_date,
                        pc_sup_price.ending_date,
                        pc_sup_price.price_after_tax,
                        NVL(pc_sup_price.tax,0) as tax,
                        product.name_1 as product_name,
                        supplier.name as supplier_name,
                        unit.name_".Portal::language()." as unit_name --oanh add,
                        ,row_number() over (".(URL::get('order_by')?"order by ".URL::get('order_by').(URL::get('order_dir')?" ".URL::get('order_dir'):""):"order by pc_sup_price.id DESC").") as rownumber
                    FROM 
                        pc_sup_price
                        INNER JOIN product ON pc_sup_price.product_id=product.id
                        INNER JOIN supplier ON pc_sup_price.supplier_id=supplier.id
                        inner join unit on unit.id= product.unit_id --oanh add
                    WHERE ".$cond."
                    ORDER BY 
                        pc_sup_price.supplier_id,pc_sup_price.product_id)
                WHERE
                    rownumber > ".(page_no()-1)*$item_per_page." and rownumber<=".(page_no()*$item_per_page)."
        ";
        $items = DB::fetch_all($sql);
        $i = 1;
        foreach($items as $key=>$value)
        {
            $items[$key]['index'] = $i++;
            $items[$key]['starting_date'] = Date_Time::convert_orc_date_to_date($value['starting_date'],"/");
            $items[$key]['ending_date'] = Date_Time::convert_orc_date_to_date($value['ending_date'],"/");
            $items[$key]['price'] = System::display_number($value['price']);
            $items[$key]['price_after_tax'] = System::display_number(round($value['price']*(1+$value['tax']/100)));
        }
        $this->map['items'] = $items;
        
        //1. hien thi cac thong tin chung cho phan edit 
        $suppliers = DB::fetch_all("SELECT id,name FROM supplier ORDER BY id");
        $options_suppliers ='<option value=0>--Nhà cung cấp--</option>';
        foreach($suppliers as $row)
        {
            if($row['id']==Url::get('supplier'))
                $options_suppliers .='<option value="'.$row['id'].'" selected="selected">'.$row['name'].'</option>';
            else
                $options_suppliers .='<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
        $this->map['option_suppliers'] = $options_suppliers;
        $this->map['paging'] = $paging;
		$this->parse_layout('list',$this->map);
	}	
}
?>