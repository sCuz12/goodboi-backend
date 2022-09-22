<?php

namespace App\Services\FileUploader;

use App\Models\DogListingImages;
use App\Enums\CoverImagesPathEnum as CoverImageEnum;
use Intervention\Image\Facades\Image as Image;


class CoverImageUploader implements ImagePhotoUploaderInterface
{

    private $file;
    private $type;
    private $fileExtension;


    public function __construct($file, $type)
    {
        if (!in_array($type, CoverImageEnum::ALLOWED_TYPES)) {
            return false;
        }
        $this->file = $file;
        $this->type = $type;
        $this->fileExtension = $file->getClientOriginalExtension();
    }

    /**
     * Resize the picture into 1080 x 1080
     * 
     * @return self
     */
    public function resize()
    {
        $this->file = Image::make($this->file)->fit(800, 800);
        return $this;
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
                $fileName = date('d-m-Y-H-i') . "_img_" .  uniqid() . "." . $this->fileExtension;

                $this->file->save(public_path(CoverImageEnum::USERS) . "/" . $fileName);
                break;
            case "listings":
                $fileName = date('d-m-Y-H-i') . "_img_" .  uniqid() . "." . $this->fileExtension;
                $this->file->save(public_path(CoverImageEnum::LISTINGS) . "/" . $fileName);
                break;
            default:
                return false;
        }
        return $fileName;
    }
}
