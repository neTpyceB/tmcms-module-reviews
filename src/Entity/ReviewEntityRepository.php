<?php

namespace TMCms\Modules\Reviews\Entity;

use TMCms\Orm\EntityRepository;
use TMCms\Orm\TableStructure;

/**
 * Class ReviewEntityRepository
 * @package TMCms\Modules\Reviews
 *
 * @method $this setWhereActive(bool $flag)
 */
class ReviewEntityRepository extends EntityRepository {
    protected $translation_fields = ['title', 'description', 'name'];
    protected $table_structure = [
        'fields' => [
            'title' => [
                'type' => TableStructure::FIELD_TYPE_TRANSLATION,
            ],
            'description' => [
                'type' => TableStructure::FIELD_TYPE_TRANSLATION,
            ],
            'name' => [
                'type' => TableStructure::FIELD_TYPE_TRANSLATION,
            ],
            'image' => [
                'type' => 'varchar',
            ],
            'active' => [
                'type' => 'bool',
            ],
        ],
    ];
}
