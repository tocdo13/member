<style>
    .divnoselect {
        width: 100px; height: 100px; 
        padding: 10px; margin: 10px; 
        color: #00b2f9; font-weight: bold; background: #FFFFFF; 
        border: 1px solid #EEEEEE; 
        text-align: center; 
        border-radius: 5px; 
        float: left;
        border: 3px solid #555;
    }
    .divnoselect:hover {
        color: #FFFFFF; 
        background: #00b2f9; 
        border: none;
    }
</style>
<table style="width: 90%; margin: 10px auto;">
    <tr>
        <td style="text-align: center;"><h1 style="text-transform: uppercase;">[[.select_option_area_vending.]]</h1></td>
    </tr>
</table>
<table style="margin: 0px auto;">
    <tr>
        <td>
            <!--LIST:area-->
            <?php if(User::can_add(false,ANY_CATEGORY)){?>
                <a href="<?php echo Url::build('automatic_vend',array('cmd'=>'add','department_id'=>[[=area.id=]],'department_code'=>[[=area.code=]],'arrival_time'=>date('d/m/Y')));?>">
                    <div class="divnoselect">
                        [[|area.name|]]
                    </div>
                </a>
            <?php }?>
            <!--/LIST:area-->
        </td>
    </tr>
</table>