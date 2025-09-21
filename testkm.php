<?php
$bal = 15;
$fare = 15;

if ($bal >= $fare) {
    echo 'success';
} else {
    echo 'insufficient';
}
/*//based fare 12 first 4km
//if exceed to 4km add 1peso
$based = 12;
$examt = 4;

$start = 0;
$end = 4;

$exceed = $end - 4;
$exceedamt = $exceed * $examt;//get the exceed amount for example total km 10 exceed 6km ($examt * $exceed)

$total = $end;
$fare =  $based + $exceedamt;


echo 'based km 4. based per km 12peso';
echo '<br> <br> total km '. $total;
echo '<br> exceed ' . $exceed;

echo '<br> ...<br>';
echo $based + $exceedamt;
echo '<br>';


echo '<br> total fare: ' . $fare . '<br>';

if ($total == 4) {
    echo 'equal to based km';
} elseif ($total <= 4) {
    echo 'less than equal to based km';
} elseif ($total >= 4 ){
    echo 'greater than equal to based km';
} else {
    echo 'n';
}

if ($end <= 4) {
    echo 'less';
} elseif ($end  >= 4) {
    echo 'less 1';
} else {
    echo 'non';
}*/
?>