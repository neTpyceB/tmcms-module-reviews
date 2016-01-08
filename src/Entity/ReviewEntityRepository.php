<?php

namespace TMCms\Modules\Reviews\Entity;

use TMCms\Orm\EntityRepository;

/**
 * Class ReviewEntityRepository
 * @package TMCms\Modules\Reviews
 *
 * @method $this setWhereActive(bool $flag)
 */
class ReviewEntityRepository extends EntityRepository {
    protected $db_table = 'm_reviews';
    protected $translation_fields = ['title', 'description', 'name'];
}