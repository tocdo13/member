<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY TCV Develop Team
******************************/		
	function paging($totalitem, $itemperpage, $numpageshow=10,$smart=false,$page_name='page_no',$params=array(),$page_label='Trang')
	{
		$st = '';
		$new_row=array();
		if($params and is_array($params))
		{
			foreach($params  as $key=>$value)
			{
				if(Url::get($value)!='')
				{
					$new_row[$value]=Url::get($value);
				}				
			}
		}
		$totalpage = ceil($totalitem/$itemperpage);
		if ($totalpage<2)
		{
			return;
		}
		$currentpage=page_no($page_name);
		$currentpage=round($currentpage);
		if($currentpage<=0 ||$currentpage>$totalpage)
		{
			$currentpage=1;
		}
		if($currentpage>($numpageshow/2))
		{
			$startpage = $currentpage-floor($numpageshow/2);
			if($totalpage-$startpage<$numpageshow)
			{
				$startpage=$totalpage-$numpageshow+1;
			}
		}
		else
		{
			$startpage=1;
		}
		if($startpage<1)
		{
			$startpage=1;
		}
		//Trang hien thoi
		$st .= '<div class="paging-bound"><span>'.$page_label.' </span>';
		//Link den trang truoc
		if($currentpage>$startpage)
		{
			$st .= '<A HREF = "'.Url::build_current($new_row+array($page_name=>$currentpage-1),$smart).'" >';
			$st .= Portal::language('previous_page');
			$st .= '</A>&nbsp;&nbsp;';
		}
		//Danh sach cac trang
		$st .= '&nbsp;';
		if($startpage>1)
		{
			$st .= '<A HREF= "'.Url::build_current($new_row+array($page_name=>'1'),$smart).' ">1</a>&nbsp;';
			if($startpage>2)
			{
				$st .= '...&nbsp;';//
			}
		}
		for($i=$startpage; $i<=$startpage+$numpageshow-1&&$i<=$totalpage; $i++)
		{
			if($i!=$startpage)
			{
				$st .= '&nbsp;';//
			}
			if($i==$currentpage)
			{
				if($i>1)
				{
					$st .='';
				}
				$st .= '<font style="font-weight:bold;">'.$i.'</font>';
			}
			else 
			{
				if($i>1)
				{
					$st .='';	
				}
				$st .= '<A HREF= "'.Url::build_current($new_row+array($page_name=>$i),$smart).' ">'.$i.'</a>';
			}
		}
		if($i==$totalpage)
		{
			$st .= '&nbsp;<A HREF= "'.Url::build_current($new_row+array($page_name=>$totalpage),$smart).' ">'.$totalpage.'</a>';//
		}
		else
		if($i<$totalpage)
		{
			$st .= '&nbsp;...&nbsp;<A HREF= "'.Url::build_current($new_row+array($page_name=>$totalpage),$smart).' ">'.$totalpage.'</a>';//
		}
		$st .= '&nbsp;';
		//Trang sau
		if($currentpage<$totalpage)
		{
			$st .= '&nbsp;&nbsp;<A HREF = "'.Url::build_current($new_row+array($page_name=>$currentpage+1),$smart).'">';
			$st .= Portal::language('next_page');
			$st .= '</A>';
		}
		$st .= '</div>';
		return $st;
	}
	function page_ajax($totalitem,$itemperpage,$reference = '',$numpageshow = 7,$page_name = 'page_no',$page_label = 'Trang')
	{
		$ref = '';
		if($reference)
		{
			if(is_array($reference))
			{
				foreach($reference as $key=>$value)
				{
					$ref .= '&'.$key.'='.$value;
				}
			}else
			{
				$ref = '&'.$reference;
			}
		}
		$st = '';
		$totalpage = ceil($totalitem/$itemperpage);
		if ($totalpage<2)
		{
			return $st;
		}
		$st .= '<div class="page-ajax-bound">';
		$currentpage=page_no($page_name);
		if($currentpage<=0 ||$currentpage>$totalpage)
		{
			$currentpage=1;
		}
		$st .= '<span class="page-ajax-label">'.$page_label.' </span>';	
	
		$startpage = $currentpage - floor($numpageshow/2);
		if($startpage < 1) 
		{
			$startpage  = 1;
		}
		$endpage = $startpage+ $numpageshow-1;
		if($endpage > $totalpage)
		{
			$endpage = $totalpage;
			if(($endpage -$numpageshow) > 1)
			{
				$startpage = $endpage -$numpageshow+1;				
			}
		}
		if($startpage == 2){ $startpage = 1; }
		if($endpage == ($totalpage-1)){ $endpage = $totalpage; }
		if($currentpage > $startpage)
		{
			$st.= '<img alt="'.Portal::language('preview_page').'" class="page-ajax-preview-img" onclick=\'load_ajax("page_no='.($currentpage-1).$ref.'",'.Module::$current->data['id'].')\' src="'.Portal::template('core').'/images/paging_left_arrow.gif" />';
		}
		if($startpage > 2)
		{
			$st.= '<span id="1" onclick=\'load_ajax("page_no=1'.$ref.'",'.Module::$current->data['id'].')\' class="page-ajax-active">1....</span>';
		}
		for($i = $startpage; $i<= $endpage; $i++)
		{
			if($i==$currentpage)
			{
				
				$st.= '<span id="'.$i.'" onclick=\'load_ajax("page_no='.$i.$ref.'",'.Module::$current->data['id'].')\' class="page-ajax-active">'.$i.'</span>';
			}else
			{
				$st.= '<span id="'.$i.'" onclick=\'load_ajax("page_no='.$i.$ref.'",'.Module::$current->data['id'].')\' class="page-ajax-normal">'.$i.'</span>';
			}
		}
		if($endpage < ($totalpage - 1))
		{
			$st.= '<span id="'.$totalpage.'" onclick=\'load_ajax("page_no='.$totalpage.$ref.'",'.Module::$current->data['id'].')\' class="page-ajax-active">....'.$totalpage.'</span>';
		}
		if($currentpage < $endpage)
		{
			$st.= '<img alt="'.Portal::language('next_page').'" class="page-ajax-next-img" onclick=\'load_ajax("page_no='.($currentpage+1).$ref.'",'.Module::$current->data['id'].')\' src="'.Portal::template('core').'/images/paging_right_arrow.gif" />';
		}
		$st.='</div>';
		return $st;
	}
	function page_no($page_name='page_no')
	{
		if(Url::get($page_name) and Url::get($page_name)>0)
		{
			return intval(Url::get($page_name));	
		}
        else
		{
			return 1;
		}
		
	}
?>