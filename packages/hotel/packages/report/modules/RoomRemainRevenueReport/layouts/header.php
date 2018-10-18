<link rel="stylesheet" href="skins/default/report.css">
<style>
.date_date{
    display: none;
}
/*full màn hình*/
.simple-layout-middle{width:100%;}
@media print
{
    .search{display: none;}
    .date_date{display: block;}
}
</style>
<script>//full_screen();</script>
<table id="tblExport" cellpadding="5" cellspacing="0" width="100%" border="1"  bordercolor="#CCCCCC" style="background-color: #FFFFFF;" class="table-bound">
    <tr id="search">
        <td>
            <div class="search" >
            <!--HEADER-->
            <table cellspacing="0" width="100%">
                <tr valign="top">
                    <td align="left" width="65%">
            			<strong>[[|hotel_name|]]</strong><br />
            			[[|hotel_address|]]
                    </td>
                    <td align="right" nowrap width="35%">
            			<strong>[[.template_code.]]</strong><br />
            			<i>[[.promulgation.]]</i><br />
                        [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo User::id();?>
                    </td>
                </tr>
            </table>
            <table cellpadding="10" cellspacing="0" width="100%">
                <tr>
                	<td align="center">
                		<b>
                            <span style="font-size: 25px;" >
                            [[.unpaid_room_list.]]
                            </span>
                        </b>
                    </td>
                </tr>
            </table>
            
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
                        <input name="to_date" type="text" id="to_date" onchange="changefromday();" style="width:70px;text-align: center;"/>              &nbsp;  
                        [[.hotel.]]  :
                        <select name="portal_id" id="portal_id"></select>
                        <input type="submit" value="[[.search.]]"/>
                        <button id="export">[[.export.]]</button>
                    </td>
                </tr>
            </table>
            </form>
            <div style="text-align: center;" class="date_date">
                [[.from_date.]] :<?php if(isset($_POST['from_date'])){echo $_POST['from_date'];}else{echo date('d/m/Y');}?> [[.to_date.]] : <?php if(isset($_POST['to_date'])){echo $_POST['to_date'];}else{echo date('d/m/Y');}?>
            </div>
            </div>
            <!--/HEADER-->
        </td>
    </tr>
<script>
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
</script>
