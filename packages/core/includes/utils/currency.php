<?php	 	

/*
* Personnel system: Erase Memories
* Create by: TCV., JSC
* Date: 11/2011
*/
//KimTan:xu ly doc am
function currency_to_text( $currency ) {
    if(substr($currency,0,1)=='-')
    {
       return "Âm ".currency_to_text_1($currency);
    }
    else
    {
        return currency_to_text_1($currency);
    }
} 
//end xu ly doc am                       
function currency_to_text_1( $currency ) {
    if(($currency - round($currency)) == 0)
        return current_to_text($currency) . ' đồng chẵn.' ;
    else
    {
        $arr_cur = explode('.',$currency);
        return current_to_text($arr_cur[0])." phẩy ".current_to_text($arr_cur[1])." đồng";
    }
}

function current_to_text( $currency ) {
    $unit = array( 1 => '', 2 => 'ngàn', 3 => 'triệu', 4 => 'tỉ', 5 => 'nghìn', 6 => 'triệu', 7 => 'tỉ' );
    $text = '';
    $i = 1;
    while ( ( strlen( $currency ) - 3 * $i ) > -3 ) {
        if ( ( strlen( $currency ) - 3 * $i ) < 0 ) {
            $number_digits_miss = abs( strlen( $currency ) - 3 * $i );
        } else  $number_digits_miss = 0;
        $temp = substr( $currency, strlen( $currency ) - 3 * $i + $number_digits_miss, 3 - $number_digits_miss );
        if ( ( $t = read_currency( $temp ) ) != '' ) {
            $text = $t . ' ' . $unit[$i] . ' ' . $text;
        }
        $i++;
    }
    return ucfirst( $text );
}

function read_currency( $number ) {
    $text = '';
    $text_number = array( 0 => 'không', 1 => 'một', 2 => 'hai', 3 => 'ba', 4 => 'bốn', 5 => 'năm', 6 => 'sáu', 7 => 'bảy', 8 => 'tám', 9 => 'chín' );
    $temp[3] = $temp[2] = $temp[1] = '';
    //KimTan: trường hop am tien van cho doc dung va xu ly them "am" trong layout
    $a = substr($number,0,1);
    if($a!='-')
    {
        $number = $number;
    }
    else
    {
        $number = substr($number,1);
    }
    // end KimTan
    for ( $i = 1; $i <= strlen($number); $i++ ) {
        if ( ( $digital[$i] = substr( $number, strlen( $number ) - $i, 1 ) ) != '' ) {
            $temp[$i] = isset( $text_number[$digital[$i]] ) ? $text_number[$digital[$i]] : '';
            if ( $i == 3 ) $temp[$i] .= ' trăm ';
            if ( $i == 2 ) {
                if ( $digital[$i] == 0 ) {
                    if ( $digital[1] != 0) $temp[$i] = ' lẻ ';
                    else  $temp[$i] = '';
                } elseif ( $digital[$i] == 1 ) {
                    $temp[$i] = suffix( $digital[$i] );
                } else {
                    if ( $digital[1] == 0 ) {
                        $temp[$i] .= suffix( $digital[$i] );
                    } else  $temp[$i] .= ' ';
                }
            }
            if ( $i == 1 and $digital[$i] == 0 ) $temp[$i] = '';
        }
    }

    if ( isset( $digital[2]) && $digital[2]>1 ) {
        if ( $digital[1] == 1 ) $temp[1] = ' mốt ';
    }
    if ( isset( $digital[1] ) and isset( $digital[2] ) and isset( $digital[3] ) and ( $digital[1] == 0 ) and ( $digital[2] == 0 ) and ( $digital[3] == 0 ) ) {
        $temp[3] = $temp[2] = $temp[1] = '';
    }

    return $text . $temp[3] . $temp[2] . $temp[1];
}
function suffix( $number ) {
    if ( $number == 1 ) return ' mười ';

    else  return ' mươi ';

}


function footer_date_to_text( $sub, $d = '0' ) {
    if ( $d ) {
        return $sub . ', ngày ' . date( 'd' ) . ' tháng ' . date( 'm' ) . ' năm ' . date( 'Y' );
    } else {
        return $sub . ', ngày ... tháng ... năm 20...';
    }
}

?>