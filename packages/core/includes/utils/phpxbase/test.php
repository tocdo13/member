<?php
/**
* ----------------------------------------------------------------
*			XBase
*			test.php	
* 
*  Developer        : Erwin Kooi
*  released at      : Nov 2005
*  last modified by : Erwin Kooi
*  date modified    : Jan 2005
*                                                               
*  Info? Mail to info@cyane.nl
* 
* --------------------------------------------------------------
*
* Basic demonstration
* download the sample tables from:
* http://www.cyane.nl/phpxbase.zip
*
**/

	/* load the required classes */
	require_once "Column.class.php";
	require_once "Record.class.php";
	require_once "Table.class.php";
	
	/* create a table object and open it */
	$table = new XBaseTable("test/cdrdata.dbf");
	$table->open();


    /* html output */
    echo "<br /><table border=1 style=border-collapse:collapse cellpadding=5>";
    
    /* print column names */
    echo "<tr>";
    foreach ($table->getColumns() as $i=>$c) {
	    echo "<td>".$c->getName()."</td>";
    }
    echo "</tr>";
    
    /* print records */
    while ($record=$table->nextRecord()) {
	    echo "<tr>";
	    foreach ($table->getColumns() as $i=>$c) {
		    echo "<td>".$record->getString($c)."</td>";
	    }
	    echo "</tr>";
    }
	echo "</table>";

	/* close the table */
	$table->close();
?>