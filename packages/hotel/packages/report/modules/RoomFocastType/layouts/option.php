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
                <span class="report_title">[[.room_focast_type.]]</span>
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
                                    <input  name="by_year" type="checkbox" id="by_year" value="1" onclick="window.location ='?page=room_focast_type&cmd=by_year&portal=<?php echo PORTAL_ID;?>'" />
                                </td>
                            </tr>
                            <tr>
                                <td>[[.by_day.]]</td>
                                <td>
                                    <input  name="by_day" type="checkbox" id="by_day" value="1" onclick="window.location ='?page=room_focast_type&cmd=by_day&portal=<?php echo PORTAL_ID;?>'"/>
                                </td>
                            </tr>
                            </table>
                        </fieldset>
                    </td></tr></table>
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
jQuery(document).ready(function()
{
   jQuery(":checkbox").checked = false; 
})
$('month').value='<?php echo date('m',time());?>';

if(jQuery('#by_day').attr('checked')==false)
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
    if(jQuery('#by_day').attr('checked')==true)
    {
        jQuery('.by-by_day').css('display','block');
    }
    else
    {
        jQuery('.by_day').css('display','none');
    }
}

function check()
{
    if(!jQuery('#by_year').attr('checked') && !jQuery('#by_day').attr('checked'))
    {
        alert('[[.choose_option.]]');
        return false;
    }

}
</script>