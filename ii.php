<?php

// Вычисление медианы

function median($array) {
    $count = count($array);
    sort($array);
    $middle = floor(($count - 1) / 2);
    if ($count % 2) {
        $median = $array[$middle];
    } else {
        $low = $array[$middle];
        $high = $array[$middle + 1];
        $median = (($low + $high) / 2);
    }
    return $median;
}



for ($i = 0; $i < 100000; $i++) {
	$array[] = rand(1, 10000);
}

// $array = array(1,5,1,20);
echo "Медиана: " . median($array); // Output: 5

echo "<br>";

$seconds = array_sum($array) / count($array); // Подсчет среднего количества секунд 
echo "Среднее: " . $seconds;


$c = new order(1000);

echo "<br>";
echo $c->b;
echo "<br>";
echo $c->getORD(3);

class order{
	public function __construct($vvv) {
		$this->vvv = $vvv;
	}
	public $b = 200;

	public function getORD($a){
		return ($a + $b) * $this->vvv;
	}
}


?>