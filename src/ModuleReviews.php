<?php

namespace TMCms\Modules\Reviews;

use neTpyceB\TMCms\Modules\IModule;
use neTpyceB\TMCms\Traits\singletonInstanceTrait;

class ModuleReviews implements IModule {
    use singletonInstanceTrait;

    public static $tables = [
        'reviews' => 'm_reviews'
    ];

    public static function getReviews()
    {
        $reviews = new ReviewEntityRepository();
        $reviews->setWhereActive(true);
        return $reviews->getAsArrayOfObjects();
    }
}