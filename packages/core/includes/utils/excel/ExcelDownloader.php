<?php

interface ExcelDownloader {

	public function __construct($rows);
	public function download();
	public function save();
}
?>
