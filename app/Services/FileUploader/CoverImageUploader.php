<?php

namespace App\Services\FileUploader;

use App\Models\DogListingImages;
use App\Enums\CoverImagesPathEnum as CoverImageEnum;

class CoverImageUploader implements ImagePhotoUploaderInterface
{

    private $file;
    private $type;
    private $path;


    public function __construct($file, $type)
    {
        if (!in_array($type, CoverImageEnum::ALLOWED_TYPES)) {
            return false;
        }
        $this->file = $file;
        $this->type = $type;
    }

    /**
     * Uploads image to cover_images and returns the filename
     *
     * @return string
     */
    public function uploadImage()
    {
        switch ($this->type) {
            case "users":
                $fileName = date('d-m-Y-H-i') . "_img_" .  uniqid() . "." . $this->file->getClientOriginalExtension();
                $this->path = $this->file->move(public_path(CoverImageEnum::USERS), $fileName);
                break;
            case "listings":
                $fileName = date('d-m-Y-H-i') . "_img_" .  uniqid();
                $this->path = $this->file->move(public_path(CoverImageEnum::LISTINGS), $fileName);
                break;
            default:
                return false;
        }
        return $fileName;
    }
}
