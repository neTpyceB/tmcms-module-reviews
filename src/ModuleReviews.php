<?php

namespace TMCms\Modules\Reviews;

use TMCms\Modules\IModule;
use TMCms\Traits\singletonInstanceTrait;
use TMCms\Modules\Reviews\Entity\ReviewEntityRepository;

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