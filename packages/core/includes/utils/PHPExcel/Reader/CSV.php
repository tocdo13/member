<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2012 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Reader
 * @copyright  Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.8, 2012-10-12
 */


/** PHPExcel root directory */
if (!defined('PHPEXCEL_ROOT')) {
	/**
	 * @ignore
	 */
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
	require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}

/**
 * PHPExcel_Reader_CSV
 *
 * @category   PHPExcel
 * @package    PHPExcel_Reader
 * @copyright  Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Reader_CSV implements PHPExcel_Reader_IReader
{
	/**
	 * Input encoding
	 *
	 * @access	private
	 * @var	string
	 */
	private $_inputEncoding	= 'UTF-8';

	/**
	 * Delimiter
	 *
	 * @access	private
	 * @var string
	 */
	private $_delimiter		= ',';

	/**
	 * Enclosure
	 *
	 * @access	private
	 * @var	string
	 */
	private $_enclosure		= '"';

	/**
	 * Line ending
	 *
	 * @access	private
	 * @var	string
	 */
	private $_lineEnding	= PHP_EOL;

	/**
	 * Sheet index to read
	 *
	 * @access	private
	 * @var	int
	 */
	private $_sheetIndex	= 0;

	/**
	 * Load rows contiguously
	 *
	 * @access	private
	 * @var	int
	 */
	private $_contiguous	= false;


	/**
	 * Row counter for loading rows contiguously
	 *
	 * @access	private
	 * @var	int
	 */
	private $_contiguousRow	= -1;

	/**
	 * PHPExcel_Reader_IReadFilter instance
	 *
	 * @access	private
	 * @var	PHPExcel_Reader_IReadFilter
	 */
	private $_readFilter = null;


	/**
	 * Create a new PHPExcel_Reader_CSV
	 */
	public function __construct() {
		$this->_readFilter		= new PHPExcel_Reader_DefaultReadFilter();
	}	//	function __construct()


	/**
	 * Can the current PHPExcel_Reader_IReader read the file?
	 *
	 * @access	public
	 * @param 	string 		$pFileName
	 * @return boolean
	 * @throws Exception
	 */
	public function canRead($pFilename)
	{
		// Check if file exists
		if (!file_exists($pFilename)) {
			throw new Exception("Could not open " . $pFilename . " for reading! File does not exist.");
		}

		return true;
	}	//	function canRead()


	/**
	 * Read filter
	 *
	 * @access	public
	 * @return PHPExcel_Reader_IReadFilter
	 */
	public function getReadFilter() {
		return $this->_readFilter;
	}	//	function getReadFilter()


	/**
	 * Set read filter
	 *
	 * @access	public
	 * @param	PHPExcel_Reader_IReadFilter $pValue
	 */
	public function setReadFilter(PHPExcel_Reader_IReadFilter $pValue) {
		$this->_readFilter = $pValue;
		return $this;
	}	//	function setReadFilter()


	/**
	 * Set input encoding
	 *
	 * @access	public
	 * @param string $pValue Input encoding
	 */
	public function setInputEncoding($pValue = 'UTF-8')
	{
		$this->_inputEncoding = $pValue;
		return $this;
	}	//	function setInputEncoding()


	/**
	 * Get input encoding
	 *
	 * @access	public
	 * @return string
	 */
	public function getInputEncoding()
	{
		return $this->_inputEncoding;
	}	//	function getInputEncoding()


	/**
	 * Return worksheet info (Name, Last Column Letter, Last Column Index, Total Rows, Total Columns)
	 *
	 * @access	public
	 * @param 	string 		$pFilename
	 * @throws	Exception
	 */
	public function listWorksheetInfo($pFilename)
	{
		// Check if file exists
		if (!file_exists($pFilename)) {
			throw new Exception("Could not open " . $pFilename . " for reading! File does not exist.");
		}

		// Open file
		$fileHandle = fopen($pFilename, 'r');
		if ($fileHandle === false) {
			throw new Exception("Could not open file " . $pFilename . " for reading.");
		}

		// Skip BOM, if any
		switch ($this->_inputEncoding) {
			case 'UTF-8':
				fgets($fileHandle, 4) == "\xEF\xBB\xBF" ?
				fseek($fileHandle, 3) : fseek($fileHandle, 0);
				break;
			case 'UTF-16LE':
				fgets($fileHandle, 3) == "\xFF\xFE" ?
				fseek($fileHandle, 2) : fseek($fileHandle, 0);
				break;
			case 'UTF-16BE':
				fgets($fileHandle, 3) == "\xFE\xFF" ?
				fseek($fileHandle, 2) : fseek($fileHandle, 0);
				break;
			case 'UTF-32LE':
				fgets($fileHandle, 5) == "\xFF\xFE\x00\x00" ?
				fseek($fileHandle, 4) : fseek($fileHandle, 0);
				break;
			case 'UTF-32BE':
				fgets($fileHandle, 5) == "\x00\x00\xFE\xFF" ?
				fseek($fileHandle, 4) : fseek($fileHandle, 0);
				break;
			default:
				break;
		}

		$escapeEnclosures = array( "\\" . $this->_enclosure, $this->_enclosure . $this->_enclosure );

		$worksheetInfo = array();
		$worksheetInfo[0]['worksheetName'] = 'Worksheet';
		$worksheetInfo[0]['lastColumnLetter'] = 'A';
		$worksheetInfo[0]['lastColumnIndex'] = 0;
		$worksheetInfo[0]['totalRows'] = 0;
		$worksheetInfo[0]['totalColumns'] = 0;

		// Loop through each line of the file in turn
		while (($rowData = fgetcsv($fileHandle, 0, $this->_delimiter, $this->_enclosure)) !== FALSE) {
			$worksheetInfo[0]['totalRows']++;
			$worksheetInfo[0]['lastColumnIndex'] = max($worksheetInfo[0]['lastColumnIndex'], count($rowData) - 1);
		}

		$worksheetInfo[0]['lastColumnLetter'] = PHPExcel_Cell::stringFromColumnIndex($worksheetInfo[0]['lastColumnIndex']);
		$worksheetInfo[0]['totalColumns'] = $worksheetInfo[0]['lastColumnIndex'] + 1;

		// Close file
		fclose($fileHandle);

		return $worksheetInfo;
	}


	/**
	 * Loads PHPExcel from file
	 *
	 * @access	public
	 * @param 	string 		$pFilename
	 * @return PHPExcel
	 * @throws Exception
	 */
	public function load($pFilename)
	{
		// Create new PHPExcel
		$objPHPExcel = new PHPExcel();

		// Load into this instance
		return $this->loadIntoExisting($pFilename, $objPHPExcel);
	}	//	function load()


	/**
	 * Loads PHPExcel from file into PHPExcel instance
	 *
	 * @access	public
	 * @param 	string 		$pFilename
	 * @param	PHPExcel	$objPHPExcel
	 * @return 	PHPExcel
	 * @throws 	Exception
	 */
	public function loadIntoExisting($pFilename, PHPExcel $objPHPExcel)
	{
		// Check if file exists
		if (!file_exists($pFilename)) {
			throw new Exception("Could not open " . $pFilename . " for reading! File does not exist.");
		}

		// Create new PHPExcel
		while ($objPHPExcel->getSheetCount() <= $this->_sheetIndex) {
			$objPHPExcel->createSheet();
		}
		$sheet = $objPHPExcel->setActiveSheetIndex( $this->_sheetIndex );

		$lineEnding = ini_get('auto_detect_line_endings');
		ini_set('auto_detect_line_endings', true);

		// Open file
		$fileHandle = fopen($pFilename, 'r');
		if ($fileHandle === false) {
			throw new Exception("Could not open file $pFilename for reading.");
		}

		// Skip BOM, if any
		switch ($this->_inputEncoding) {
			case 'UTF-8':
				fgets($fileHandle, 4) == "\xEF\xBB\xBF" ?
					fseek($fileHandle, 3) : fseek($fileHandle, 0);
				break;
			case 'UTF-16LE':
				fgets($fileHandle, 3) == "\xFF\xFE" ?
					fseek($fileHandle, 2) : fseek($fileHandle, 0);
				break;
			case 'UTF-16BE':
				fgets($fileHandle, 3) == "\xFE\xFF" ?
					fseek($fileHandle, 2) : fseek($fileHandle, 0);
				break;
			case 'UTF-32LE':
				fgets($fileHandle, 5) == "\xFF\xFE\x00\x00" ?
					fseek($fileHandle, 4) : fseek($fileHandle, 0);
				break;
			case 'UTF-32BE':
				fgets($fileHandle, 5) == "\x00\x00\xFE\xFF" ?
					fseek($fileHandle, 4) : fseek($fileHandle, 0);
				break;
			default:
				break;
		}

		$escapeEnclosures = array( "\\" . $this->_enclosure,
								   $this->_enclosure . $this->_enclosure
								 );

		// Set our starting row based on whether we're in contiguous mode or not
		$currentRow = 1;
		if ($this->_con