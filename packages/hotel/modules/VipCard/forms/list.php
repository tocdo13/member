<?php
class ListVipCardForm extends Form
{
	function ListVipCardForm()
	{
		Form::Form('ListVipCardForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 20;
		$cond = '1=1
			'.(Url::get('keyword')?' AND (VIP_CARD.CARD_HOLDER LIKE \'%'.Url::sget('keyword').'%\' OR VIP_CARD.CODE LIKE \'%'.Url::sget('keyword').'%\' OR VIP_CARD_TYPE.NAME LIKE \'%'.Url::sget('keyword').'%\')':'').'
			'.(Url::get('join_date')?' AND VIP_CARD.JOIN_DATE = \''.Date_Time::to_orc_date(Url::get('join_date')).'\'':'').'
			';
		$this->map['title'] = Portal::language('VipCard_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				VIP_CARD
				LEFT OUTER JOIN VIP_CARD_TYPE ON VIP_CARD_TYPE.ID = VIP_CARD.CARD_TYPE_ID
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10);
		$sql = '
			SELECT * FROM
			(
				SELECT
					VIP_CARD.ID,VIP_CARD.CARD_HOLDER,VIP_CARD.CODE,VIP_CARD.DISCOUNT_PERCENT,VIP_CARD.DISCOUNT_AMOUNT,TO_CHAR(VIP_CARD.JOIN_DATE,\'DD/MM/YYYY\') AS JOIN_DATE,VIP_CARD_TYPE.NAME AS CARD_TYPE,
					ROWNUM as rownumber
				FROM
					VIP_CARD
					LEFT OUTER JOIN VIP_CARD_TYPE ON VIP_CARD_TYPE.ID = VIP_CARD.CARD_TYPE_ID
				WHERE	
					'.$cond.'	
				ORDER BY
					VIP_CARD.ID DESC
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
		}
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}	
}
?>