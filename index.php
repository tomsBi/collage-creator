<?php

require_once 'vendor/autoload.php';

use App\ImageCollage;
use Ramsey\Uuid\Uuid;

$uuid = Uuid::uuid4();
$collage = new ImageCollage(1810, 1088);

for($x=1; $x <= 10; $x++){
    $collage->addPadding("assets\\".$x.".png", $x);
}

for($x=1; $x <= 5; $x++){
    $image = imagecreatefrompng("temp\\".$x.".png");
    $collage->putImage($image, 1, 1, $x-1, 0);
}
for($x=6; $x <= 10; $x++){
    $image = imagecreatefrompng("temp\\".$x.".png");
    $collage->putImage($image, 1, 1, $x-6, 1);
}

// $collage->display();

$collage->saveCollage("collage/{$uuid}.png");