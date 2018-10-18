<table width="100%" height="100%" bgcolor="#B5AEB5">
    <tr>
        <td>
    	<link rel="stylesheet" href="skins/default/report.css">
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
    	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
    	<p>&nbsp;</p>
    	
    	<table align="center" width="100%">
    	<tr>
    		<td align="center" style="border:1px dotted #CCCCCC;">
        		<p>&nbsp;</p>
        		<font class="report_title">TỔNG HỢP KHÁCH THEO SALER</font>
        		<br><br>
        		<form name="SearchForm" method="post">
            		<table><tr><td>
                		<fieldset>
                    		<table border="0" align="center" id="select_time">
                    			<tr>
                    			  <td>[[.hotel.]]</td>
                        			  <td>
                                        <select name="portal_id" id="portal_id">
                        			    </select></td>
                        			  <td nowrap="nowrap">&nbsp;</td>
                  			    </tr> 
                                
                                    <td>Saler</td>
                                    <td><select name="user_id" id="user_id" style="width:180px;"></select></td>
                   			</table>
                		</fieldset>
            		  </td></tr>
                      
                    </table>
            		  <br />
            		  
                      <table>
            			<tr>
            				<td>[[.line_per_page.]]</td>
            				<td><input name="line_per_page" type="text" id="line_per_page" value="50" size="4" maxlength="2" style="text-align:right"/></td>
            			</tr>
            			<tr>
            				<td>[[.no_of_page.]]</td>
            				<td><input name="total_page" type="text" id="total_page" value="100" size="4" maxlength="2" style="text-align:right"/></td>
            			</tr>
            			<tr>
            				<td>[[.from_page.]]</td>
            				<td><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
            			</tr>
        			</table>
            			
            			<p>
            				<input type="submit" name="do_search" value="  [[.report.]]  "/>
            				<input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';"/>
            			</p>
        			</form>
        		
        		</td></tr></table>
        	</div>
        	</div>
        </td>
    </tr>
</table>
<script type="text/javascript">
	jQuery("#date").datepicker();
</script>