<?php	
    /**
     * Tự động sinh module và category để phân quyền
     * prefix : tiền tố của module
     * code : kết hợp prefix để tạo module name
     * name : sinh ra name của category
     * package_name : tên package để gắn module
     * category_name : tên category cha (vn or english do php design loi~ font ko viet dc TV =)) ), để gắn các category mới tạo ra vào làm con
     */
    function update_module($prefix, $code, $name, $package_name, $category_name=false)
    {
        //code luôn viết hoa
		$code = trim(strtoupper($code));
		require_once 'packages/core/includes/utils/vn_code.php';	
		$module_name = $prefix.str_replace('-','',convert_utf8_to_url_rewrite($code));
		//Lấy module id dể tạo category
        //Neu đã có module
        if($module = DB::fetch('select id,name from module where name = \''.$module_name.'\''))
        {
			$module_id = $module['id'];
		}
        else//Neu chua co thi tao moi module
        {
            //Lay package_id
            $package_id = DB::fetch('Select id from package where name = \''.$package_name.'\' ','id');
			//Tao module
            $module_id = DB::insert('module',array(
                                        			'name'=>$module_name,
                                        			'package_id'=>$package_id,
                                        			'path'=>'packages/hotel/packages/'.$package_name.'/modules/'.$module_name.'/',
                                        			'title_1'=>$module_name
                                                    )
                                    );//25: package warehousing
		}
        
        //Nếu kiểm tra theo name, thì khi edit name lại tạo category mới
		//if(!DB::exists('select id,name_1 from category where upper(name_1) = \''.strtoupper($name).'\''))
        //Chuyen thanh kiem tra theo module id
        //module_id dc lay tu module name, module name sinh ra tu code cua item + prefix
        //=> trong 1 table code la duy nhat => de module name la duy nhat => prefix khong duoc giong nhau giua cac bo phan
        //da ton tai categoy thi update lai ten
        if($row = DB::fetch('select id from category where module_id = '.$module_id.' and type = \'MODERATOR\' and portal_id = \''.PORTAL_ID.'\' '))
        {
            DB::update_id('category',array('name_1'=>$name,'name_2'=>$name),$row['id']);
		}
        else//Neu chua co category
        {
            //lay struct id cua category cha, cac category tao ra se la con cua no
            if(!$category_name)
                $parent_structure_id = ID_ROOT;
            else
            {
                require_once 'packages/core/includes/system/si_database.php';
                $parent_structure_id = structure_id('category',DB::fetch('Select id from category where name_1 = \''.$category_name.'\' or name_2 = \''.$category_name.'\'  ','id'));
            } 
			//Tao category
			DB::insert('category',array(
                        				'name_1'=>$name,
                        				'name_2'=>$name,
                        				'is_visible'=>1,
                        				'type'=>'MODERATOR',
                        				'structure_id'=>si_child('category',$parent_structure_id),
                        				'portal_id'=>PORTAL_ID,
                        				'status'=>'SHOW',
                        				'name_id'=>convert_utf8_to_url_rewrite($name),
                        				'check_privilege'=>1,
                        				//'group_name_1'=>Portal::language('quyen'),//Language nao cung ra Quyen (do php design loi font khong viet dc chu Quyen =)) )
                                        'group_name_1'=>'Quyền',
                        				'group_name_2'=>'Privilege',
                        				'module_id'=>$module_id
                        			)
                        );
        }
		return $module_name;
	}
    
    /**
     * Xoa cac module va category duoc sinh ra boi update_module(cac module nay thuong dung de phan quyen)
     * input : id or code cua item can` xoa
     * table_name : bang can xoa item
     * function se xoa cac module va category lien quan
     */
    function delete_module($table_name,$id = false,$code = false)
    {
        if($id)
        {
            $cond = ' id =  '.$id;
        }
        else
            if($code)
            {
                $cond = ' upper(code) =  \''.trim(strtoupper($code)).'\' ';
            }
        //neu co cond
        if(isset($cond))
        {
            //Neu ton tai ban ghi can xoa
            if($row = DB::fetch('Select * from '.$table_name.' where '.$cond))
            {
                //Neu ban ghi co field module name => co su dung chuc nang generate module + category
                if(isset($row['module_name']) || isset($row['privilege']))
                {
                    $module_name = isset($row['module_name'])?$row['module_name']:$row['privilege'];
                    /* name cua ban ghi = category name, nhung khi edit name ban ghi thi category name chua chac dc edit theo?
                    if(isset($row['name']))
                        $category_name = $row['name'];
                    else
                        if(isset($row['name_1']))
                            $category_name = $row['name_1'];
                        else
                            if(isset($row['name_2']))
                                $category_name = $row['name_2'];
                    */        
                    //dem so mmodule theo ten        
                    $count_module = DB::fetch('Select count(*) as acount from module where name = \''.$module_name.'\' ','acount');
                    if($count_module==1)
                    {
                        //Lay ra id cua module can xoa
                        $module_id = DB::fetch('Select id from module where name = \''.$module_name.'\' ','id');
                        //$cond_category = ' module_id = '.$module_id.' and name_1 = \''.$category_name.'\' and name_2 = \''.$category_name.'\' and type = \'MODERATOR\' and portal_id = \''.PORTAL_ID.'\'';
                        $cond_category = ' module_id = '.$module_id.' and type = \'MODERATOR\' and portal_id = \''.PORTAL_ID.'\' ';
                        //dem so category theo module id
                        $count_category = DB::fetch('Select count(*) as acount from category where '.$cond_category,'acount');
                        if($count_category>0)
                        {
                            //xoa category
                            DB::delete('category',$cond_category);
                        }
                        //xoa module
                        DB::delete_id('module',$module_id);
                    }
                }
                //Xoa ban ghi                
                DB::delete($table_name,$cond);
            }
        }
        
    }
?>