<?php
function contentEmail($itemSend)
    {
        ob_start();
        ?>
        <div  style="margin: 0 auto; border: 1px solid #00b2f9; padding: 5px;">
            <table cellSpacing=0 cellPadding=0 border=0 width="100%" id="invoice">
            	<tr>
            		<td align="center">
                		<table cellpadding="0" width="100%" border="0">
                            <tr>
                            	<td>
                                    <br><br>
                                </td>
                            </tr>
                    		<tr>
                    			<td width="1%" align="center" valign="top"><img src="<?php if(defined('ROOT_PATH_EMAIL')) echo ROOT_PATH_EMAIL.HOTEL_LOGO; else echo $_SERVER['DOCUMENT_ROOT'].Url::$root.HOTEL_LOGO;?>" width="200px;" alt="logo" /></td>
                    		  	<?php //if(defined('ROOT_PATH_EMAIL')) echo ROOT_PATH_EMAIL.HOTEL_LOGO.'<br >'.$_SERVER['DOCUMENT_ROOT'].Url::$root.HOTEL_LOGO;//die();?>
                                  <td align="center">
                    				<div class="invoice-title">GUEST'S FOLIO</div>
                    				<div class="invoice-sub-title"></div>
                    				<div class="invoice-contact-info"><?php echo HOTEL_ADDRESS;?></div>
                    				<div class="invoice-contact-info">Tel: <?php echo HOTEL_PHONE;?> | Fax: <?php echo HOTEL_FAX;?></div>
                    				<div class="invoice-contact-info">Email: <?php echo HOTEL_EMAIL;?> | Website: <?php echo HOTEL_WEBSITE;?></div>
                    				<div></div>
                    		  	</td>
                                  <td width="100px;"></td>
                    		</tr>
                		</table><br />
                		<table cellpadding="0" width="100%" border="0" class="genneral-info-table">
                			<tr>
                			  <td width="50%"><div class="item-body" style="float:left; width:100%;" style="float:left; width:100%;">Booking code:<?php echo $itemSend['booking_code']; ?> / Ref_No:</font> <font style="font-size:18px;font-weight:200"><?php echo $itemSend['bill_number']; ?></font></div></td>
                			  <td>Folio No.<?php echo $itemSend['folio_id']; ?>/  Re_code: <?php echo $itemSend['reservation_id']; ?></td>
                		  </tr>
                		</table>
            		<table cellpadding="0" width="100%" border="0" class="genneral-info-table">
            			<tr>
            				<td width="50%"><div class="item-body" style="float:left; width:100%;" style="float:left; width:100%;">Guest's name:&nbsp;<?php echo $itemSend['full_name']; ?></div></td>
            				<td>Room No.<?php echo $itemSend['room_name'] ?></td>
            			</tr>
                        
                        <tr>
                			  <td width="50%"><div class="item-body" style="float:left; width:100%;" style="float:left; width:100%;">Company name:<?php echo $itemSend['customer_name']; ?></div></td>
                              <td></td>
                		</tr>
            			<tr>
            			  <td><div class="item-body" style="float:left; width:100%;" style="float:left; width:100%;">Address:<?php echo $itemSend['address']; ?></div></td>
                          <td><div class="item-body" style="float:left; width:100%;">Exchange Rate : <?php echo number_format($itemSend['exchange_rate']);?></div></td>
            		    </tr>
            			<tr>
                          <td><div class="item-body" style="float:left; width:100%;">Arrival date:<?php echo $itemSend['arrival_time']; ?> Departure date:<?php echo $itemSend['departure_time'];?></div></td>
            			  <td>Print by - Print time :<?php echo $itemSend['account_name']; ?><?php //echo Session::get('user_id');?> - <?php echo date('H:i d/m/y');?></td>
                        </tr> 
            		</table>
            		</td>
            	</tr>
            </table>      
            
            <div class="item-body" style="float:left; width:100%;">
            	<div class="seperator-line" style="border-bottom:1px solid #000000;float:left;width:100%;margin-bottom:10px;">&nbsp;</div>
            </div>
            <div class="item-body" style="float:left; width:100%;">
            	<div class="item-header" style="float:left;width:100%;font-weight:bold;margin-bottom:10px;">
                	
            		 <div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;">Descriptions</div>
            	   <div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;">Amount</div>
            	</div>
            </div>
            <?php
                if(isset($itemSend['rooms']))
                {
                    foreach($itemSend['rooms'] as $key=>$value)
                    {
             ?>           
                        <div class="item-body" style="float:left; width:100%;">	
                        
                        	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;"><?php echo $value['description'] ?></div>
                        	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($value['amount']);?></div>
                        </div>
              <?php
                    }   
                }
                
                if(isset($itemSend['total_phone']) AND $itemSend['total_phone']>0 AND $itemSend['total_phone'] !="0.00")
                {
              ?>
                    <div class="item-body" style="float:left; width:100%;">	
                    
                    	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;">Telephone Fee</div>
                    	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report(System::calculate_number($itemSend['total_phone']));?></div>
                    </div>
              <?php      
                }
                if(isset($itemSend['total_phone']) and $value['total_phone']>0 and $itemSend['total_phone']!="0.00")
                {
              ?>      
                    <div class="item-body" style="float:left; width:100%;">	
                    
                    	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;">Telephone Fee</div>
                    	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report(System::calculate_number($itemSend['total_phone']));?></div>
                    </div>
            <?php        
                }
                if(isset($itemSend['total_massage_amount']) and $itemSend['total_massage_amount'])
                {
              ?>      
                    <div class="item-body" style="float:left; width:100%;">	
                    	
	                    <div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;">Massage service</div>
                    	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report(System::calculate_number($itemSend['total_massage_amount']));?></div>
                    </div>
            <?php        
                } 
                if(isset($itemSend['total_swimming_pool_amount']) and $itemSend['total_swimming_pool_amount'])
                {
              ?>      
                    <div class="item-body" style="float:left; width:100%;">	
                    	
	                    <div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;">Swimming service</div>
                    	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report(System::calculate_number($itemSend['total_swimming_pool_amount']));?></div>
                    </div>
            <?php        
                }
                
                if(isset($itemSend['total_karaoke_amount']) and $itemSend['total_karaoke_amount'])
                {
              ?>      
                    <div class="item-body" style="float:left; width:100%;">	
                    	
	                    <div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;">Karaoke</div>
                    	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report(System::calculate_number($itemSend['total_karaoke_amount']));?></div>
                    </div>
            <?php        
                }
                
                if(isset($itemSend['minibars']))
                {
                    foreach($itemSend['minibars'] as $key=>$value)
                    {
             ?>           
                        <div class="item-body" style="float:left; width:100%;">
                        	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;"><?php echo $value['description']; ?><?php echo $value['hk_code']!= ''? '('. $value['hk_code'].')':'';  ?></div>
                        	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($value['amount']);?></div>	
                        </div> 
              <?php      
                    }  
                }
                
                if(isset($itemSend['laundrys']))
                {
                    foreach($itemSend['laundrys'] as $key=>$value)
                    {
             ?>           
                        <div class="item-body" style="float:left; width:100%;">	
                        	
                        	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;"><?php echo $value['description']; ?><?php echo $value['hk_code']!= ''? '('. $value['hk_code'].')':'';  ?></div>
                        	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($value['amount']);?></div>	
                        </div> 
              <?php      
                    }  
                }
                
                if(isset($itemSend['equipments']))
                {
                    foreach($itemSend['equipments'] as $key=>$value)
                    {
             ?>           
                        <div class="item-body" style="float:left; width:100%;">	
                        	
                        	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;"><?php echo $value['description']; ?></div>
                        	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($value['amount']);?></div>	
                        </div> 
              <?php      
                    }  
                }
                
                if(isset($itemSend['bars']))
                {
                    foreach($itemSend['bars'] as $key=>$value)
                    {
             ?>           
                        <div class="item-body" style="float:left; width:100%;">	
                        
                        	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;"><?php echo $value['description']; ?></div>
                        	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($value['amount']);?></div>	
                        </div> 
              <?php      
                    }  
                }
                
                if(isset($itemSend['massages']))
                {
                    foreach($itemSend['massages'] as $key=>$value)
                    {
             ?>           
                        <div class="item-body" style="float:left; width:100%;">	
                        
                        	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;"><?php echo $value['description']; ?></div>
                        	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($value['amount']);?></div>	
                        </div> 
              <?php      
                    }  
                }
                
                if(isset($itemSend['extra_services']))
                {
                    foreach($itemSend['extra_services'] as $key=>$value)
                    {
             ?>           
                        <div class="item-body" style="float:left; width:100%;">	
                        	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;"><?php echo $value['description']; ?><?php echo $value['ex_code']!= ''? '('. $value['ex_code'].')':'';  ?><?php echo $value['ex_note']!= ''? '('. $value['ex_note'].')':'';  ?></div>
                        	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($value['amount']);?></div>	
                        </div> 
              <?php      
                    }  
                }
                
                if(isset($itemSend['telephones']))
                {
                    foreach($itemSend['telephones'] as $key=>$value)
                    {
             ?>           
                        <div class="item-body" style="float:left; width:100%;">	
                        	
                        	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;"><?php echo $value['description']; ?></div>
                        	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($value['amount']);?></div>	
                        </div> 
              <?php      
                    }  
                }
                
                if(isset($itemSend['service_total']) AND $itemSend['service_total']>0 AND $itemSend['service_total']!=="0.00")
                {
              ?>      
                    <div class="item-body" style="float:left; width:100%;">		
                    	
                    	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;">Service Charge <?php echo "(".($itemSend['service_rate']."%)"); ?></div>
                    	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($itemSend['service_total']);?></div>
                    </div>
            <?php        
                }
                
                if($itemSend['tax_total']>0 AND $itemSend['tax_total']!=="0.00")
                {
              ?>      
                    <div class="item-body" style="float:left; width:100%;">		
                    	
                    	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;">Goverment tax<?php echo "(".$itemSend['tax_rate']."%)"; ?></div>
                    	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($itemSend['tax_total']);?></div>
                    </div>
            <?php        
                }
                
                foreach($itemSend['add_payments'] as $keyPayment=>$valuePayment)
                {       
            ?>
                     <div class="item-body" style="float:left; width:100%;">	
                    	
                		<div class="description"><?php echo $valuePayment['description']; ?></div>
                		<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report(round($valuePayment['total']));?></div>
                	</div>     
            <?php
                      foreach($itemSend['add_payment_items'] as $keyPaymentItem => $valuePaymnetItem)
                      {
                          if($valuePayment['id']==$valuePaymnetItem['id'])
                          {
                              foreach($valuePaymnetItem['items'] as $keyPItems => $valuePItems)
                              {
                                  
           ?>
                                    <div class="item-body" style="float:left; width:100%;">	
                                        
                                        <div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px; padding-left:30px;"><i><?php echo $valuePItems['description']; ?></i><span style="float:right;"><i><?php echo System::display_number_report($valuePItems['amount']);?></i></span></div>
                                        <div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"></div>
                                    </div>
           <?php                         
                              }
                              
                              if(isset($valuePaymnetItem['service_amount']) and $valuePaymnetItem['service_amount']>0)
                              {
           ?>                    
                                   <div class="item-body" style="float:left; width:100%;">	
                                    
                                    <div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px; padding-left:30px;"><i>Service amount</i><span style="float:right;"><i><?php echo System::display_number_report($valuePaymnetItem['service_amount']);?></i></span> </div>
                                    <div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"></div>
                                </div> 
           <?php                   
                              }
                              
                              if(isset($valuePaymnetItem['tax_amount']) and $valuePaymnetItem['tax_amount']>0)
                              {
           ?>                    
                                   <div class="item-body" style="float:left; width:100%;">	
                                   
                                    <div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px; padding-left:30px;"><i>Tax amount</i><span style="float:right;"><i><?php echo System::display_number_report($valuePaymnetItem['tax_amount']);?></i></span> </div>
                                    <div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"></div>
                                </div> 
           <?php                   
                              } 
                          }
                      }            
                }
                
                if(isset($itemSend['discounts']) and $itemSend['discounts'])
                {
                   foreach($itemSend['discounts'] as $key=>$value)
                   {
            ?>        
                        <div class="item-body" style="float:left; width:100%;">	
                    	
                    	<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;">Discount by <?php echo HOTEL_CURRENCY;?><?php echo HOTEL_CURRENCY;?></div>
                    	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;">-<?php echo System::display_number_report($value['amount']);?></div>
                    </div>
            <?php        
                   } 
                }
               
                $total_deposit =0;
           ?>
          <div id="total_invoice">
          <div class="item-body total-group">
           <?php
                if(isset($itemSend['deposits']))
                {
                    foreach($itemSend['deposits'] as $key => $value)
                    {
                        $total_deposit += $value['amount'];
                        echo "</div></div>";
                    }
                }
                
           ?>
           <div class="sub-item-body">	
        		
        		<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px; color:red; "><strong>Grand Total</strong>
                    <?php if($itemSend['foc_all']){ ?>FOC<?php } ?>
                    </div>
        		<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px; color:red;"><strong><?php echo System::display_number_report($itemSend['total']+$total_deposit);?></strong></div>
        	</div>
            
            <?php
                if(isset($itemSend['deposits']))
                {
                   foreach($itemSend['deposits'] as $key => $value)
                    {
                ?>
                    <div class="sub-item-body">	
                        
                        <div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;">Deposit</div>
                        <div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($value['amount']);?></div>
                    </div>
                <?php        
                    } 
                }
                
               if($total_deposit>0)
               {
           ?>
                    <div class="sub-item-body" style="float: left; width: 100%">	
                        
                        <div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px; color: red;"><strong>Remain pay</strong>
                <?php
                    if($itemSend['foc_all'])
                    {
                        echo "(FOC)";   
                    }
                    echo "</div>";    
                ?> 
                        <div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px; color: red;"><strong><?php echo System::display_number_report($itemSend['total']);?></strong></div>
                    </div>
           <?php         
               }
               if(isset($itemSend['total_bank_fee']) and $itemSend['total_bank_fee'] >0)
               {
           ?>     
                    <div class="sub-item-body" style="display:none;">	
                		
                		<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;"><?php echo Portal::language('bank_fee'); echo "(".$itemSend['bank_fee_percen']."%)"; ?></div>
                		<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><?php echo System::display_number_report($itemSend['total_bank_fee']);?></div>
                	</div>
                    
                    <div class="sub-item-body" style="display:none;">	
                		
                		<div class="description" style="float:left;width:45%;text-align:left;padding:2px 2px 2px 5px;"> <strong><?php echo(Portal::language('total_with_bank_fee')) ?></strong></div>
                	<div class="amount" style="float:left; width:30%; text-align:right; padding:2px 0px 2px 0px;"><strong><?php echo System::display_number_report($itemSend['total_with_bank_fee']);?></strong></div>
             <?php
               } 
            ?>
            <div align="center">
                <?php
                    $j=0;
                    foreach($itemSend['payments'] as $key => $value)
                    {
                        $j++;
                    }
                    if($j>0)
                    {
                ?>
                    <table width="800px" style="border-collapse:collapse;" border="1" bordercolor="#CCCCCC" cellpadding="3px">
                        <tr>
                      		<th align="center"><?php echo(Portal::language('payment_type')) ?></th >
                            <th align="center"><?php echo(Portal::language('bank_account')."/".Portal::language('card_number')) ?></th >
                            <th align="center"><?php echo(Portal::language('description')) ?></th >
                            <th align="center"><?php echo(Portal::language('currency')) ?></th >
                            <th align="right"><?php echo(Portal::language('total')) ?></th >
                            <!--  
                            <th align="right"><?php echo(Portal::language('bank_fee')) ?></th >
                            <th align="right"><?php echo(Portal::language('total_with_bank_fee')) ?></th >
                            -->
                      	</tr>
                        
                        <?php
                        foreach($itemSend['payments'] as $key => $value)
                        {
                        ?>
                          <tr>
                      		<td align="center"><?php echo Portal::language(strtolower($value['payment_type_id'])); if($value['credit_card_id']!=''){echo '('.$value['credit_card_name'].')';}?></td>
                            <td align="center"><?php echo $value['bank_acc'] ?></td>
                            <td align="center"><?php echo $value['description'] ?></td>
                            <td align="center"><?php echo $value['currency_id'] ?></td>
                            <td align="right"><?php echo System::display_number($value['total']);?></td>
                            <!--  
                            <td align="right"><?php echo System::display_number($value['bank_fee']);?></td>
                            <td align="right"><?php echo System::display_number($value['total'] + $value['bank_fee']);?></td>
                            -->   
                      	</tr>
                        <?php    
                        }    
                        ?>
                     </table>   
                <?php        
                    }
                ?>
            </div> 
            </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean(); 
        //print_r($output);
        return $output;
    }
?>    