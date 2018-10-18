<?php
class BarAreaForm extends Form{
	function BarAreaForm()
    {
		Form::Form('BarAreaForm');
		$this->add('bar_area.name',new TextType(true,'name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
        require_once 'packages/hotel/includes/php/module.php';
	}
	function on_submit()
    {
		if($this->check())
        {
            if(Url::get('deleted_ids'))
            {
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
                {
					DB::delete('bar_area','id=\''.$id.'\'');
				}
			}
            if(isset($_REQUEST['mi_bar_area']))
            {
                foreach($_REQUEST['mi_bar_area'] as $key=>$value)
                {
                    if($value['id'] AND DB::exists_id("bar_area",$value['id']))
                    {
                        $id_area = $value['id'];
                        unset($value['id']);
                        unset($value['no']);
                        DB::update('bar_area',$value,'id='.$id_area);
                    }
                    else
                    {
                        unset($value['id']);
                        unset($value['no']);
                        DB::insert('bar_area',$value);
                    }
                }
            }
        }
        Url::redirect_current();
	}	
	function draw()
    {
        /** check du lieu lan dau chay module. do la module viet sau va khong thay doi du lieu trk do nen phai co thao tac nay **/
            $first_data_bar_area = DB::fetch_all('
    				SELECT
    					bar_area.*
    				FROM
    					bar_area 
                        inner join bar on bar_area.bar_id=bar.id
                    WHERE 
                        bar.portal_id=\''.PORTAL_ID.'\' 
                    ORDER BY 
                        bar_area.id');
            if(sizeof($first_data_bar_area)==0)
            {/** neu chua co du lieu thi lay du lieu khu tu bang bar_table de insert vao database **/
                $list_area_name = DB::fetch_all("
                                                SELECT DISTINCT
                                                    bar_table.bar_id as id,
                                                    bar_table.table_group as name
                                                FROM
                                                    bar_table
                                                WHERE
                                                    portal_id='".PORTAL_ID."'
                                                ");
                foreach($list_area_name as $key=>$value)
                {
                    $array_first = array();
                    $array_first['bar_id'] = $value['id'];
                    $array_first['name'] = $value['name'];
                    DB::insert('bar_area',$array_first);
                }
            }
        /** end check du lieu la dau **/
        /** ----------------------------------------------------------------------------------------------- **/
        /** lay du lieu dang co trong bar_area de tao module **/
    		if(!isset($_REQUEST['mi_bar_area']))
            {
    			$sql = '
    				SELECT
    					bar_area.*
    				FROM
    					bar_area
                        inner join bar on bar_area.bar_id=bar.id
                    WHERE 
                        bar.portal_id=\''.PORTAL_ID.'\' 
                    ORDER BY 
                        bar_area.id';
    			$bar_areas = DB::fetch_all($sql);
    			$i=1;
    			foreach($bar_areas as $key => $value)
                {
    				$bar_areas[$key]['no'] = $i;
    				$i++;
    			}
    			$_REQUEST['mi_bar_area'] = $bar_areas;
    		}
        /** end lay du lieu tu bar_area **/
        /** ----------------------------------------------------------------------------------------------- **/
        /** lay ra danh sach bar **/
            $list_bar = DB::fetch_all("
                                        SELECT
                                            bar.id,
                                            bar.name
                                        FROM
                                            bar
                                        WHERE
                                            bar.portal_id='".PORTAL_ID."'
                                        ORDER BY
                                            bar.id DESC
                                        ");
            $bar_option = '';
            foreach($list_bar as $id=>$content)
            {
                $bar_option .= '<option value="'.$content['id'].'">'.$content['name'].'</option>';
            }
            $this->map['bar_option'] = $bar_option;
        /** end danh sach bar **/
        
        /** truyen du lieu sang layout **/
        $this->parse_layout('edit',$this->map);
        
	}
}
?>