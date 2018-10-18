<div>
<div style="text-align: center;">[[|titel|]]</div>
<form name="listNationalForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<br />
    <div style="text-align: center;">
            <?php if(Url::get('search_na')=='district')
            {
            ?>
            Thành phố : <select name="city" id="city" style="width:150px;height:20px" tabindex="4"></select>
            <?php 
            }
            ?>
            từ khóa: <input name="national_search" type="text" id="national_search" /><input name="search" id="search" type="submit" value="[[.search.]]"/>
    </div>	
    </table><br />
	<div class="content">
		<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
			<tr bgcolor="#F1F1F1">
			  <th width="50%">[[.code.]]</th>
              <th width="50%" align="left">[[.name.]]</th>
		  </tr>
		  <!--LIST:items-->
			<tr>
			  <td>[[|items.id|]]</td>
			  <td>[[|items.name|]]</td>
			</tr>
		  <!--/LIST:items-->			
		</table>
		<br />
	
	</div>
	
</form>	
</div>