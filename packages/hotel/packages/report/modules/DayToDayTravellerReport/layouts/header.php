<div id="header_report" style="margin: 5px auto; width: 90%;">
    <table style="width: 100%;">
        <tr>
            <td style="width: 55px; text-align: center;"><div style="border-radius: 50%; width: 50px; height: 50px; overflow: hidden; background: #eee; margin: 10px; border:2px solid #eee; box-shadow: 0px 0px 5px #000;"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 50px; height: auto;" /></div></td>
            <td style="width: 120px; text-align: left;">
                <p style="text-transform: uppercase;"><span style="font-size: 11px; font-weight: bold;"><?php echo HOTEL_NAME; ?></span><br />
                    <i style="font-size: 10px;">[[.report.]]</i>
                </p>
            </td>
            <td style="text-align: center;">
                <h1 style="text-transform: uppercase; font-size: 15px;">[[.traveller_report.]]</h1>
                <i>[[.date_from.]] <?php echo $_REQUEST['date_from']; ?> [[.date_to.]] <?php echo $_REQUEST['date_to']; ?></i>
            </td>
            <td style="width: 175px; text-align: right;">
                <p>[[.hotel.]]: <i style="font-size: 10px;"><?php if($_REQUEST['portal_id']=='#default'){echo 'SUN HOTEL';}elseif($_REQUEST['portal_id']=='#moon'){ echo 'MOON HOTEL'; }else{echo $_REQUEST['portal_id'];} ?></i><br />
                    [[.nationality.]]: <i style="font-size: 10px;"><?php 
                                                                    if($this->map['country_id']!='all'){
                                                                        $country_name = $this->map['country_id'];
                                                                        $sql = "SELECT country.id,country.name_2 From country Where country.id = '$country_name'";
                                                                        $row = DB::fetch_all($sql);
                                                                        foreach($row as $t=>$v){
                                                                            echo $v['name_2'];
                                                                        }
                                                                    }else{
                                                                        echo $this->map['country_id'];
                                                                    }
                                                                     ?></i>
                </p>
            </td>
        </tr>
    </table>
</div>