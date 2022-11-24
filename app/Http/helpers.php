<?php
/**
 * Created by PhpStorm.
 * User: Napster
 * Date: 5/7/2020
 * Time: 11:08 PM
 */


if (!function_exists('ul_link')) {
    function ul_link($link)
    {
        $one = route($link);
        return $one;
    }
}

if (!function_exists('path')) {
    function path()
    {
        $one = url('/') . \App\Setting::first()->public;
        return $one;
    }
}

if (!function_exists('user')) {
    function user()
    {
        $one = \Illuminate\Support\Facades\Auth::user();
        return $one;
    }
}

if (!function_exists('current_route')) {
    function current_route($x)
    {
        if (\Route::current()->getName() == $x) {
            return "active";
        } else {
            return "";
        }
    }
}

if (!function_exists('setting')) {
    function setting()
    {
        return \App\Setting::first();
    }
}

if (!function_exists('HPContactUS')) {
    function HPContactUS()
    {
        return \App\HPContactUS::first();
    }
}

if (!function_exists('posts')) {
    function posts($x)
    {
        return \App\Post::where("featured",1)->where("type",$x)->get();
    }
}

if (!function_exists('social_media')) {
    function social_media()
    {
        return \App\Contents::where("active",1)->where("type","social_media")->get();
    }
}

if (!function_exists('percentage')) {
    function percentage($percentage = 50, $totalWidth = 350)
    {
        $new_width = ($percentage / $totalWidth) * $totalWidth;
        return $new_width;
    }
}

if (!function_exists('percentage1')) {
    function percentage1($percentage = 50, $totalWidth = 350)
    {
        $new_width = ($percentage / 100) * $totalWidth;
        return $new_width;
    }
}
