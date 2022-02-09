<?php

use Illuminate\Support\Facades\Cache;

/**
 * canada phone number format
 */

function phoneFormat($value)
{

    try {

        $phone = sprintf('+1%s', ltrim(phone($value, 'CA')->formatE164(), '+1'));

    } catch (\Exception $e) {
        $phone = $value;
    }
    return $phone;
}

/**
 * jwt auth
 */
if (!function_exists('jwt')) {
    function jwt()
    {
        return auth()->guard('api');
    }
}

/**
 * Slug Maker for any string
 */
function SlugMaker($value, $replacement = '_')
{
    $value = trim(strtolower($value));
    $value = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', $replacement, strtolower($value)));
    return $value;
}

/**
 * User permission check
 */
function check_permission_exist($permission, $matching_array)
{
    return in_array(explode('|', $permission)[0], $matching_array);

}

/**
 * this function check the user have permission to access this route
 */


function can_access_route($matching_data_value, $permission_data_array)
{
    //checking the current user is super admin

    //getting super admin role id
    $super_admin_role_id = config('app.super_admin_role_id');

    if (Auth::user()->role_type == $super_admin_role_id) {
        return true;
    }

    // checking the matching data type is array or string
    $matching_data_value_type = gettype($matching_data_value);
    if ($matching_data_value_type == 'string') {
        //checking the route name exist in permissions array
        return in_array($matching_data_value, $permission_data_array);
    } elseif ($matching_data_value_type == 'array') {
        //checking the route names exist in permissions array
        $matching_count = count(array_intersect($matching_data_value, $permission_data_array));
        return ($matching_count > 0) ? true : false;
    }

    // default return false
    return false;


}


/**
 * this function check the user have authority to view cards
 */

function can_view_cards($matching_value, $rights_array)
{
    //checking the current user is super admin

    //getting super admin role id
    $super_admin_role_id = config('app.super_admin_role_id');

    if (Auth::user()->role_type == $super_admin_role_id) {
        return true;
    }

    if ($rights_array) {
        //checking the card view rights exist name exist in permissions array
        return in_array($matching_value, $rights_array);
    }

    return false;

}

/**
 * this function check the user have permission access
 */


function HasPermissionAccess($user_type, $matching_data, $permissions_array)
{
    /*checking user type*/
    if ($user_type == 'admin') {
        return true;
    }

    /*now checking matching data data type */
    $matching_data_type = gettype($matching_data);
    if ($matching_data_type == 'array') {
        $match_data = count(array_intersect($permissions_array, $matching_data));
        if ($match_data > 0) {
            return true;
        }
    } elseif ($matching_data_type == 'string') {
        return in_array($matching_data, $permissions_array);
    }

    //default return typ false
    return false;

}
