<?php
// include package
include 'Writer.php';
include 'ExcelDownloader.php';
class IssueList implements ExcelDownloader {

	private $__rows;
	private $__columns;
	private $__sheet_name;
	private $__file_name;
	private $__excel;
	private $__sheet;

	public function __construct($rows) {
		$this->__rows = $rows;
	}

	public function setFileName($file_name) {
		$this->__file_name = $file_name;
	}

	public function setSheetName($sheet_name){
		$this->__sheet_name = $sheet_name;
	}

	public function download() {
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=$this->__file_name");
		header("Content-Transfer-Encoding: binary ");
		echo @file_get_contents($this->__file_name);
		@unlink($this->__file_name);
		$this->deleteSession();
	}

	public function save() {
		// create empty file
		$this->__excel = new Spreadsheet_Excel_Writer($this->__file_name);
		$this->__excel->setVersion(8, 'utf-8');
		
		$total_record = Session::get('report','total_record');
		$item_per_page = Session::get('report','item_per_page');
		$data_sheet = Session::get('report','data_sheet');

		// $title[-1] : title of sheet
		// $title[-2] : format width column of sheet
		// $title[-3] : format text center or left of cell
		// $title[-4] : column need total
		$title = Session::get('report','title_data_sheet');
		$total_field = count($title[-1]);
		
		// set sheet name
		if($item_per_page >= $total_record){
			$sheet_name = $this->__excel->addWorksheet('sheet1');
			$sheet_name->setInputEncoding('utf-8');
			
			// set header
			$this->setHeader($sheet_name, $total_field);
			
			// data sheet
			unset($title[-2]);
			unset($title[-3]);
			unset($title[-4]);
			$data_sheet = $title+$data_sheet;
			$data_sheet = $this->ExportExcelArray($data_sheet);
			$this->dataSheet($sheet_name,$data_sheet);
			
			// set footer
			// set column and row format
			$this->setColumnAndRow($sheet_name);
		}else{
			$total_page = ceil($total_record/$item_per_page);
			$data_sheet = $this->ExportExcelArray($data_sheet);
			unset($title[-2]);
			unset($title[-3]);
			unset($title[-4]);
			for($i=1;$i<=$total_page;$i++){
				$sheet_name = 'sheet'.$i;
				$$sheet_name = $this->__excel->addWorksheet('Sheet'.$i);
				$$sheet_name->setInputEncoding('utf-8');
				
				// set header
				$this->setHeader($$sheet_name, $total_field);
			
				// data sheet
				$data = array_slice($data_sheet, ($i-1)*$item_per_page, $item_per_page);
				$data = $title+$data;
				$data = $this->ExportExcelArray($data);
				$this->dataSheet($$sheet_name,$data);
				
				// set column and row format
				$this->setColumnAndRow($$sheet_name);
			}
		}
		// save file to disk
		return $this->__excel->close();
	}
	function setHeader($sheet_name, $total_field){
		$title = Session::get('report','title_data_sheet');
		$total_field = count($title[-1]);
		
		// restaurant_name
		$format_restaurant_name = $this->__getRestaurantNamFormat();
		$restaurant_info = Portal::get_setting('restaurant_name')."\n";
		$restaurant_info .= Portal::get_setting('restaurant_address');
		$sheet_name->write(0, 0, $restaurant_info, $format_restaurant_name);
		$sheet_name->setMerge(0, 0, 0, ceil(($total_field-1)/2));
		
		// template_number
		$format_template_number = $this->__getTemplateNumberFormat();
		$template_number = Portal::language('template_number')."\n";
		$template_number .= Portal::language('template_by')."\n";
		$sheet_name->write(0, ceil(($total_field-1)/2)+1, $template_number,$format_template_number);
		$sheet_name->setMerge(0, ceil(($total_field-1)/2)+1, 0, $total_field-1);		
		// revenue_report
		$format_revenue_report = $this->__getRevenueReportFormat();
		$sheet_name->write(1, 0, Session::get('report','report_name'), $format_revenue_report);
		$sheet_name->setMerge(1, 0, 1, $total_field-1);		
		// area report data
		$format_report_data = $this->__getReportDataFormat();
		$sheet_name->write(2, 0, Portal::language('from_date').' '.Session::get('report','from_date').'  '.Portal::language('to_date').' '.Session::get('report','to_date'), $format_report_data);
		$sheet_name->setMerge(2, 0, 2, $total_field-1);		

		// revenue_by
		$format_revenue_by = $this->__getRevenueByFormat();
		if(Session::is_set('report','revenue_by'))
		{
			$revenue_by = Session::get('report','revenue_by');
		}
		else
		{
			$revenue_by = '';
		}
		$text_revenue_by = '';
		$row3 = 20;		
		if(is_array($revenue_by)){
			for($i=0;$i<count($revenue_by);$i++){
				$row3 = $row3+10;
				$text_revenue_by .= $revenue_by[$i]."\n";
			}
			$sheet_name->write(3, 0, $text_revenue_by,$format_revenue_by);
			$sheet_name->setRow(3, $row3); // row 3
		}else{
			$sheet_name->write(3, 0, $revenue_by,$format_revenue_by);
			$sheet_name->setRow(3, 20); // row 3
		}
		$sheet_name->setMerge(3, 0, 3, $total_field-1);
		//currency_default
		$format_currency_default = $this->__getReportCurrencyFormat();
		$sheet_name->write(4, 0,Portal::language('currency_id').': '.Portal::get_setting('restaurant_currency'), $format_currency_default);
		$sheet_name->setMerge(4, 0, 4, $total_field-1);
	}
	function dataSheet($sheet_name,$data_sheet){
		$format_first_row = $this->__getFirstRowFormat();
		$format_cell = $this->__getCellFormat();
		$format_center = $this->__getCenterFormat();
		$format_currency = $this->__getCurrencyFormat();
		
		$title = Session::get('report','title_data_sheet');
		$total_field = count($title[-1]);
		$rowCount = $rowFirstData = 5;
		$total_amount = 0;
		$total = array();
		if(Session::is_set('report','currency')){
			$currency = Session::get('report','currency');
		}else{
			$currency = array();
		}
		//System::debug($data_sheet); exit();
		foreach ($data_sheet as $key=>$value) {
			if($rowCount!=5){
				$total_amount += $value[6];
				$sheet_name->setRow($rowCount, 15);
			}
			for($colCount=0; $colCount < count($value); $colCount++) {
				if ($rowCount == 5) {
					$format = 'format_first_row';
				} else {
					if($title[-3][$colCount]){
						$format = 'format_center';
					}else{
						if($currency[$colCount]){
							$format = 'format_currency';
						}else{
							$format = 'format_cell';
						}
					}
					if($title[-4][$colCount]){
						$total[$colCount] += $value[$colCount]; // tinh tong tung cot lay mau theo $title[-4]
					}
				}
				if($colCount==0 and $rowCount!=5){
					$sheet_name->write($rowCount, $colCount, $value[$colCount]+1, $$format); // function in Worksheet.php
				}else{
					$sheet_name->write($rowCount, $colCount, $value[$colCount], $$format); // function in Worksheet.php
				}
			}	// for
			$rowCount++;
		}	// end foreach
		// total amount
		$first = key($total);
		if($total){
			if(Session::is_set('report','quantity')){
				$format_total_amount = $this->__getTotalQuantity();
			}else{
				$format_total_amount = $this->__getRestaurantTotalAmount();
			}
			$sheet_name->write($rowCount, $first-1, Portal::language('total'), $format_total_amount);
			$sheet_name->setRow($rowCount, 25);
			foreach($total as $col=>$v){
				$firstRowCoordinates = $rowFirstData+1;
				$endRowCoordinates = $rowCount-1;
				$columnCoordinates = $col;
				$firstCoordinates = Spreadsheet_Excel_Writer::rowcolToCell($firstRowCoordinates,$columnCoordinates); // toa do chuan excel tren bang tinh
				$endCoordinates = Spreadsheet_Excel_Writer::rowcolToCell($endRowCoordinates,$columnCoordinates);
				
				// tinh tong cac o tu $firstCoordinates toi $endCoordinates
				$sheet_name->writeFormula($rowCount, $col, "=SUM(".$firstCoordinates.":".$endCoordinates.")", $format_total_amount);
			}
		}
		
		// set footer
		$this->setFooter($sheet_name, $rowCount, $total_field);
	}
	function setFooter($sheet_name, $rowCount, $total_field){
				
		// day mon year
		$format_report_day = $this->__getReportDayFormat();
		$sheet_name->write($rowCount+1, 0, Portal::language('day_month_year'), $format_report_day);
		$sheet_name->setMerge($rowCount+1, 0, $rowCount+1, $total_field-1);
		$sheet_name->setRow($rowCount+1, 30); // row format
		
		// positions
		if(Session::is_set('report','positions')){
			$format_positions = $this->__getPositionsFormat();
			$format_signature = $this->__getSignatureFormat();
			$positions = Session::get('report','positions');
			$pos_name = $positions[0];
			$pos = $positions[1];
			$pos_next = 0;
			for($i=0;$i<count($pos_name);$i++){
				$sheet_name->write($rowCount+2, $pos[$i], $pos_name[$i], $format_positions);
				$sheet_name->write($rowCount+3, $pos[$i], Portal::language('sign_nature'), $format_signature);
				$pos_next++;
				if($i==(count($pos_name)-1)){
					$sheet_name->setMerge($rowCount+2, $pos[$i], $rowCount+2, $total_field-1);
					$sheet_name->setMerge($rowCount+3, $pos[$i], $rowCount+3, $total_field-1);
				}else{
					$sheet_name->setMerge($rowCount+2, $pos[$i], $rowCount+2, ($pos[$pos_next]-1));
					$sheet_name->setMerge($rowCount+3, $pos[$i], $rowCount+3, ($pos[$pos_next]-1));
				}
			}
			$sheet_name->setRow($rowCount+2, 30); // row format
			$sheet_name->setRow($rowCount+3, 90); // row format
		}
	}
	function setColumnAndRow($sheet_name){
		// set width column
		$title = Session::get('report','title_data_sheet');
		$total_field = count($title[-1]);
		unset($title[-1]);
		unset($title[-3]);
		unset($title[-4]);
		foreach($title as $key=>$value){
			for($i=0;$i<count($value);$i++){
				$sheet_name->setColumn(0, $i, $value[$i]);
			}
		}
		// set height row
		$sheet_name->setRow(0, 50); // row 0
		$sheet_name->setRow(1, 25); // row 1
		$sheet_name->setRow(5, 20); // row 4
	}
	private function __getRestaurantTotalAmount(){
		$totalAmount = $this->__excel->addFormat();
		$totalAmount->setBold();
		$totalAmount->setAlign('right');
		$totalAmount->setVAlign('bottom');
		$totalAmount->setTextWrap();
		$totalAmount->setNumFormat ('#,##0.00');
		return $totalAmount;
	}
	private function __getTotalQuantity(){
		$totalQuantity = $this->__excel->addFormat();
		$totalQuantity->setBold();
		$totalQuantity->setAlign('right');
		$totalQuantity->setVAlign('bottom');
		$totalQuantity->setTextWrap();
		return $totalQuantity;
	}
	private function __getRestaurantNamFormat(){
		$restaurantName = $this->__excel->addFormat();
		$restaurantName->setBold();
		$restaurantName->setAlign('top');
		$restaurantName->setTextWrap();		
		return $restaurantName;
	}
	private function __getTemplateNumberFormat(){
		$templateNumber = $this->__excel->addFormat();
		$templateNumber->setBold();
		$templateNumber->setTextWrap();
		$templateNumber->setAlign('center');
		$templateNumber->setAlign('top');
		return $templateNumber;
	}
	private function __getRevenueReportFormat(){
		$revenueReport = $this->__excel->addFormat();
		$revenueReport->setBold();
		$revenueReport->setTextWrap();
		$revenueReport->setAlign('center');
		$revenueReport->setAlign('top');
		$revenueReport->setSize(20);
		return $revenueReport;
	}
	function __getReportCurrencyFormat()
	{
		$revenueReport = $this->__excel->addFormat();
		$revenueReport->setBold();
		$revenueReport->setTextWrap();
		$revenueReport->setAlign('right');
		$revenueReport->setAlign('top');
		return $revenueReport;
	}
	private function __getReportDataFormat(){
		$reportData = $this->__excel->addFormat();
		$reportData->setItalic();
		$reportData->setTextWrap();
		$reportData->setAlign('center');
		$reportData->setAlign('top');
		return $reportData;
	}
	private function __getReportDayFormat(){
		$reportDay = $this->__excel->addFormat();
		$reportDay->setItalic();
		$reportDay->setTextWrap();
		$reportDay->setAlign('right');
		$reportDay->setAlign('bottom');
		return $reportDay;
	}
	private function __getRevenueByFormat(){
		$revenueBy = $this->__excel->addFormat();
		$revenueBy->setTextWrap();
		$revenueBy->setAlign('center');
		$revenueBy->setAlign('top');
		$revenueBy->setVAlign('vcenter');
		return $revenueBy;
	}
	private function __getPositionsFormat() {
		$positions = $this->__excel->addFormat();
		$positions->setBold();
		$positions->setTextWrap();
		$positions->setAlign('center');
		$positions->setAlign('bottom');
		return $positions;
	}
	private function __getSignatureFormat() {
		$signature = $this->__excel->addFormat();
		$signature->setTextWrap();
		$signature->setAlign('center');
		$signature->setAlign('top');
		return $signature;
	}
	private function __getFirstRowFormat() {
		// create format for header row 
		$firstRow = $this->__excel->addFormat();
		$firstRow->setBold();
		$firstRow->setColor('blue');
		$firstRow->setBottom(2);
		$firstRow->setBottomColor('black');
		$firstRow->setBorder(1);
		$firstRow->setAlign('center');
		$firstRow->setVAlign('vcenter');
		$firstRow->setFgColor('#FFAF59');
		return $firstRow;
	}
	private function __getCellFormat() {
		$cell = $this->__excel->addFormat();
		$cell->setBorder(1);
		return $cell;
	}
	private function __getCurrencyFormat() {
		$currency = $this->__excel->addFormat();
		$currency->setBorder(1);
		$currency->setNumFormat('#,##0.00');
		return $currency;
	}
	private function __getCenterFormat() {
		$center = $this->__excel->addFormat();
		$center->setBorder(1);
		$center->setAlign('center');
		return $center;
	}
	function ExportExcelArray($array){
		$data = array();
		$k = 0;
		$total_field = count(end($array));
		foreach($array as $key=>$value){
			for($i=0;$i<$total_field;$i++){
				$first = array_shift($value);
				$data[$k][] = $first;
			}
			$k++;
		}
		return $data;
	}
	function deleteSession()
	{
		Session::delete('report');		
	}
}
?>
