<style>
    #banner_url{display:none;}
    /* Shine */
        .wicket {
            	position: relative;
            	-webkit-transform: scale(0.9);
            	transform: scale(0.9);
            	-webkit-transition: .3s ease-in-out;
            	transition: .3s ease-in-out;
                
            }
        .wicket:hover{
            -webkit-transform: scale(1);
	       transform: scale(1);
           background-color: black !important;
        }  
          
        .wicket::before {
        	position: absolute;
        	top: 50%;
        	left: 50%;
        	z-index: 2;
        	display: block;
        	content: '';
        	width: 0;
        	height: 0;
        	background: rgba(255,255,255,.2);
        	border-radius: 100%;
        	-webkit-transform: translate(-25%, -25%);
        	transform: translate(-50%, -50%);
        	opacity: 0;
        }
        .wicket:hover::before {
        	-webkit-animation: circle .75s;
        	animation: circle .75s;
        }
        @-webkit-keyframes circle {
        	0% {
        		opacity: 1;
        	}
        	40% {
        		opacity: 1;
        	}
        	100% {
        		width: 100%;
        		height: 100%;
        		opacity: 0;
        	}
        }
        @keyframes circle {
        	0% {
        		opacity: 1;
        	}
        	40% {
        		opacity: 1;
        	}
        	100% {
        		width: 100%;
        		height: 100%;
        		opacity: 0;
        	}
        }
</style>
<div class="row">
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">[[.ticket_card_sale_list.]]</h4>
                </div>
                <div class="panel-body">
                    <!--LIST:ticket_card_sales-->
                        <div class="col-md-1" style="margin-top: 10px;"></div>
                        <div onclick="window.location='?page=ticket_card_wicket&cmd=edit&sales_id=[[|ticket_card_sales.id|]]';" class="col-md-2 wicket" style="margin-top:10px; background: maroon; height: 150px; border-radius: 10px; cursor: pointer;">
                            <span style="font-size:15px;color: white; vertical-align:middle; text-align: center; height: 150px;display: flex;justify-content: center;align-items: center;">[[|ticket_card_sales.name|]]</span>
                        </div>
                    <!--/LIST:ticket_card_sales-->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //jQuery(document).ready(function(){
//        jQuery('#testRibbon').css('display','none');
//		jQuery(".jcarousel-clip-horizontal").width(jQuery('.full_screen').width()-100);
//		if(jQuery('#bound_product_list').css('display')=='none')
//        {
//			jQuery('#bound_product_list').css('display','block');
//		}
//    });
</script>