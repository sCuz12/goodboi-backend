<?php

namespace App\Services\FileUploader;

use App\Models\DogListingImages;
use Exception;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image as Image;


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
     * param bool $update 
     *
     * @return void
     */
    function uploadImage(bool $update = false)
    {

        if ($update) {
            DogListingImages::where('dog_id', $this->dog_list_id)->delete();
        }
        //Loop through the files
        foreach ($this->files as $file) {
            try {
                $fileName = date('d-m-Y-H-i') . "_img_" .  uniqid() . "." . $file->getClientOriginalExtension();
                $image    = Image::make($file);
                unset($file);
                //$rezisedFile  =     $this->compress($image);
                $finishedFile = $this->addWatermark($image);
                $finishedFile->save(public_path() . SELF::LISTING_IMAGES_PATH . "/" . $fileName);
                $path = SELF::LISTING_IMAGES_PATH . $fileName;

                DogListingImages::create([
                    'name' => $fileName,
                    'url'  => $path,
                    'dog_id' => $this->dog_list_id,
                ]);
            } catch (Exception $e) {
                //TODO : Log 
                continue;
            }
        }
    }

    /**
     * compress
     *
     * @param  UploadedFile $file
     * @return UploadedFile
     */
    public function compress($file)
    {
        return $file
            ->crop(1152, 2048);
    }

    /**
     * Adds watermark to the photo
     *
     * @param  UploadedFile $file
     * @return UploadedFile
     */
    public function addWatermark($file)
    {
        $file->insert(public_path('/images/watermark/goodboi_watermark.png'), 'bottom-right', 1, 1);
        return $file;
    }
}
