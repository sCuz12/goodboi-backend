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
    private $watermark;

    public function __construct($files, $title, $dog_list_id)
    {
        $this->files        = $files;
        $this->title        = $title;
        $this->dog_list_id  = $dog_list_id;
        $this->watermark    = Image::make(public_path('/images/watermark/goodboi_watermark.png'));
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
                $height = $finishedFile->height() / 4;            //get 1/4th of image height

                $width = $finishedFile->width() / 4;
                if ($finishedFile->fileSize() > 1000000) {
                    $finishedFile->resize($width, $height)->save(public_path() . SELF::LISTING_IMAGES_PATH . "/" . $fileName);
                } else {
                    $finishedFile->save(public_path() . SELF::LISTING_IMAGES_PATH . "/" . $fileName);
                }


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
        $resizePercentage = 75; //70% less then an actual image (play with this value)
        $watermarkSize    = round($file->width() * ((100 - $resizePercentage) / 100), 2); //watermark will be $resizePercentage less then the actual width of the image

        // resize watermark width keep height auto
        $this->watermark->resize($watermarkSize, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $file->insert($this->watermark, 'bottom-right', 2, 2);
        return $file;
    }
}
