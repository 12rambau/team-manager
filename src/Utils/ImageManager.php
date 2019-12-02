<?php

namespace App\Utils;

use App\Entity\Image;

class ImageManager
{
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }


    public function ManualImage(string $imageName): Image
    {
        $image = new Image();

        $image->setUpdatedAt(new \DateTime());

        $sourcePath = $this->projectDir . '/public/image/default/' . $imageName;

        $name = str_replace('.', '', uniqid('', true));
        if ($extension = pathinfo($sourcePath, PATHINFO_EXTENSION))
            $name = sprintf('%s.%s', $name, $extension);

        $copyPath = $this->projectDir . '/public/image/upload/' . $name;

        copy($sourcePath, $copyPath);
        
        $image->setFileName($name);

        return $image;
    }
}