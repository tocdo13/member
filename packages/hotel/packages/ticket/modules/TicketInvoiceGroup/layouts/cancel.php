<h2>[[.cancel_ticket.]]</h2>
<div style="padding-left: 110px;">
    <form name="CancelTicketForm" method="post">
    <br />
    <br />
    <h3>[[.ticket_name.]]: [[|ticket_name|]]</h3>
    <h3>[[.ticket_quantity.]]: [[|ticket_quantity|]]</h3>
    <h3>[[.user.]]: [[|create_user|]]</h3>
    [[.cacel_reason.]]: <input name="note" type="text" id="note" style="width: 350px;"/>
    <br />
    <div style="float: left; width: 45%;">
        <h4>[[.printed_ticket_list.]]:</h4>
        <table  border="1" >
            <?php 
                //tao so serie
                function get_ticket_code($ticket_code)
                {
            		$code = '';
            		$leng = strlen($ticket_code);
            		for($j=0;$j<7-$leng;$j++)
                    {
            			$code .= '0';	
            		}
            		$code = $code.$ticket_code;
            		return $code;
            	} 
                $j = 1;
                for($i = [[=start_code=]]; $i <= [[=last_code=]]; $i++)
                {
                    if(!DB::fetch('select * from ticket_cancelation where ticket_invoice_id = '.[[=invoice_id=]].' and ticket_serie = '.$i))
                    {
            ?>
            <?php 
                if($j == 1)
                {
            ?>  
                
                        <input type="checkbox" name="check_all" value="check_all" class="print_list" onclick="CheckAll('print_list', this);" />[[.check_all.]]
                   
                   
                <tr>
            <?php 
                }
            ?>
                    
                    <td>
                        <input name="cancel[]" class="print_list" type="checkbox" value="<?php echo $i; ?>" />
                        <?php 
                            echo get_ticket_code($i);
                            $j += 1;
                        ?>
                    </td>
            <?php 
                if($i == [[=last_code=]] and $j < 6)
                {
                    for($j = $j; $j < 6; $j ++)
                    {
            ?>
                    <td>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    </td>
            <?php    
                    }
                }
                if($j == 6)
                {
                    $j = 1;
            ?>   
                <tr>
            <?php 
                }
            ?>
            <?php 
                    }
            ?>
            <?php 
                if($i == [[=last_code=]] and $j < 6 and $j > 1)
                {
                    for($j = $j; $j < 6; $j ++)
                    {
            ?>
                     <td>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    </td>
            <?php    
                    }
                }
                if($j == 6)
                {
                    $j = 1;
            ?>   
                <tr>
            <?php 
                }
            ?>
            <?php
                }
            ?>
        </table>
        <br />
        <input type="submit" value="[[.action.]]" />
        <input type="button" value="[[.exit.]]" onclick="window.parent.location.reload();" />
    </div>
    <div style="float: left; width: 45%; margin-left: 10px;">
        <!--danh sach ve da huy-->
        <h4>[[.canceled_ticket_list.]]:</h4>
        <table style=" width: 100%;" border="1">
            <?php
                $j = 1;
                for($i = [[=start_code=]]; $i <= [[=last_code=]]; $i++)
                {
                    if(DB::fetch('select * from ticket_cancelation where ticket_invoice_id = '.[[=invoice_id=]].' and ticket_serie = '.$i))
                    {
            ?>
            <?php 
                if($j == 1)
                {
            ?>   
                <tr>
            <?php 
                }
            ?>
            
                    <td><?php 
                            echo get_ticket_code($i);
                            $j += 1;
                        ?></td>
            <?php 
                if($i == [[=last_code=]] and $j < 6)
                {
                    for($j = $j; $j < 6; $j ++)
                    {
            ?>
                    <td>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    </td>
            <?php    
                    }
                }
                if($j == 6)
                {
                    $j = 1;
            ?>   
                <tr>
            <?php 
                }
            ?>
            <?php 
                    }
            ?>
            
            <?php 
                if($i == [[=last_code=]] and $j < 6 and $j > 1)
                {
                    for($j = $j; $j < 6; $j ++)
                    {
            ?>
                     <td>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    </td>
            <?php    
                    }
                }
                if($j == 6)
                {
                    $j = 1;
            ?>   
                <tr>
            <?php 
                }
            ?>
            <?php
                }
            ?> 
        </table>
    </div>
</form>
</div>
<script>
    function CheckAll(class_name, obj)
    {
        var checkboxs = document.getElementsByClassName(class_name);
        if(obj.checked == true)
        {
            for(i=0; i<checkboxs.length; i++)
             checkboxs[i].checked = true;
            
        }
        else
        {
            for(i=0; i<checkboxs.length; i++)
            checkboxs[i].checked = false;
        }
    }
</script>