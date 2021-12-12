<?php

if ($_POST['sum'] < 1000 || $_POST['sum'] > 3000000) {
  echo 'Сумма вклада должна быть в диапазоне [1000; 3000000]';
  exit();
} elseif ($_POST['term'] < 1 || $_POST['term'] > 60) {
  echo 'Продолжительность вклада может быть в пределах от 1 до 60 месяцев, либо от 1 до 5 лет';
  exit();
} elseif ($_POST['sumAdd'] < 0 || $_POST['sumAdd'] > 3000000) {
  echo 'Сумма пополнения должна быть в диапазоне [0; 3000000]';
  exit();
} elseif (gettype($_POST['percent']*1) !== integer || $_POST['percent'] < 3 || $_POST['percent'] > 100) {
  echo 'Процент это целое число от 3 до 100';
  exit();
}

if ($_POST['selectDuration'] == 'Год') {
    $term = $_POST['term']*12;
}
else $term = $_POST['term'];


$daysN = countDaysInMonths($_POST['startDate'], $term);

//$sum = countDeposit($_POST['sum'], $term, $daysN);

$sumAdd = countDepositAdd($_POST['sum'],$_POST['sumAdd'], $term, $daysN);
echo $sumAdd;
//Функция возвращает массив из количества дней по каждому месяцу; 1 - полученная дата, 2 - полученная продолжительность в месяцах.
function countDaysInMonths($dateStart,$term): array
{
    $daysList = array();

    $dateDepositOpens = date_parse_from_format("d.m.Y", $dateStart);
    for ($i = 0, $j =0, $k = 0, $l = 0, $m = 0, $n = 0;  $i < $term; $i++) {
        $currentMonth = $dateDepositOpens['month'];//12
        $currentYear = $dateDepositOpens['year'];//2021
        $allMonths = $currentMonth +$i;
        if ($allMonths >= 13) {
            $currentYear++;
            $allMonths = 0;
            ++$j;
            $allMonths += $j;
            if ($allMonths >= 13) {
                $currentYear++;
                $allMonths = 0;
                ++$k;
                $allMonths += $k;
                if ($allMonths >= 13) {
                    $currentYear++;
                    $allMonths = 0;
                    ++$l;
                    $allMonths += $l;
                    if ($allMonths >= 13) {
                        $currentYear++;
                        $allMonths = 0;
                        ++$m;
                        $allMonths += $m;
                        if ($allMonths >= 13) {
                            $currentYear++;
                            $allMonths = 0;
                            ++$n;
                            $allMonths += $n;
                            $number = cal_days_in_month(CAL_GREGORIAN, $allMonths, $currentYear);
                            $daysList[] = $number;

                        }
                        $number = cal_days_in_month(CAL_GREGORIAN, $allMonths, $currentYear);
                        $daysList[] = $number;
                        continue;
                    }
                    $number = cal_days_in_month(CAL_GREGORIAN, $allMonths, $currentYear);
                    $daysList[] = $number;
                    continue;
                }
                $number = cal_days_in_month(CAL_GREGORIAN, $allMonths, $currentYear);
                $daysList[] = $number;
                continue;
            }
            $number = cal_days_in_month(CAL_GREGORIAN, $allMonths, $currentYear);
            $daysList[] = $number;
            continue;

        }
        $number = cal_days_in_month(CAL_GREGORIAN, $allMonths, $currentYear);
        $daysList[] = $number;

    }
    return array_slice($daysList, 0, $term);
}

//Функция считает вклад с пополнением
//sumN = sum + (sum + sumAdd) * daysN * (percent / daysY)
function countDepositAdd($sum,$sumAdd, $term, $daysN): string
{
    $percentIncome = array();
    for ($i=0; $i<$term; $i++){
        $month_summ = (($sum + $sumAdd )* $daysN[$i] * ($_POST['percent']/100/365));
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