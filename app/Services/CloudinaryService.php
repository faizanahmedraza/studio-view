<?php

namespace App\Services;

class CloudinaryService
{
    public static function upload($image, $publicId = '')
    {
        try {
            $result = cloudinary()->upload($image, ['public_id' => uniqid('studio-' . $publicId)]);
            return (object)['url' => $result->getPath(), 'secureUrl' => $result->getSecurePath(), 'publicId' => $result->getPublicId()];
        } catch (\Exception $e) {
        }
    }
}
