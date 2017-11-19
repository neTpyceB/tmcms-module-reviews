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
    const FIELD_TITLE = 'title';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_NAME = 'name';

    protected $translation_fields = [self::FIELD_TITLE, self::FIELD_DESCRIPTION, self::FIELD_NAME];
    protected $table_structure = [
        'fields' => [
            self::FIELD_TITLE => [
                'type' => TableStructure::FIELD_TYPE_TRANSLATION,
            ],
            self::FIELD_DESCRIPTION => [
                'type' => TableStructure::FIELD_TYPE_TRANSLATION,
            ],
            self::FIELD_NAME => [
                'type' => TableStructure::FIELD_TYPE_TRANSLATION,
            ],
            'image' => [
                'type' => TableStructure::FIELD_TYPE_VARCHAR_255,
            ],
            'ts' => [
                'type' => TableStructure::FIELD_TYPE_UNSIGNED_INTEGER,
            ],
            'active' => [
                'type' => TableStructure::FIELD_TYPE_BOOL,
            ],
        ],
    ];
}
