<?php
class ViewMoveRecommendationForm extends Form
{
    function ViewMoveRecommendationForm()
    {
        Form::Form('ViewMoveRecommendationForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
    }
    function draw()
    {
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        $this->map = array();
        $recommend = DB::fetch('SELECT * FROM pc_recommendation WHERE id='.Url::get('id'));

        if(empty($recommend)==false)
        {
            $this->map['recommend_date'] = date('d/m/Y',$recommend['recommend_time']);
            $this->map['recommend_time'] = date('H:i',$recommend['recommend_time']);
            $this->map['person_recommend'] = $recommend['recommend_person'];
            $this->map['description'] = $recommend['description'];
            //2. lay ra thong tin bo phan theo id 
            $sql ="SELECT department.id,
                        department.name_".Portal::language()." as name,
                        portal_department.warehouse_pc_id
                    FROM department 
                    INNER JOIN portal_department ON department.code=portal_department.department_code
                    WHERE portal_department.id=".$recommend['portal_department_id'];
            $row = DB::fetch($sql);
            $this->map['department_name'] = $row['name'];
            
            $this->map['department_id'] = $recommend['portal_department_id'];
            
            //3. lay ra danh sach cac san pham 
            $sql = "SELECT  product.id,
                           sum(pc_recommend_detail.quantity) as quantity,
                            product.name_1 as product_name,
                            unit.name_1 as unit
                        FROM pc_recommend_detail
                        INNER JOIN product ON product.id=pc_recommend_detail.product_id
                        INNER JOIN unit ON unit.id=product.unit_id
                        WHERE pc_recommend_detail.recommend_id=".$recommend['id']."
                        group by product.id,product.name_1,unit.name_1
                        ORDER BY product.id";
            $items = DB::fetch_all($sql);
            $i = 1;
            $warehouse_id = $row['warehouse_pc_id'];
            foreach($items as $key=>$value)
            {
                //lay ra so luong ton cho san pham do o kho hien tai
                $product_remain_warehouse = get_remain_products($warehouse_id,false,$value['id']);
                //lay ra ton kho tong cong cua san pham do 
                $product_remain_total = get_remain_products('',false,$value['id']);
                if(empty($product_remain_warehouse))
                    $items[$key]['remain_department'] = 0;
                else
                {
                    foreach($product_remain_warehouse as $k=>$v)
                    {
                        $items[$key]['remain_department'] = $v['remain_number'];
                        break;
                    }
                }

                if(empty($product_remain_total))
                    $items[$key]['remain_total'] = 0;
                else
                {
                    foreach($product_remain_total as $k=>$v)
                    {
                        $items[$key]['remain_total'] = $v['remain_number'] - $items[$key]['remain_department'];
                        break;
                    }
                }
                $items[$key]['index'] = $i++;
            }  
        }
        $this->map['products'] = $items;
        $this->parse_layout('view_move',$this->map);
	}
    
}
?>
