<?php

namespace App\Helpers;

use App\Models\AdPlacement;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Ads
{
    /**
     * @param $placement_name
     * @return mixed
     */
    public static function checkAd($placement_name) {
        return AdPlacement::whereSlug($placement_name)->with('ad')->exists();
    }

    public static function checkFileAd($placement_name) {
        $filename = AdPlacement::whereSlug($placement_name)->first()->ad->image;
        return Storage::disk('public')->exists('ad/' . $filename);
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function checkAdPlacementActive($name) {
        return AdPlacement::whereSlug($name)->whereActive('y')->exists();
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function checkAdActive($name) {
        return Advertisement::whereName($name)->whereActive('y')->exists();
    }

    public static function url($placement_name) {
        if ( self::checkAd($placement_name) ) {
            return AdPlacement::whereSlug($placement_name)->first()->ad->url;
        }
        return '#';
    }

    public static function image($placement_name) {
        $image = '';
        if ( self::checkAd($placement_name) ) {
            $image = AdPlacement::whereSlug($placement_name)->first()->ad->image;
        }
        return route('ad.image', $image);
    }

    public static function type($placement_name) {
        $type = null;
        if ( self::checkAd($placement_name) ) {
            $type = AdPlacement::whereSlug($placement_name)->first()->ad->type;
        }
        return $type;
    }

    public static function checkFileScript($placement_name) {
        $name = null;
        if ( self::checkAd($placement_name) ) {
            $name = AdPlacement::whereSlug($placement_name)->first()->ad->name;
        }
        return Str::slug($name) .'-script.blade.php';
    }

    public static function checkFileGa($placement_name) {
        $name = null;
        if ( self::checkAd($placement_name) ) {
            $name = AdPlacement::whereSlug($placement_name)->first()->ad->name;
        }
        return Str::slug($name) .'-ga.blade.php';
    }

    public static function viewFileScript($placement_name) {
        $name = null;
        if ( self::checkAd($placement_name) ) {
            $name = AdPlacement::whereSlug($placement_name)->first()->ad->name;
        }
        return Str::slug($name) .'-script';
    }

    public static function viewFileGa($placement_name) {
        $name = null;
        if ( self::checkAd($placement_name) ) {
            $name = AdPlacement::whereSlug($placement_name)->first()->ad->name;
        }
        return Str::slug($name) .'-ga';
    }
}
