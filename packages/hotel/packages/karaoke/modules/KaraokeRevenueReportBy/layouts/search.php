<table width="100%" height="100%" bgcolor="#B5AEB5">
    <tr>
        <td>
            <link rel="stylesheet" href="skins/default/report.css">
            <div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
                <div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
                    <table cellpadding="0" cellspacing="0" align="center" width="100%">
                        <tr>
                            <td width="80px">&nbsp;</td>
                            <td align="center">
                                <p>&nbsp;</p>
                        		<font class="report_title">BÁO CÁO DOANH THU NHÀ HÀNG</font>
                        		<br>
                                <form name="SearchForm" method="post">
                                    <?php if(User::can_admin(false,ANY_CATEGORY)){?>
                                        <div style="margin-top:10px;"><label for="portal_id">[[.Hotel.]]:</label> <select name="portal_id" id="portal_id" onchange="get_karaokes();" ></select></div>
                                    <?php }?>
                                    <table>
                                        <tr>
                                            <td align='center' >
                                                <fieldset>
                                                    <legend>[[.type_report.]]</legend>
                                                    <table width="100%">
                                                        <tr>
                                                            <td align="center">
                                                                <input type="radio" value="invoice" name="type_report" class="type_report" checked="true" />
                                                                 [[.by_invoice.]] 
                                                                <input type="radio" value="date" name="type_report" class="type_report" />
                                                                 [[.by_date.]]
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </fieldset>
                                                <fieldset>
                                                    <legend>[[.select_time.]]</legend>
                                                    <table>
                                                        <tr>
                                                            <td width="80">[[.from.]]</td>
                                                            <td>
                                                                <input name="from_date" type="text" id="from_date" style="width:63px">
                                                                &nbsp;
                                                                -
                                                                &nbsp;
                                                                <input name="from_time" type="text" id="from_time" style="width:32px">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>[[.to.]]</td>
                                                            <td>
                                                                <input name="to_date" type="text" id="to_date" style="width:63px">
                                                                &nbsp;
                                                                -
                                                                &nbsp;
                                                                <input name="to_time" type="text" id="to_time" style="width:32px">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </fieldset>
                                                <fieldset>
                                                    <legend>[[.select_karaoke.]]</legend>
                                                    <table>
                                                        <tr>
                                                            <td id="select_karaoke">
                                                                <input name="checked_all" type="checkbox" id="checked_all" onchange="jQuery('.check_box_karaoke').attr('checked',jQuery(this).attr('checked')?true:false);get_category();" > 
                                                                <b><label for="checked_all">[[.select_all_karaoke.]]</label></b>
                                                                <br />
                                                                <!--LIST:karaokes-->
                                                                        <input name="karaoke_id_[[|karaokes.id|]]" type="checkbox" value="[[|karaokes.id|]]" id="karaoke_id_[[|karaokes.id|]]" class="check_box_karaoke" onchange="get_category();"  />
                                                                        &nbsp;
                                                                        <label for="karaoke_id_[[|karaokes.id|]]">[[|karaokes.name|]]</label>
                                                                        <br />
                                                    			 <!--/LIST:karaokes-->
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </fieldset>
                                                <fieldset>
                                                    <legend>[[.select_invisibale_category.]]</legend>
                                                    <table >
                                                        <tr>
                                                            <td valign="top" id="select_cate_other">
                                                                <input name="checked_all" type="checkbox" id="checked_all" onchange="jQuery('.check_box_other').attr('checked',jQuery(this).attr('checked')?true:false);" > 
                                                                <b><label for="checked_all">[[.select_all.]]</label></b>
                                                                <br />
                                                                <!--LIST:categories-->
                                                                        <input name="categorie_[[|categories.id|]]" type="checkbox" value="[[|categories.id|]]" id="categorie_[[|categories.id|]]" class="check_box_other" />
                                                                        &nbsp;
                                                                        <label for="categorie_[[|categories.id|]]">[[|categories.name|]]</label>
                                                                        <br />
                                                    			 <!--/LIST:categories-->
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </fieldset>
                                                <table>
                                        			<tr>
                                        				<td align="right">[[.line_per_page.]]</td>
                                        				<td align="left"><input name="line_per_page" type="text" id="line_per_page" value="30" size="4" maxlength="2" style="text-align:right"/></td>
                                        			</tr>
                                        			<tr>
                                        				<td align="right">[[.no_of_page.]]</td>
                                        				<td align="left"><input  name="no_of_page" type="text" id="no_of_page" value="400" size="4" maxlength="2" style="text-align:right"/></td>
                                        			</tr>
                                        			<tr>
                                        				<td align="right">[[.from_page.]]</td>
                                        				<td align="left"><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
                                        			</tr>
                                    			</table>
                                                <p>
                                    				<input type="submit" name="do_search" value="  [[.report.]]  " id="do_search" onclick=" return check_karaoke();"/>
                                    				<input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';"/>
                                    			</p>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>

