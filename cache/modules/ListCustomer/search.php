<script src="packages/core/includes/js/jquery/datepicker.js"></script>
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
        		<font class="report_title">DANH SÁCH KHÁCH HÀNG</font>
        		<br><br>
        		<form name="SearchForm" method="post">
            		<table><tr><td>
                		<fieldset>
                    		<table border="0" align="center" id="select_time">
                    			<tr>
                    			  <td><?php echo Portal::language('hotel');?></td>
                        			  <td>
                                        <select  name="portal_id" id="portal_id"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	
                        			    </select></td>
                        			  <td nowrap="nowrap">&nbsp;</td>
                  			    </tr> 
                                
                                    <td>Đến ngày</td>
                                    <td><input  name="date" id="date" tabindex="1" / type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>"></td>
                   			</table>
                		</fieldset>
            		  </td></tr></table>
            		  <br />
            		  
                      <table>
            			<tr>
            				<td><?php echo Portal::language('line_per_page');?></td>
            				<td><input  name="line_per_page" id="line_per_page" value="200" size="4" maxlength="20" style="text-align:right"/ type ="text" value="<?php echo String::html_normalize(URL::get('line_per_page'));?>"></td>
            			</tr>
            			<tr>
            				<td><?php echo Portal::language('no_of_page');?></td>
            				<td><input  name="total_page" id="total_page" value="500" size="4" maxlength="2" style="text-align:right"/ type ="text" value="<?php echo String::html_normalize(URL::get('total_page'));?>"></td>
            			</tr>
            			<tr>
            				<td><?php echo Portal::language('from_page');?></td>
            				<td><input  name="start_page" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/ type ="text" value="<?php echo String::html_normalize(URL::get('start_page'));?>"></td>
            			</tr>
        			</table>
            			
            			<p>
            				<input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/>
            				<input type="button" value="  <?php echo Portal::language('cancel');?>  " onclick="location='<?php echo Url::build('home');?>';"/>
            			</p>
        			<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
        		
        		</td></tr></table>
        	</div>
        	</div>
        </td>
    </tr>
</table>
<script type="text/javascript">
	jQuery("#date").datepicker();
</script>