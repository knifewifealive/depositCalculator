<?php

if ($_POST['sum'] < 1000 || $_POST['sum'] > 3000000) {
  echo 'Сумма вклада должна быть в диапазоне [1000; 3000000]';
  exit();
} elseif ($_POST['term'] < 1) {
  echo 'Продолжительность вклада должна быть больше одного месяца';
  exit();
} elseif ($_POST['sumAdd'] < 0 || $_POST['sumAdd'] > 3000000) {
  echo 'Сумма пополнения должна быть в диапазоне [0; 3000000]';
  exit();
} elseif (gettype($_POST['percent']*1) !== integer || $_POST['percent'] < 3 || $_POST['percent'] > 100) {
  echo 'Процент это целое число от 3 до 100';
  exit();
} elseif ($_POST['selectDuration'] == 'Год' && ($_POST['term'] < 1)) {
  echo 'Продолжительность вклада может быть в пределах от 1 до 60 месяцев, либо от 1 до 5 лет';
  exit();
}

if ($_POST['selectDuration'] == 'Год') {
    $term = $_POST['term']*12;
}
else $term = $_POST['term'];


$daysN = countDaysInMonths($_POST['startDate'], $term);
$daysY = countDaysInYear($_POST['startDate'], $term);
//$sum = countDeposit($_POST['sum'], $term, $daysN);

$sumAdd = countDepositAdd($_POST['sum'],$_POST['sumAdd'], $term, $daysN, $daysY);
echo $sumAdd;
//Функция возвращает массив из количества дней по каждому месяцу; 1 - полученная дата, 2 - полученная продолжительность в месяцах.
function countDaysInMonths($startDate, $term) {
  $d = new DateTime($startDate);
  $daysN = array();
  for ($i=0; $i < $term; $i++) { 
    $daysN[] = cal_days_in_month(CAL_GREGORIAN , date_format($d, 'm'), date_format($d, 'Y'));
    date_add($d, date_interval_create_from_date_string('1 month'));    
  }
  return $daysN;
}
//Функция считает количество дней в году для каждого месяца, исходя из даты открытия и продолжительности вклада
function countDaysInYear($startDate, $term) {
  $date = new DateTime($startDate);
  $daysY = array();
  for ($i=0; $i < $term; $i++) {
    if(date_format($date, 'Y') % 4 == 0 && date_format($date, 'Y') % 100 != 0 || date_format($date, 'Y') % 400 == 0) {
      $daysY[] = 366;
    } else
      $daysY[] = 365;
    date_add($date, date_interval_create_from_date_string('1 month'));   
  }
  return $daysY;
}
//Функция считает вклад с пополнением
//sumN = sum + (sum + sumAdd) * daysN * (percent / daysY)
function countDepositAdd($sum,$sumAdd, $term, $daysN, $daysY): string
{
    $percentIncome = array();
    for ($i=0; $i<$term; $i++){
        $month_summ = (($sum + $sumAdd )* $daysN[$i] * ($_POST['percent']/100/$daysY[$i]));
        $sum= $sum + $month_summ + $sumAdd;
        $percentIncome[] = $month_summ;
    }
    $sum1 = number_format($_POST['sum'], 0, '.', ' ');
    //Разница между первоначальным счётом и конечным
    $sumDifference = number_format($sum - $_POST['sum'], 0, '.', ' ');
    //Количество прибыли по вкладу
    $percentIncome = number_format(array_sum($percentIncome), 0, '.', ' ');
    $sum = number_format($sum, 0, '.', ' ');
    return "Разница между конечным состоянием счёта({$sum} р.) и первоначальным состоянием счёта({$sum1} р.) 
    через {$term} месяцев составит:  {$sumDifference}р. Прибыль по вкладу: {$percentIncome}р.";
}