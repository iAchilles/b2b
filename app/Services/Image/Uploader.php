<?php
namespace App\Services\Image;

use App\Entities\Image;
use Ramsey\Uuid\Uuid;

/**
 * Uploader class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class Uploader
{

    /**
     * @var \Imagick
     */
    private $driver;

    /**
     * @var string User identifier
     */
    private $id;

    /**
     * @var string
     */
    private $data;


    /**
     * Uploader constructor.
     *
     * @param string $data Base64-encoded data of the image.
     * @param string $id User identifier.
     */
    public function __construct(string $data, string $id)
    {
        $this->driver = new \Imagick();
        $this->id     = $id;
        $this->data   = $data;
    }


    public function handle()
    {
        return $this->saveImageDisk();
    }


    private function saveImageDisk()
    {
        $data = explode(',', $this->data);
        $data = count($data) === 1 ? reset($data) : $data[ 1 ];

        $decodedData = base64_decode($data);

        if ($this->driver->readImageBlob($decodedData)) {
            $this->driver->setImageAlphaChannel(\Imagick::VIRTUALPIXELMETHOD_WHITE);
            $this->driver->setImageFormat('jpeg');

            $image = $this->saveImageDb();
            $path  = app()->basePath('/public/images/');
            $name  = $path . $image->getFile();

            $this->driver->writeImage($name);

            return $image->getId()->toString();
        }

        return null;
    }


    /**
     * @return Image
     */
    private function saveImageDb()
    {
        $image = new Image();
        $image->setFile(Uuid::uuid4()->toString() . '.jpg');
        app('em')->persist($image);
        app('em')->flush();

        return $image;
    }

}
