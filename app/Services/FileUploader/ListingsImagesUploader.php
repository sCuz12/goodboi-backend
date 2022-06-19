<?php

namespace App\Services\FileUploader;

use App\Models\DogListingImages;
use Carbon\Carbon;

class ListingsImagesUploader implements ImagePhotoUploaderInterface
{
    const LISTING_IMAGES_PATH  = '/images/listings/';

    private $files;
    private $title;
    private $dog_list_id; // The id of the listing dog


    public function __construct($files, $title, $dog_list_id)
    {
        $this->files = $files;
        $this->title = $title;
        $this->dog_list_id = $dog_list_id;
        return $this;
    }


    /**
     * Uploads all the images for the listings 
     *
     * @return void
     */
    function uploadImage()
    {
        //Loop through the files
        foreach ($this->files as $file) {
            $fileName = date('d-m-Y-H-i') . "_img_" .  uniqid();
            $file->move(public_path() . SELF::LISTING_IMAGES_PATH, $fileName);
            $path = SELF::LISTING_IMAGES_PATH . $fileName;
            DogListingImages::create([
                'name' => $fileName,
                'url'  => $path,
                'dog_id' => $this->dog_list_id,
            ]);
        }
    }
}
