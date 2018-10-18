<?php
$department = array(
    'reception'=> array(
                        'id'=>'reception',
                        'name'=>Portal::language('reception'),
                        'tables'=>array(
                                        'reservation',
                                        'reservation_room',
                                        'reservation_note',
                                        'vat_bill'
                        ),
                    ),
    'restaurant'=> array(
                        'id'=>'restaurant',
                        'name'=>Portal::language('restaurant'),
                        'tables'=>array(
                                        'bar_reservation',
                                        ),
                    ),
    'housekeeping'=> array(
                        'id'=>'housekeeping',
                        'name'=>Portal::language('housekeeping'),
                        'tables'=>array(
                                        'amenities_used',
                                        'extra_service_invoice',
                                        'forgot_object',
                                        'housekeeping_equipment_damaged',
                                        'housekeeping_invoice', 
                                        ),
                    ),
    'warehouse'=> array(
                        'id'=>'warehouse',
                        'name'=>Portal::language('warehouse'),
                        'tables'=>array(
                                        'wh_invoice',
                                        ),
                    ),
);

$link_table = array(
    'reservation' => array(
                        'compare_filed'=>'time',
                        'data_type'=>'number',
                        'associate'=>array(  
                                        ),
                        ),
    'reservation_room' => array(
                            'compare_filed'=>'time',
                            'data_type'=>'number',
                            'associate'=>array( 
                                             'room_status'=>'reservation_room_id',
                                             'reservation_traveller'=>'reservation_room_id',
                                            ),
                            ),
    'reservation_note' => array(
                            'compare_filed'=>'create_time',
                            'data_type'=>'number',
                            'associate'=>array(  
                                            ),
                            ),
    'vat_bill' => array(
                    'compare_filed'=>'print_time',
                    'data_type'=>'number',
                    'associate'=>array(  
                                    ),
                    ),
    'bar_reservation' => array(
                        'compare_filed'=>'time',
                        'data_type'=>'number',
                        'associate'=>array(  
                                         'bar_reservation_product'=>'bar_reservation_id',
                                         'bar_reservation_table'=>'bar_reservation_id',
                                        ),
                        ),
    'amenities_used' => array(
                        'compare_filed'=>'time',
                        'data_type'=>'number',
                        'associate'=>array(  
                                         'amenities_used_detail'=>'amenities_used_id',
                                        ),
                        ),
    'extra_service_invoice' => array(
                            'compare_filed'=>'time',
                            'data_type'=>'number',
                            'associate'=>array(  
                                             'extra_service_invoice_detail'=>'invoice_id',
                                            ),
                            ),
    'forgot_object' => array(
                            'compare_filed'=>'time',
                            'data_type'=>'number',
                            'associate'=>array(  
                                            ),
                            ),
    'housekeeping_equipment_damaged' => array(
                                        'compare_filed'=>'time',
                                        'data_type'=>'number',
                                        'associate'=>array(  
                                                        ),
                                                ),
    'housekeeping_invoice' => array(
                            'compare_filed'=>'time',
                            'data_type'=>'number',
                            'associate'=>array(  
                                             'housekeeping_invoice_detail'=>'invoice_id',
                                            ),
                            ),
    'wh_invoice' => array(
                        'compare_filed'=>'time',
                        'data_type'=>'number',
                        'associate'=>array(  
                                         'wh_invoice_detail'=>'invoice_id',
                                        ),
                        ),
                            
);

?>