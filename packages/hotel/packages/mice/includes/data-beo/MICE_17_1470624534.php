<?php $beo = array (
  'code_mice' => '08082016',
  'customer_name' => 'AGORA',
  'contact_name' => '',
  'sales' => '',
  'contact_phone' => '',
  'user_full_name' => 'Trần Thị Lý',
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
      'name' => 'Deluxe Family',
      'service' => 'Home stays',
      'time' => '14:00 08/08/2016 - 12:00 09/08/2016',
    ),
    2 => 
    array (
      'id' => '2',
      'name' => 'HT nhà ăn riêng',
      'service' => 'Ẩm thực',
      'time' => '09:27 08/08/2016 - 11:27 08/08/2016',
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
          'name' => 'Deluxe Family 14:00 08/08/2016 - 12:00 09/08/2016',
          'quantity' => '1',
          'unit' => 'Apartment',
          'price' => '2350000',
          'discount' => '0',
          'amount' => '2350000',
          'total' => '2350000',
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
          'name' => 'Ba ba đồng nướng Thung Nham Ngày 08/08/2016 In HT nhà ăn riêng',
          'quantity' => '1',
          'unit' => 'Kg',
          'price' => '1200000',
          'discount' => '0',
          'amount' => '1200000',
          'total' => '1200000',
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
  'mice_total' => '3550000',
  'mice_service' => '0',
  'mice_tax' => '0',
  'mice_grand_total' => '3550000',
  'setup' => 
  array (
    101 => 
    array (
      'id' => '5',
      'in_date' => '08/08/2016',
      'content' => 'Khách khó tính : muốn ở phòng có ban công ,bàn có lãng hoa quả',
    ),
  ),
  'user_view_full_name' => 'Prepared by: Trần Thị Lý',
); $database = array (
  'mice_booking' => 
  array (
    'id' => 'mice_booking',
    'items' => 
    array (
      14 => 
      array (
        'id' => '14',
        'mice_reservation_id' => '17',
        'room_level_id' => '72',
        'quantity' => '1',
        'child' => '0',
        'adult' => '2',
        'price' => '2350000',
        'exchange_rate' => '22000',
        'usd_price' => '106.82',
        'net_price' => '0',
        'service_rate' => '0',
        'tax_rate' => '0',
        'time_in' => '1470639600',
        'time_out' => '1470718800',
        'note' => NULL,
        'service_name' => NULL,
        'total_amount' => '2350000',
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
      8 => 
      array (
        'id' => '8',
        'mice_reservation_id' => '17',
        'table_id' => '313',
        'bar_id' => '4',
        'time_in' => '1470623220',
        'time_out' => '1470630420',
        'full_rate' => '1',
        'full_charge' => '0',
        'service_rate' => '0',
        'tax_rate' => '0',
        'foc' => '0',
        'banquet_order_type' => NULL,
        'num_people' => '2',
        'order_person' => NULL,
        'discount' => NULL,
        'discount_percent' => NULL,
        'service_name' => NULL,
        'table_child_product' => 
        array (
          10 => 
          array (
            'id' => '10',
            'mice_restaurant_id' => '8',
            'product_id' => 'DATN319',
            'quantity' => '1',
            'price_id' => '8250',
            'quantity_discount' => NULL,
            'discount_rate' => NULL,
            'price' => '1200000',
            'unit_id' => '85',
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