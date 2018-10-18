<link rel="stylesheet" href="skins/default/report.css">
<style>
/*full màn hình*/
.simple-layout-middle{width:100%;}
</style>
<script>//full_screen();</script>
<table cellpadding="5" cellspacing="0" width="100%" border="1"  bordercolor="#CCCCCC" style="background-color: #FFFFFF;" class="table-bound">
    <tr>
        <td colspan="22">
            <!--HEADER-->
            <table cellspacing="0" width="100%">
                <tr valign="top">
                    <td align="left" width="65%">
            			<strong>[[|hotel_name|]]</strong><br />
            			[[|hotel_address|]]
                    </td>
                    <td align="right" nowrap width="35%">
            			<strong>[[.template_code.]]</strong><br />
            			<i>[[.promulgation.]]</i>
                    </td>
                </tr>	
            </table>
            <table cellpadding="10" cellspacing="0" width="100%">
                <tr>
                	<td align="center">
                		<b>
                            <span style="font-size: 25px;" >
                            DANH SÁCH PHÒNG CHƯA THANH TOÁN
                            </span>
                        </b>
                    </td>
                </tr>
            </table>
            <br />
            <form name="SearchForm" method="post">
            <table cellpadding="10" cellspacing="0" width="100%" style=" border: 1px solid ;">
                <tr>
                    <td align="center">
                        [[.line_per_page.]]
                        <input name="line_per_page" type="text" id="line_per_page" size="4" maxlength="2" style="text-align:center"/>
                        [[.no_of_page.]]
                        <input name="no_of_page1" type="text" id="no_of_page1" size="4" maxlength="2" style="text-align:center"/>
                        [[.from_page.]]
                        <input name="start_page" type="text" id="start_page" size="4" maxlength="4" style="text-align:center"/>
                        &nbsp;
                        [[.from_date.]] : 
                        <input name="from_date" type="text" id="from_date" onchange="changevalue();" style="width:70px;text-align: center;"/>
                        &nbsp;
                        [[.to_date.]] : 
                        <input name="to_date" type="text" id="to_date" onchange="changefromday();" style="width:70px;text-align: center;"/> 
                        <input type="submit" value="[[.search.]]"/>
                    </td>
                </tr>
            </table>
            </form>
            <!--/HEADER-->
        </td>
    </tr>
<script>
    function changevalue(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('to_date').value =$('from_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('to_date').value =$('from_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('to_date').value =$('from_date').value;
                }
            }
        }
    }
    function changefromday(){
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        if(myfromdate[2] > mytodate[2]){
            $('from_date').value= $('to_date').value;
        }else{
            if(myfromdate[1] > mytodate[1]){
                $('from_date').value = $('to_date').value;
            }else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                    $('from_date').value =$('to_date').value;
                }
            }
        }
    }
</script>
