<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

function getCloudinaryInstance() {
    return new Cloudinary([
        'cloud' => [
            'cloud_name' => CLOUDINARY_CLOUD_NAME,
            'api_key' => CLOUDINARY_API_KEY,
            'api_secret' => CLOUDINARY_API_SECRET,
        ],
    ]);
}