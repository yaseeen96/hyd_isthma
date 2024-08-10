<?php

namespace App\Helpers;

class AppHelperFunctions {
    public static function getRedBadge($title) {
        return '<span class="badge badge-danger">'.$title.'</span>';
    }
    public static function getGreenBadge($title) {
        return '<span class="badge badge-success">'.$title.'</span>';
    }
}