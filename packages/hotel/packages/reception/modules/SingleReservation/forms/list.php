<?php
class ListSingleReservationForm extends Form
{
	function ListSingleReservationForm()
	{
		Form::Form('ListSingleReservationForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 200;
		$cond = '1 = 1
			'.(Url::get('keyword')?' AND (UPPER(TOUR.NAME) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR UPPER(TOUR.TOUR_LEADER) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR UPPER(CUSTOMER.NAME) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\')':'').'
			';
		$this->map['title'] = Portal::language('tour_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				TOUR
				LEFT OUTER JOIN CUSTOMER ON CUSTOMER.ID = TOUR.COMPANY_ID
				LEFT OUTER JOIN RESERVATION ON RESERVATION.TOUR_ID = TOUR.ID
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array('action'));
		$sql = '
			SELECT * FROM
			(
				SELECT temp.* , ROWNUM AS rownumber FROM
				(
					SELECT
						TOUR.ID,TOUR.ROOM_QUANTITY,TOUR.USER_ID,TOUR.LAST_MODIFIED_USER_ID,TOUR.COMPANY_ID,TOUR.NAME,TOUR.NUM_PEOPLE,TOUR.NOTE,TOUR.CODE,CUSTOMER.NAME AS company_name,
						tour.total_amount,tour.extra_amount,tour.tour_leader,
						to_char(tour.arrival_time,\'DD/MM/YYYY\') as arrival_time,to_char(tour.departure_time,\'DD/MM/YYYY\') as departure_time,
						tour_id,
						'.((Url::get('action')=='select_tour')?' DECODE(RESERVATION.TOUR_ID,null,1,0) AS selected':' 0 AS selected').'					
					FROM
						TOUR 
						LEFT OUTER JOIN CUSTOMER ON CUSTOMER.ID = TOUR.COMPANY_ID
						LEFT OUTER JOIN RESERVATION ON RESERVATION.TOUR_ID = TOUR.ID
					WHERE
						'.$cond.'	
					ORDER BY
						ID DESC
				) temp
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