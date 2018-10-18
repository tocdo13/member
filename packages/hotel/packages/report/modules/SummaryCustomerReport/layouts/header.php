<table id="tblExport" style="width: 100%;">
    <tr>
        <td>
        
<div id="header_report" style="margin: 5px auto; width: 90%;">
    <table style="width: 100%;">
        <tr>
            <td style="width: 55px; text-align: center;" class="class_none"><div style="border-radius: 50%; width: 50px; height: 50px; overflow: hidden; background: #eee; margin: 10px; border:2px solid #eee; box-shadow: 0px 0px 5px #000;"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 50px; height: auto;" /></div></td>
            <td style="width: 120px; text-align: left;" class="class_none">
                <p style="text-transform: uppercase;"><span style="font-size: 11px; font-weight: bold;"><?php echo HOTEL_NAME; ?></span><br />
                    <i style="font-size: 10px;">[[.room_sale.]]</i>
                </p>
            </td>
            <td style="text-align: center;">
                <h1 style="text-transform: uppercase; font-size: 15px;">[[.summary_customer_report.]]</h1>
                <i>[[.date_from.]] <?php echo $_REQUEST['date_from']; ?> [[.date_to.]] <?php echo $_REQUEST['date_to']; ?></i>
            </td>
            <td style="width: 175px; text-align: right;" class="class_none">
                <p>[[.saler.]]: <i style="font-size: 10px;">[[.all.]]</i>
                    <br />
                    [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                    <br />
                    [[.user_print.]]:<?php echo ' '.User::id();?>
                </p>
                
            </td>
        </tr>
    </table>
</div>