<?php $beo = array (
  'code_mice' => '04082016',
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
      'time' => '14:00 04/08/2016 - 12:00 05/08/2016',
    ),
    2 => 
    array (
      'id' => '2',
      'name' => 'HT nhà ăn riêng',
      'service' => 'Ẩm thực',
      'time' => '10:25 04/08/2016 - 12:25 04/08/2016',
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
          'name' => 'Deluxe Family 14:00 04/08/2016 - 12:00 05/08/2016',
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
          'name' => 'Ba ba đồng nướng Thung Nham Ngày 04/08/2016 In HT nhà ăn riêng',
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
      'child' => 
      array (
        3 => 
        array (
          'id' => '3',
          'stt' => '3',
          'name' => 'VÉ BUFFET NL 00:00 04/08/2016',
          'quantity' => '1',
          'unit' => 'Apartment',
          'price' => '20000',
          'discount' => '',
          'amount' => '20000',
          'total' => '20000',
        ),
      ),
    ),
    'EXS' => 
    array (
      'id' => 'EXS',
    ),
  ),
  'mice_total' => '3570000',
  'mice_service' => '0',
  'mice_tax' => '0',
  'mice_grand_total' => '3570000',
  'setup' => 
  array (
    101 => 
    array (
      'id' => '5',
      'in_date' => '05/08/2016',
      'content' => 'Bộ phận lễ tân chuẩn bị : Phòng cho khách có ban công , phòng sạch sẽ',
    ),
  ),
  'user_view_full_name' => 'Prepared by: Trần Thị Lý',
); $database = array (
  'mice_booking' => 
  array (
    'id' => 'mice_booking',
    'items' => 
    array (
      4 => 
      array (
        'id' => '4',
        'mice_reservation_id' => '4',
        'room_level_id' => '72',
        'quantity' => '1',
        'child' => '0',
        'adult' => '2',
        'price' => '2350000',
        'exchange_rate' => '22000',
        'usd_price' => '.91',
        'net_price' => '0',
        'service_rate' => '0',
        'tax_rate' => '0',
        'time_in' => '1470294000',
        'time_out' => '1470373200',
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
      1 => 
      array (
        'id' => '1',
        'mice_reservation_id' => '4',
        'time' => '1470243600',
        'note' => NULL,
        'tax_rate' => NULL,
        'service_rate' => NULL,
        'total_before_tax' => '18181.818181818',
        'total' => '20000',
        'ticket_id' => '7',
        'ticket_area_id' => '4',
        'quantity' => '1',
        'price' => '20000',
        'discount_quantity' => NULL,
        'discount_rate' => NULL,
      ),
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
      1 => 
      array (
        'id' => '1',
        'mice_reservation_id' => '4',
        'table_id' => '313',
        'bar_id' => '4',
        'time_in' => '1470281100',
        'time_out' => '1470288300',
        'full_rate' => '1',
        'full_charge' => '0',
        'service_rate' => '0',
        'tax_rate' => '0',
        'foc' => '0',
        'banquet_order_type' => NULL,
        'num_people' => NULL,
        'order_person' => NULL,
        'discount' => '0',
        'discount_percent' => '0',
        'service_name' => NULL,
        'table_child_product' => 
        array (
          1 => 
          array (
            'id' => '1',
            'mice_restaurant_id' => '1',
            'product_id' => 'DATN319',
            'quantity' => '1',
            'price_id' => '8250',
            'quantity_discount' => '0',
            'discount_rate' => '0',
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