<script>
    jQuery('#from_time').mask("99:99");
    jQuery('#to_time').mask("99:99");
    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
    function get_karaokes()
    {
        var portal_id = jQuery("#portal_id").val().replace('#',"");
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                //document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
                var text_reponse = xmlhttp.responseText;
                show_select_karaoke(text_reponse);
                get_category();
            }
        }
        xmlhttp.open("GET","packages/hotel/packages/karaoke/modules/KaraokeRevenueReportBy/db.php?cmd=get_karaokes&portal_id="+portal_id,true);
        xmlhttp.send();
    }
    
    function show_select_karaoke(str_karaokes)
    {
        var objs = jQuery.parseJSON(str_karaokes);
        var txt=document.getElementById("select_karaoke").innerHTML;
        var new_content = "<input name=\"checked_all\" type=\"checkbox\" id=\"checked_all\" onchange=\"jQuery('.check_box_karaoke').attr('checked',jQuery(this).attr('checked')?true:false);get_category();\" >" 
                        +"<b><label for=\"checked_all\">[[.select_all_karaoke.]]</label></b>"
                        +"<br />";                          
        for(var obj in objs)
        {
            new_content +=  "<input name=\"karaoke_id_"+obj+"\" type=\"checkbox\" value=\""+obj+"\" id=\"karaoke_id_"+obj+"\" class=\"check_box_karaoke\" onchange=\"get_category();\" />"
                            +"&nbsp;"
                            +"<label for=\"karaoke_id_"+obj+"\">"+objs[obj]+"</label>"
                            +"<br />";
        }
        document.getElementById("select_karaoke").innerHTML = new_content;
    }
    
    function get_category()
    {
        var inputs = jQuery('table input:checkbox:checked');
        var str_karaokes = '';
        for (var i=0;i<inputs.length;i++)
        { 
            if(inputs[i].className == 'check_box_karaoke')
            {
                var karaoke_id = inputs[i].id.replace('karaoke_id_','');
                str_karaokes += ','+karaoke_id;
            }
        }
        
        str_karaokes = str_karaokes?str_karaokes.substr(1,str_karaokes.length-1):"";
        var portal_id = jQuery("#portal_id").val().replace('#',"");
        
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                //document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
                var text_reponse = xmlhttp.responseText;
                show_select_category(text_reponse);
            }
        }
        xmlhttp.open("GET","packages/hotel/packages/karaoke/modules/KaraokeRevenueReportBy/db.php?cmd=get_categorys&str_karaokes="+str_karaokes+"&portal_id="+portal_id,true);
        xmlhttp.send();
    }
    
    function show_select_category(str_categorys)
    {
        var objs = jQuery.parseJSON(str_categorys);
       //#FOOD
        var txt=document.getElementById("select_cate_other").innerHTML;
        var new_content = "<input name=\"checked_all\" type=\"checkbox\" id=\"checked_all\" onchange=\"jQuery('.check_box_other').attr('checked',jQuery(this).attr('checked')?true:false);\" >" 
                        +"<b><label for=\"checked_all\">[[.select_all_karaoke.]]</label></b>"
                        +"<br />";                          
        for(var obj in objs['categories'])
        {
            new_content +=  "<input name=\"categorie_"+obj+"\" type=\"checkbox\" value=\""+obj+"\" id=\"categorie_"+obj+"\" class=\"check_box_other\" />"
                            +"&nbsp;"
                            +"<label for=\"categorie_"+obj+"\">"+objs['categories'][obj]['NAME']+"</label>"
                            +"<br />";
        }
        document.getElementById("select_cate_other").innerHTML = new_content;
    }
</script>