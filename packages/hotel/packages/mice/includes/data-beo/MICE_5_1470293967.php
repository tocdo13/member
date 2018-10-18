<?php $beo = array (
  'code_mice' => '04082016',
  'customer_name' => 'AGORA',
  'contact_name' => '',
  'sales' => '',
  'contact_phone' => '',
  'user_full_name' => 'Lê Thị Thu',
  'event_name' => '',
  'confirmed_number_of_guest' => '',
  'expect_number_of_guest' => '',
  'event_date' => '',
  'time' => '',
  'venues' => 
  array (
    1 => 
    array (
      'id' => '1',
      'name' => 'Nhà Sàn 40',
      'service' => 'Home stays',
      'time' => '14:00 04/08/2016 - 12:00 05/08/2016',
    ),
    2 => 
    array (
      'id' => '2',
      'name' => 'Hội Nghị',
      'service' => 'Ẩm thực',
      'time' => '13:57 04/08/2016 - 15:57 04/08/2016',
    ),
  ),
  'deposit_note' => '',
  'payment_method_note' => '',
  'note' => '',
  'items' => 
  array (
    'REC' => 
    array (
      'id' => 'REC',
      'child' => 
      array (
        1 => 
        array (
          'id' => '1',
          'stt' => '1',
          'name' => 'Nhà Sàn 40 14:00 04/08/2016 - 12:00 05/08/2016',
          'quantity' => '1',
          'unit' => 'Apartment',
          'price' => '4000000',
          'discount' => '0',
          'amount' => '4000000',
          'total' => '4000000',
        ),
      ),
    ),
    'RES' => 
    array (
      'id' => 'RES',
      'child' => 
      array (
        2 => 
        array (
          'id' => '2',
          'stt' => '2',
          'name' => 'Bánh Cosy to Ngày 04/08/2016 In Hội Nghị',
          'quantity' => '1',
          'unit' => 'Gói',
          'price' => '20000',
          'discount' => '0',
          'amount' => '20000',
          'total' => '20000',
        ),
      ),
    ),
    'VENDING' => 
    array (
      'id' => 'VENDING',
    ),
    'TICKET' => 
    array (
      'id' => 'TICKET',
    ),
    'EXS' => 
    array (
      'id' => 'EXS',
    ),
  ),
  'mice_total' => '4020000',
  'mice_service' => '0',
  'mice_tax' => '0',
  'mice_grand_total' => '4020000',
  'setup' => 
  array (
    102 => 
    array (
      'id' => '5',
      'in_date' => '04/08/2016',
      'content' => 'Chuẩn bị phòng
cung cấp minibar',
    ),
    103 => 
    array (
      'id' => '2',
      'in_date' => '04/08/2016',
      'content' => 'cần 4 người',
    ),
    104 => 
    array (
      'id' => '2',
      'in_date' => '05/08/2016',
      'content' => 'cần 1 người',
    ),
    105 => 
    array (
      'id' => '6',
      'in_date' => '04/08/2016',
      'content' => 'âfff',
    ),
  ),
  'user_view_full_name' => 'Prepared by: Lê Thị Thu',
  'CKFinder_Path' => 'Images:/:1',
); $database = array (
  'mice_booking' => 
  array (
    'id' => 'mice_booking',
    'items' => 
    array (
      6 => 
      array (
        'id' => '6',
        'mice_reservation_id' => '5',
        'room_level_id' => '73',
        'quantity' => '1',
        'child' => '0',
        'adult' => NULL,
        'price' => '4000000',
        'exchange_rate' => '22000',
        'usd_price' => '181.82',
        'net_price' => '0',
        'service_rate' => '0',
        'tax_rate' => '0',
        'time_in' => '1470294000',
        'time_out' => '1470373200',
        'note' => NULL,
        'service_name' => NULL,
        'total_amount' => '4000000',
        'recode' => NULL,
      ),
    ),
  ),
  'mice_extra_service' => 
  array (
    'id' => 'mice_extra_service',
    'items' => 
    array (
    ),
  ),
  'mice_ticket_reservation' => 
  array (
    'id' => 'mice_ticket_reservation',
    'items' => 
    array (
    ),
  ),
  'mice_party' => 
  array (
    'id' => 'mice_party',
    'items' => 
    array (
    ),
  ),
  'mice_restaurant' => 
  array (
    'id' => 'mice_restaurant',
    'items' => 
    array (
      2 => 
      array (
        'id' => '2',
        'mice_reservation_id' => '5',
        'table_id' => '296',
        'bar_id' => '2',
        'time_in' => '1470293820',
        'time_out' => '1470301020',
        'full_rate' => '0',
        'full_charge' => '0',
        'service_rate' => '0',
        'tax_rate' => '0',
        'foc' => '0',
        'banquet_order_type' => NULL,
        'num_people' => NULL,
        'order_person' => NULL,
        'discount' => NULL,
        'discount_percent' => NULL,
        'service_name' => NULL,
        'table_child_product' => 
        array (
          2 => 
          array (
            'id' => '2',
            'mice_restaurant_id' => '2',
            'product_id' => 'VEN004',
            'quantity' => '1',
            'price_id' => '7255',
            'quantity_discount' => NULL,
            'discount_rate' => NULL,
            'price' => '20000',
            'unit_id' => '95',
            'note' => NULL,
          ),
        ),
      ),
    ),
  ),
  'mice_vending' => 
  array (
    'id' => 'mice_vending',
    'items' => 
    array (
    ),
  ),
); $database_module = array (
  'reservation' => 
  array (
    'id' => 'reservation',
    'items' => 
    array (
    ),
  ),
  'extra_service_invoice' => 
  array (
    'id' => 'extra_service_invoice',
    'items' => 
    array (
    ),
  ),
  'ticket_reservation' => 
  array (
    'id' => 'ticket_reservation',
    'items' => 
    array (
    ),
  ),
  'bar_reservation' => 
  array (
    'id' => 'bar_reservation',
    'items' => 
    array (
    ),
  ),
  've_reservation' => 
  array (
    'id' => 've_reservation',
    'items' => 
    array (
    ),
  ),
  'party_reservation' => 
  array (
    'id' => 'party_reservation',
    'items' => 
    array (
    ),
  ),
); ?>