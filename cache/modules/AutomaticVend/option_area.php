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
        <td style="text-align: center;"><h1 style="text-transform: uppercase;"><?php echo Portal::language('select_option_area_vending');?></h1></td>
    </tr>
</table>
<table style="margin: 0px auto;">
    <tr>
        <td>
            <?php if(isset($this->map['area']) and is_array($this->map['area'])){ foreach($this->map['area'] as $key1=>&$item1){if($key1!='current'){$this->map['area']['current'] = &$item1;?>
            <?php if(User::can_add(false,ANY_CATEGORY)){?>
                <a href="<?php echo Url::build('automatic_vend',array('cmd'=>'add','department_id'=>$this->map['area']['current']['id'],'department_code'=>$this->map['area']['current']['code'],'arrival_time'=>date('d/m/Y')));?>">
                    <div class="divnoselect">
                        <?php echo $this->map['area']['current']['name'];?>
                    </div>
                </a>
            <?php }?>
            <?php }}unset($this->map['area']['current']);} ?>
        </td>
    </tr>
</table>