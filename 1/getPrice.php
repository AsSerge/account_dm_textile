<?php
header("Content-Type: image/png");

$price_arr[0] = $_GET['a100'];
$price_arr[1] = $_GET['a95'];
$price_arr[2] = $_GET['a92'];
$price_arr[3] = $_GET['dt'];


// Создаем новый прозрачный рисунок
$image = imagecreatetruecolor(230, 341); // 230x341px
imagesavealpha($image, true);
imagealphablending($image, false);
$transparent = imagecolorallocatealpha($image, 0, 0, 0, 127); // 127 - задает серый цвет фона, который игнорируется
imagefill($image, 0, 0, $transparent);


// Устанавливаем шрифт размер и цвет букв
$font = $_SERVER['DOCUMENT_ROOT'].'/1/FuturaDemiCTT Normal.ttf'; // Путь к файлу шрифта;
$font_size = 40; // Размер шрифта
$text_color = imagecolorallocate($image, 255, 255, 255);


// Надписи
// $price_arr = ['63.90', '53.30', '48.10', '55.30'];  //Массив цен// Добавляем надписи
$start_pos_x = 95; // Позиция по горизонтали (X)
$start_pos_y = 56; // Позиция по вертикали (Y)
$offset_y = 86; // Смещение по вертикали
foreach($price_arr as $pr){
	imagettftext($image, $font_size, 0, $start_pos_x, $start_pos_y, $text_color, $font, $pr);
	$start_pos_y = $start_pos_y + $offset_y;
}

// Пиктограммы
$icon_arr = ['icon1.png', 'icon2.png', 'icon3.png', 'icon4.png'];  //Массив пиктограмм
$start_pos_x = 2; // Позиция по горизонтали (X)
$start_pos_y = 2; // Позиция по вертикали (Y)
$offset_y = 87; // Смещение по вертикали
foreach($icon_arr as $ico){
	imagecopy($image, imagecreatefrompng($ico), $start_pos_x, $start_pos_y, 0, 0, imagesx(imagecreatefrompng($ico)), imagesy(imagecreatefrompng($ico)));
	$start_pos_y = $start_pos_y + $offset_y;	
}
// Выводим файл
imagepng($image);

// Сохраняем изображение на жесткий диск
imagepng($image, 'image.png');

// Освобождаем память
imagedestroy($image)
?>