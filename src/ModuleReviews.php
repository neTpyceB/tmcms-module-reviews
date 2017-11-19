<?php
declare(strict_types=1);

namespace TMCms\Modules\Reviews;

use TMCms\Modules\IModule;
use TMCms\Orm\Entity;
use TMCms\Traits\singletonInstanceTrait;
use TMCms\Modules\Reviews\Entity\ReviewEntityRepository;

/**
 * Class ModuleReviews
 * @package TMCms\Modules\Reviews
 */
class ModuleReviews implements IModule {
    use singletonInstanceTrait;

    public static $tables = [
        'reviews' => 'm_reviews'
    ];

    /**
     * @param Entity|null $entity
     *
     * @return ReviewEntityRepository
     */
    public static function getReviews(Entity $entity = null): ReviewEntityRepository
    {
        $reviews = new ReviewEntityRepository();
        $reviews->addOrderByField('ts', true);
        $reviews->setWhereActive(true);

        if ($entity) {
            // TODO link to entity by separate table
        }

        return $reviews;
    }
}
