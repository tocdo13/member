<?php
class WarehouseRemainByWarehouseReportForm extends Form
{
	function WarehouseRemainByWarehouseReportForm()
	{
		Form::Form('WarehouseRemainByWarehouseReportForm');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');        
	}
	function draw()
	{
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        require_once 'packages/hotel/packages/warehousing/modules/WarehouseReport/forms/options.php';
         
        //exit();
		$this->map = array();
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End   :Luu Nguyen GIap add portal
        $warehouse = get_warehouse(true);
        
        $this->map['warehouse_id_list'] = array(''=>Portal::language('select_warehouse'))+String::get_list($warehouse);
        //$this->map['type_list'] = array(''=>Portal::language('all'),'IMPORT'=>Portal::language('import'),'EXPORT'=>Portal::language('export'));
		
        $this->map['title'] = Portal::language('Report_options');
       
       
        $this->map['date'] = Url::sget('date')?Url::sget('date'):date('d/m/Y');
        $_REQUEST['date'] = $this->map['date'];
        

            
            
        if(!Url::get('warehouse_id'))
        {
            $this->parse_layout('options',$this->map);
        }
        else
        {
            //cac sp ton
            $product_remain = array();
            //lay structure_id cha
            $structure_id = DB::fetch('Select * from warehouse where id = '.Url::get('warehouse_id'),'structure_id');
            //echo $structure_id;
            //Start Luu Nguyen Giap add portal
            if(Url::get('portal_id'))
            {
               $portal_id =  Url::get('portal_id');
            }
            else
            {
                $portal_id =PORTAL_ID;
            }
            if($portal_id!="ALL")
            {
                $cond ="  warehouse.portal_id ='".$portal_id."' ";
            }
            else
            {
                $cond=" 1=1 ";
            } 
            //End Luu Nguyen Giap add portal
            //lay nhom kho
            $group_wh = DB::fetch_all('Select * from warehouse where structure_id='.($structure_id).' AND '.$cond.' order by structure_id');
            $this->map['group_wh'] = $group_wh;
            //System::debug($group_wh);
            //echo IDStructure::child_cond($structure_id);
            $stt =1 ;
            foreach($group_wh as $wh_id=>$value)
            {
                //sp ton cua tung kho
                $products = get_remain_products($wh_id,true,false,false,false);
                if (User::is_admin())
                {
                    //System::debug($products);exit();
                }
                foreach($products as $product_id=>$v)
                {
                    //bo ban ghi co ton = 0;
                    if($v['remain_number']==0)
                    {
                        unset($products[$product_id]);
                        continue;
                    }
                    $products[$product_id]['total_product'] =$v['remain_money'];
                    //tao cac so ton tuong ung vs cac kho (lay theo id kho)
                    foreach($group_wh as $id_sub=>$value_sub)
                    {
                        if($v['warehouse_id']==$id_sub)
                        {
                            $products[$product_id]['remain_number_'.$id_sub] = number_format($v['remain_number'],3,".",",");//System::display_number_report($v['remain_number']);
                        }                            
                        else
                        {
                            $products[$product_id]['remain_number_'.$id_sub] = '0.00';
                        }                            
                    }
                    
                    //Dua vao mang tong hop
                    if(!isset($product_remain[$product_id]))
                    {
                        $product_remain[$product_id] = $products[$product_id];
                        $product_remain[$product_id]['stt'] = $stt;
                        $stt++;
                    }    
                    else
                    {
                        //cong don so ton cac kho
                        foreach($group_wh as $id_sub=>$value_sub)
                        {
                            $product_remain[$product_id]['remain_number_'.$id_sub] += number_format($products[$product_id]['remain_number_'.$id_sub],3,".",",");//System::calculate_number($products[$product_id]['remain_number_'.$id_sub]);
                        }
                    }
                    
                }
            }
            //System::debug($product_remain);
            $total_money =array();
           
            foreach($product_remain as $key=>$value)
            {
                $product_remain[$key]['total_product'] = round($value['total_product'], 2);
                if(isset($total_money['total_money']))
                {
                    $total_money['total_money'] += round($value['total_product'], 2);
                }
                else
                {
                    $total_money['total_money'] = round($value['total_product'], 2);
                }
                
                //$product_remain[$key]['total_product'] = System::display_number_report($value['total_product']);
                //$products[$product_id]['total_product'] = System::display_number_report($v['remain_money']);
            }

            $this->map['total_money'] = $total_money;
            $this->map['product_remain'] = $product_remain;
            
            $this->parse_layout('report',$this->map); 
        }
	}	
}
?>