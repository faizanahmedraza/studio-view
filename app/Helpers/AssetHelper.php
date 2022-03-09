<?php

/**
 * AssetHelper
 *
 */

/**
 * Return's admin assets directory
 *
 * CALLING PROCEDURE
 *
 * In controller call it like this:
 * $adminAssetsDirectory = adminAssetsDir() . $site_settings->site_logo;
 *
 * In View call it like this:
 * {{ asset(adminAssetsDir() . $site_settings->site_logo) }}
 *
 * @param string $role
 *
 * @return bool
 */

/**
 * used for backend url
 */
function backend_url($uri = '/')
{
    return call_user_func_array('url', ['admin/' . ltrim($uri, '/')] + func_get_args());
}

/**
 * used for backend view
 */
function backend_view($file)
{
    return call_user_func_array('view', ['admin/' . $file] + func_get_args());
}

/**
 * user upload file save path
 */
function backendUserFile()
{
    return 'backends/users/';
}



/**
 * user upload file get path
 */
function backendUserUrl($file = '')
{
    return $file != '' && file_exists('backends/users/' . $file) ? url('backends/users') . '/' . $file : '';
}

/**
 * training  upload file  save path
 */

function backendTrainingFile()
{
    return 'backends/training/';
}

/**
 *   upload file path
 */
function backendDocumentVerificationFile()
{
    return 'backends/training/';
}

/**
 * training upload file  get path
 */
function backendTrainingUrl($file = '')
{
    return $file != '' && file_exists('backends/training/' . $file) ? url('backends/training') . '/' . $file : '';
}


/**
 * any contants
 */
function constants($key)
{
    return config('constants.' . $key);
}


function adminHasAssets($image)
{
    if (!empty($image) && file_exists(uploadsDir() . $image)) {
        return true;
    } else {
        return false;
    }
}

function defaultStoreCoverUrl()
{
    return 'assets/front/images/store-cover.png';
}

function getFileExtension($filename, $offset = 3)
{
    return substr(strtolower($filename), strlen($filename) - $offset, $offset);
}

/**
 * studios upload file save path
 */
function backendStudioFile()
{
    return 'backends/studios/';
}

/**
 * upload Studio image base64
 */
function uploadImageStudio($base64Image,$studioName)
{
        $slug = \Str::slug(trim($studioName), '-');

        $image = $base64Image;  // your base64 encoded

        $image_parts = explode(";base64,", $image);

        $image_type_aux = explode("image/", $image_parts[0]);

        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);

        $file = backendStudioFile() . $slug . '-' . time(). '-' . uniqid() . '.'.$image_type;

        file_put_contents($file, $image_base64);

        return url($file);
}
