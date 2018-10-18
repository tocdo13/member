<?php
/*
CREAT BY NGOCUB
*/
/*
	FUNCTION TCPDF USE IN THIS FILE
	void function writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
	void function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=0, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
*/

	require_once('tcpdf.php');
	require_once 'packages/core/includes/utils/tcpdf/config/lang/eng.php';
	class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// Logo
			//$this->Image(K_PATH_IMAGES.'logo_example.jpg', 10, 8, 15);
			$this->SetFont('dejavusans', 'B', '10px');
		// Title
			$this->MultiCell(100, 5, Portal::get_setting('restaurant_name') , 0, 'L',0,0);
			$this->Cell(122);
			$this->MultiCell(40, 5, Portal::language('template_number') , 0, 'L',0,1);
			$this->MultiCell(100, 5, Portal::get_setting('restaurant_address') , 0, 'L',0,0);
			$this->Cell(85);
			$this->SetFont('dejavusans', '', '10px');
			$this->MultiCell(90, 50, Portal::language('template_by') , 0, 'C',0,1);

		}
		// Page footer
		public function Footer() {
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// Set font
			$this->SetFont('dejavusans', 'I', 8);
			// Page number
			$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');
		}
		function pre_data($datas,$titles,&$align=array(),&$currency=array(),&$total=array(),&$total_sum=array())
		{
			$i=0;
			$new_datas = array();
			foreach($titles as $title)
			{
				if(isset($title['text_align']) and $title['text_align']=='auto' and isset($title['currency']))
				{
					$align[$i]='right';
				}elseif(isset($title['text_align']) and $title['text_align']=='center'){
					$align[$i]='center';
				}elseif(!isset($title['text_align']) and isset($title['currency'])){
					$align[$i]='right';
				}elseif(isset($title['is_number'])){
					$align[$i]='right';
				}else{
					$align[$i]='left';
				}
				if(isset($title['currency']) && $title['currency'])
				{
					$currency[$i] = 1;
				}
				if(isset($title['total']) && $title['total']==1)
				{
					$total[$i] = 1;
				}
				$i++;
			}
			$count = count($titles);
			for($j=0;$j<=$count;$j++)
			{
				$total_sum[$j]=0;
			}
			$k=0;
			foreach($datas as $data)
			{
				$i=0;
				foreach($data as $key => $value)
				{
					if(isset($total[$i]) and $total[$i])
					{
						$total_sum[$i] += $value; 
					}
					$i++;
				}
				$new_datas[$k] = $data;
				$k++;
			}
			return $new_datas;
		}
		function creat_table_pdf($datas,$titles,$width,$align,$currency,$item_per_page,&$item_start)
		{
			$i=0;
			$tb = '<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#E7E7E7" align="center">';
			$tb_header ='<thead><tr>';
			foreach($titles as $title)
			{
				$tb_header .= '<th width="'.$width[$i].'%" align="center"><b>'.$title['name'].'</b></th>';
				$i++;
			}
			$tb_header .= '</tr></thead>';
			$tb .= $tb_header;
			$tb_body ='<tbody>';
			if(($item_start+$item_per_page)>count($datas))
			{
				$item_end = count($datas);
			}else{
				$item_end = $item_start+$item_per_page;
			}
			for($i=$item_start;$i<$item_end;$i++)
			{
				$tb_body .='<tr valign="middle">';
				$j=0;
				foreach($datas[$i] as $key=>$value)
				{
					$tb_body .='<td width="'.$width[$j].'%" align="'.$align[$j].'">';
					if($key=='0')
					{
						$value++;
					}
					if(isset($currency[$j]))
					{
						$tb_body .= System::display_number_report($value);
					}else{
						$tb_body .=$value;
					}
					$tb_body.='</td>';
					$j++;
				}
				$tb_body .='</tr>';
			}
			$item_start = $item_end;
			$tb_body .='</tbody>';
			$tb .= $tb_body;
			return $tb;		
		}
	}
	function export_pdf() 
	{		
		$datas = Session::get('report','data_sheet');
		$titles = Session::get('report','title_data_sheet');
		$format_currency = Session::get('report','currency');
		$new_title = array();
		$ex_width = array();
		for($i=0;$i<=count($titles[-1]);$i++)
		{
			$new_title[$i+1] = array(
				'name'=>$titles[-1][$i],
				'width'=>$titles[-2][$i],
				'text_align'=>$titles[-3][$i]==1?'center':'right',
				'total'=>$titles[-4][$i]
				);	
			if($titles[-4][$i])
			{
				$new_title[$i+1]+=array('is_number'=>1);
			}	
			$ex_width[$i+1] = $titles[-2][$i];
			if($format_currency[$i])
			{
				$new_title[$i+1]+=array('currency'=>1);
			}
		}
		$titles = $new_title;
		if(Session::is_set('report','width'))
		{
			$width = Session::get('report','width');
		}
		else
		{
			$width = $ex_width;
		}		
		$report = Session::get('report','report_name');
		$from_date = Session::get('report','from_date');
		$to_date = Session::get('report','to_date');
		$revenue = Session::get('report','revenue_by');
		$positions = Session::get('report','positions');
		$item_per_page = Session::get('report','item_per_page');
		// create new PDF document		
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
		
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->setPageOrientation('L');
		$pdf->SetPrintHeader(true);
//		$pdf->SetAuthor('Ngocub');
//		$pdf->SetTitle('TCPDF Example 006');
//		$pdf->SetSubject('TCPDF Tutorial');
//		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$pdf->SetMargins(5, 25, 5);
		$pdf->setHeaderMargin(5);
//		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		//set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
		
		//set some language-dependent strings
//		$pdf->setLanguageArray($l); 
		
		// ---------------------------------------------------------
		// add a page
		$pdf->AddPage();
		//Body
		$pdf->SetFont('dejavusans', 'B', '20px');
		$pdf->MultiCell(290, 10, $report , 0, 'C',0,1,'','',true,0);
		$pdf->SetFont('dejavusans', 'I', '12px');
		$txt_date = Portal::language('from_date').': '.$from_date.'  '.Portal::language('to_date').': '.$to_date;
		$pdf->MultiCell(290, 5, $txt_date , 0, 'C',0,1,'','',true,0);
		$pdf->SetFont('dejavusans', '', '10px');
		$pdf->MultiCell(290, 5, $revenue , 0, 'C',0,1,'','',true,0);
		$pdf->MultiCell(290, 3, '' , 0, 'C',0,1,'','',true,0);
		$pdf->SetFont('dejavusans', '', 10);
		$i=0;
		$count = count($titles);
		$align=array();
		$currency =array();
		$total =array();
		$total_sum = array();
		$item_start=0;		
		$datas = $pdf->pre_data($datas,$titles,$align,$currency,$total,$total_sum);
		$num_rows =count($datas);		
		while(($num_rows-$item_start)>$item_per_page)
		{
			$tb = $pdf->creat_table_pdf($datas,$titles,$width,$align,$currency,$item_per_page,$item_start);
			$tb.='</table>';
			$pdf->writeHTML($tb, true, false, false, false, '');
			$pdf->AddPage();
		}
		$tb = $pdf->creat_table_pdf($datas,$titles,$width,$align,$currency,$item_per_page,$item_start);
		$total_col = '';
		$check = false;		
		for($j=0;$j<count($width);$j++)
		{
			if($check)
			{
				$total_col .='<td width="'.$width[$j].'%" align="'.$align[$j].'"><b>';
				if(isset($currency[$j]) && $currency[$j] && $total_sum[$j]>0)
				{
					$total_col .= System::display_number_report($total_sum[$j]);
				}
				elseif($total_sum[$j]>0)
				{
					$total_col .= $total_sum[$j];
				}
				$total_col .='&nbsp;</b></td>';
			}
			if(isset($total[$j]) && $total[$j]>0 && !$check)
			{
				$col = $j;
				$total_width =100;
				while($col < count($width))
				{
					$total_width -= $width[$col];
					$col++;
				}
				$total_col.='<td width="'.$total_width.'%" colspan="'.($j).'" align="right"><b>'.Portal::language('total').'</b></td>';
				$total_col .='<td width="'.$width[$j].'%" align="'.$align[$j].'"><b>';
				if(isset($currency[$j]) && $currency[$j] && $total_sum[$j]>0)
				{
					$total_col .= System::display_number_report($total_sum[$j]);
				}
				elseif($total_sum[$j]>0)
				{
					$total_col .= $total_sum[$j];
				}
				$total_col .='&nbsp;</b></td>';
				$check = true;
			}
		}
		$tb_foot ='';
		if($total_col !== '')
		{
			$tb_foot = '<tfoot><tr>';
			$tb_foot .= $total_col;
			$tb_foot .= '</tr></tfoot>';

		}
		$tb .= $tb_foot;
		$tb .= '</table>';
		$pdf->writeHTML($tb, true, false, false, false, '');
		//Footer
		$pdf->SetFont('dejavusans', 'I', '10px');
		$date = Portal::language('day').'......'.Portal::language('month').'......'.Portal::language('year').'......';
		$pdf->MultiCell(500, 5, $date , 0, 'C',0,1,'','',true,0);
		$pdf->MultiCell(500, 3, '' , 0, 'C',0,1,'','',true,0);
		$pdf->SetFont('dejavusans', '', '10px');
		$footer = '<table width="100%"><thead><tr>';
		if(is_array($positions))
		{
			unset($positions['1']);
			foreach($positions as $values)
			{
				$j=0;
				foreach($values as $value)
				{
					$footer .='<td align="center">'.$value.'</td>';
					$j++;
				}
			}
		}
		$footer .='</tr><tr>';
		for($i=0;$i<=$j;$i++)
		{
			$footer .='<td align="center">'.Portal::language('sign_nature').'</td>';
		}
		$footer .= '</tr></thead></table>';
		$pdf->writeHTML($footer, true, false, false, false, '');
		// reset pointer to the last page
		$pdf->lastPage();
		// ---------------------------------------------------------
		//Close and output PDF document
		$pdf->Output('export.pdf', 'D');
	}
?>