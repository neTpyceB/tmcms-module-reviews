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
    protected $translation_fields = ['title', 'description', 'name'];
    protected $table_structure = [
        'fields' => [
            'title' => [
                'type' => 'translation',
            ],
            'description' => [
                'type' => 'translation',
            ],
            'name' => [
                'type' => 'translation',
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