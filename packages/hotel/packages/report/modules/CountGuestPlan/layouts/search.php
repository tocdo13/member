<link rel="stylesheet" href="skins/default/report.css"/>

<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	
    <div style="width:100%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
    <div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
    
    	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
        	<tr valign="top">
        		<td align="left" width="90%">
            		<strong><?php echo Portal::get_setting('company_name');?></strong>
                    <br /><?php echo Portal::get_setting('company_address');?>
                </td>
        		<td align="center">
            		<strong>[[.template_code.]]</strong>
                    <br />
            		<i>[[.promulgation.]]</i>
        		</td>
        	</tr>	
    	</table>
	
        <table align="center" width="100%">
            <tr><td align="center" style="border:1px dotted #CCCCCC;">
                <p>&nbsp;</p>
                <span class="report_title">[[.expect_room_revenue_report.]]</span>
                <br/>
                <br/>
                <form name="SearchForm" method="post">
                    <table style="width: 250px;"><tr><td>
                        <fieldset>
                            <legend><strong>[[.report_option.]]</strong></legend>
                            
                            <table border="0" align="center">
                            <tr>
                                <td>[[.by_year.]]</td>
                                <td>
                                    <input  name="by_year" type="checkbox" id="by_year" value="1" onclick="check_only(this);" />
                                </td>
                            </tr>
                            <tr>
                                <td>[[.by_month.]]</td>
                                <td>
                                    <input  name="by_month" type="checkbox" id="by_month" value="1" onclick="check_only(this);"/>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="by-year">[[.current_year.]]</span></td>
                                <td>
                                <select name="year" id="year" style="width:60px;" class="by-year">
                                <?php
                                	for($i=date('Y')+1;$i>=BEGINNING_YEAR;$i--)
                                	{
                                		echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
                                	}
                                ?>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="by-month">[[.month.]]</span></td>
                                <td>
                                    <select  name="month" id="month" style="width:120px;" class="by-month">
                                        <option value="01">[[.january.]]</option>
                                        <option value="02">[[.february.]]</option>
                                        <option value="03">[[.march.]]</option>
                                        <option value="04">[[.april.]]</option>
                                        <option value="05">[[.may.]]</option>
                                        <option value="06">[[.june.]]</option>
                                        <option value="07">[[.july.]]</option>
                                        <option value="08">[[.august.]]</option>
                                        <option value="09">[[.september.]]</option>
                                        <option value="10">[[.november.]]</option>
                                        <option value="11">[[.october.]]</option>
                                        <option value="12">[[.december.]]</option>
                                    </select>
                                </td>
                            </tr>
                            </table>
                        </fieldset>
                    </td></tr></table>
                <p>
    				<input type="submit" name="do_search" value="  [[.report.]]  " onclick="return check();"/>
    				<input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';"/>
    			</p>
                </form>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
        	</td></tr>
        </table>
    </div>
    </div>
</td></tr>
</table>
<script type="text/javascript">
   
$('month').value='<?php echo date('m',time());?>';

if(jQuery('#by_month').attr('checked')==false)
{
    jQuery('#by_year').attr('checked','checked');
}
//jQuery('#by_month').attr('checked',false);
show_option(); 

function check_only(obj)
{
    //bo het cac check box
    jQuery(":checkbox").each(function(){
			this.checked = false;
	});
    //check lai dung doi tuong
    jQuery(obj).attr('checked','checked');
    show_option();
}

function show_option()
{
    if(jQuery('#by_month').attr('checked')==true)
    {
        jQuery('.by-month').css('display','block');
    }
    else
    {
        jQuery('.by-month').css('display','none');
    }
}

function check()
{
    if(!jQuery('#by_year').attr('checked') && !jQuery('#by_month').attr('checked'))
    {
        alert('[[.choose_option.]]');
        return false;
    }

}
</script>