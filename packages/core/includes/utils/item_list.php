<?php
	$GLOBALS['arr'] = array();
	function get_parent_path($id)
	{
		$item = DB::fetch('select id,parent_id from item where id = '.$id);
		$parent_id = $item['parent_id'];
		if($parent_id !='' and $parent = DB::fetch('select id,name,category_id from item where id = '.$parent_id))
		{
			$GLOBALS['arr'][] = '&nbsp;<img src="'.Portal::template('core').'/images/icon/uparrow.gif">&nbsp;<b><A onClick="xxx"  HREF="'.Url::build_current(array('category_id'=>$parent['category_id'],'id'=>$parent['id'])).'">'.$parent['name'].'</a></b>';
			$GLOBALS['arr'] += get_parent_path($parent_id);
		}
		return $GLOBALS['arr'];
	}
	function ajax_paging($block_id = false,$totalitem, $itemperpage,$numpageshow=10,$page_name='page_no',$new_page=false,$page_label='Trang:', $itemname='m&#7909;c',$new_id=false)
	{	
		$st = '';
		$totalpage = ceil($totalitem/$itemperpage);
		if ($totalpage<2)
		{
			return;
		}
		$currentpage=ajax_page_no($page_name);
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
		$st .= '<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0">
	<TR><TD>'.$page_label.' 
	';
		//Link den trang truoc
		if($currentpage>1)
		{
			if($new_page==false)
			{
				$st .= '<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params,{\''.$page_name.'\':'.($currentpage-1).'});ITEM.GetContent();return false;"  HREF = "#"><IMG ALT="';
			}
			else
			{
				$st .= '<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params,{\'id\':'.$new_id.',\''.$page_name.'\':'.($currentpage-1).'});ITEM.GetContent();return false;"  HREF = "#" >';
			}
			$st .= Portal::language('previous_page');
			$st .= '" SRC="'.Portal::template('core').'/images/buttons/paging_left_arrow.gif"></A>&nbsp;&nbsp;';
		}
		//Danh sach cac trang
		$st .= '&nbsp;';
		if($startpage>1)
		{
			if($new_page==false)
			{
				$st .= '<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params,{\''.$page_name.'\':'.($currentpage-1).'});ITEM.GetContent();return false;"  HREF= "'.Url::build_all(array($page_name),$page_name.'=1').' ">1</a>&nbsp';
			}
			else
			{
				$st .= '<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params,{\''.$page_name.'\':'.($currentpage-1).'});ITEM.GetContent();return false;"  HREF= "'.Url::build($new_page,array($page_name,'id'=>$new_id),'&amp;'.$page_name.'=1').' ">1</a>&nbsp;';//
			}
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
			if($new_page==false)
			{
				if($i==$currentpage)
				{
					if($i>1)
					{
						$st .='';
					}
					$st .= '<font style="font-weight:bold">'.$i.'</font>';
				}
				else 
				{
					if($i>1)
					{
						$st .='';	
					}
					$st .= '<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params , {\''.$page_name.'\':'.($i).'});ITEM.GetContent();return false;"  HREF= "#">'.$i.'</a>';
				}
			}
			else
			{
				$st .= '<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params,{\'id\':'.$new_id.',\''.$page_name.'\':'.($i).'});ITEM.GetContent();return false;"  HREF= "#">'.$i.'</a>';
			}
		}
		if($i==$totalpage)
		{
			if($new_page==false)
			{
				$st .= '&nbsp;<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params , {\''.$page_name.'\':'.($totalpage).'});ITEM.GetContent();return false;"  HREF= "#">'.$totalpage.'</a>';//
			}
			else
			{
				$st .= '&nbsp;<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params,{\'id\':'.$new_id.',\''.$page_name.'\':'.($totalpage).'});ITEM.GetContent();return false;"  HREF= "#">'.$totalpage.'</a>';//	
			}
		}
		else
		if($i<$totalpage)
		{
			if($new_page==false)
			{
				$st .= '&nbsp;...&nbsp;<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params , {\''.$page_name.'\':'.($totalpage).'});ITEM.GetContent();return false;"  HREF= "#">'.$totalpage.'</a>';//
			}
			else
			{
				$st .= '&nbsp;...&nbsp;<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params,{\'id\':'.$new_id.',\''.$page_name.'\':'.($totalpage).'});ITEM.GetContent();return false;"  HREF= "#">'.$totalpage.'</a>';//
			}
		}
		$st .= '&nbsp;';
		//Trang sau
		if($currentpage<$totalpage)
		{
			if($new_page==false)
			{
				$st .= '&nbsp;&nbsp;<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params , {\''.$page_name.'\':\''.($currentpage+1).'\'});ITEM.GetContent();return false;"  HREF = "#"><IMG ALT="';
			}
			else
			{
				$st .= '&nbsp;&nbsp;<A onClick="ITEM.blockId = '.$block_id.';ITEM.UpdateParam(ITEM.params,{\'id\':'.$new_id.',\''.$page_name.'\':'.($currentpage+1).'});ITEM.GetContent();return false;"  HREF = "#"><IMG ALT="';
			}
			$st .= Portal::language('next_page');
			$st .= '" SRC="'.Portal::template('core').'/images/buttons/paging_right_arrow.gif"></A>';
		}
		$st .= '</TD></TR></TABLE>';
		return $st;
	}
	function ajax_page_no($page_name='page_no')
	{
		if (Url::get($page_name))
		{
			return Url::get($page_name);	
		}
		else
		{
			return 1;
		}
		
	}
?>