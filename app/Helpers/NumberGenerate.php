<?php
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;

/**
 * @return string
 * return issue number 
 */
function issue_number():string{
  $comID = Auth::user()->company_id;
  $data = DB::select('CALL getTableID("trns03a_issue_master","' .$comID.'")');
  return $data[0]->masterID;
}

function number_to_words_bdt($number):string
{
  $ones = array(
    0 => '',
    1 => 'one',
    2 => 'two',
    3 => 'three',
    4 => 'four',
    5 => 'five',
    6 => 'six',
    7 => 'seven',
    8 => 'eight',
    9 => 'nine',
    10 => 'ten',
    11 => 'eleven',
    12 => 'twelve',
    13 => 'thirteen',
    14 => 'fourteen',
    15 => 'fifteen',
    16 => 'sixteen',
    17 => 'seventeen',
    18 => 'eighteen',
    19 => 'nineteen'
);

$tens = array(
    2 => 'twenty',
    3 => 'thirty',
    4 => 'forty',
    5 => 'fifty',
    6 => 'sixty',
    7 => 'seventy',
    8 => 'eighty',
    9 => 'ninety'
);

$result = '';

if ($number < 0) {
    $result .= 'minus ';
    $number = abs($number);
}

if ($number < 20) {
    $result .= $ones[$number];
} elseif ($number < 100) {
    $result .= $tens[floor($number / 10)];
    $remainder = $number % 10;
    if ($remainder) {
        $result .= '-' . $ones[$remainder];
    }
} elseif ($number < 1000) {
    $result .= $ones[floor($number / 100)] . ' hundred';
    $remainder = $number % 100;
    if ($remainder) {
        $result .= ' ' . number_to_words_bdt($remainder);
    }
} elseif ($number < 1000000) {
    $result .= number_to_words_bdt(floor($number / 1000)) . ' thousand';
    $remainder = $number % 1000;
    if ($remainder) {
        $result .= ' ' . number_to_words_bdt($remainder);
    }
} else {
    $result .= number_to_words_bdt(floor($number / 1000000)) . ' million';
    $remainder = $number % 1000000;
    if ($remainder) {
        $result .= ' ' . number_to_words_bdt($remainder);
    }
}

    return ucfirst($result);
}
