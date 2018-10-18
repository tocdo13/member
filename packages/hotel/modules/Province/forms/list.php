<?php
class ListProvinceForm extends Form
{
    function ListProvinceForm()
    {
        Form::Form('ListProvinceForm');
    }
    
    function on_submit()
    {
        $selected_ids = Url::get('selected_ids');
        if(!empty($selected_ids))
        {
			foreach($selected_ids as $id)
			{
                DB::delete_id( 'province', $id );
			}  
        } 
    }
    function draw()
    {
        $item_per_page = 30;
		$sql = 'SELECT count(*) AS acount FROM province ';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array());

        $sql = '
			SELECT * FROM
			(
				SELECT
					province.*,
					ROW_NUMBER() OVER (ORDER BY province.id) as rownumber
				FROM
					province
				ORDER BY
					province.id
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'	
		';
		$items = DB::fetch_all($sql);
        $this->map['items'] = $items;
        $this->parse_layout('list',$this->map);
    }
}

?>