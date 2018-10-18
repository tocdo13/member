<script type="text/javascript">
   jQuery(document).ready(function()
    {   
		jQuery('#batdau_apdung').datepicker({ yearRange: '2008:2020' });
		jQuery('#guest_left').datepicker({ yearRange: '2008:2020' });
		jQuery("#line_per_page,#no_of_page,#start_page,#guest_id_res").keydown(function(event) {
            // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
           (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
           (event.keyCode >= 35 && event.keyCode <= 39)) 
		   {
                 // let it happen, don't do anything
             return;
           }
        else 
		   {
            // Ensure that it is a number and stop the keypress
              if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) 
			   {
                  event.preventDefault(); 
               }   
           }
        });	    
	});
	function isNumber(datacheck)
	{ 
	  var intRegex = /^\d+$/;
      if(intRegex.test(datacheck)) 
	  {
         return true;
      }
	  else
	  { 
	     return false;
	  }

	}
</script>	
	
<table width="100%" bgcolor="#baddf3">
    <form name="SearchForm" method="post">
     <div align="center">
	<tr>
		<td align="center" style="border:1px dotted #CCCCCC;" colspan="12">
		<p>&nbsp;</p>
		<b><font class="report_title">[[.travellerout_report_hung.]]</font></b>
		<br>
        </td>
    </tr>
       <td><p></p></td>
    <tr>
    </tr>
    </div>
     <div align="center">
    <tr align="center"> 
               <td></td>
				<td width="150px" colspan="2">[[.line_per_page.]]</td>
				<td width="30px"><input name="line_per_page" type="text" id="line_per_page" value="32" size="4" maxlength="2" style="text-align:right"/></td>
			    
				<td width="40px">[[.no_of_page.]]</td>
                
				<td width="20px"><input name="no_of_page" type="text" id="no_of_page" value="50" size="4" maxlength="2" style="text-align:right"/></td>
			
			
				<td width="40px">[[.from_page.]]</td>
				<td width="20px"><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
			
			
        
        <td>
        </td>
        <td>
         <input type="submit" name="btn_excel" id="btn_excel" value="Printf_Excel" />
        </td>
        <td>
        </td>
   

    </tr>
    </div>
    <div align="center"> 
    <tr>  
        <td></td>
        <td width="40px"> From :</td>   
		<td width="90px">
            <input name="batdau_apdung" type="text" id="batdau_apdung" readonly="true" />
        </td>
        <td width="30px">To :</td>
        <td width="90px">
           <input name="guest_left" type="text" id="guest_left" readonly="true" />
        </td>
        <td width="30px">Tên :</td>
        <td width="90px">
           <input name="guest_name" type="text" id="guest_name" />
        </td>
        <td width="50px">Mã Res :</td>
        <td width="90px">
           <input name="guest_id_res" type="text" id="guest_id_res"/>
        </td>
         <td width="30px">
           <input type="submit" name="btn_search" id="btn_search" value="Search" />
        </td>
        <td width="30px">
           <input type="submit" name="btn_cancel" id="btn_cancel" value="Cancel" />
        </td>
        <td></td>
     
    </tr>
    </div>   
   
   
    </form>
</table>