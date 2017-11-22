<?php
declare(strict_types=1);

namespace TMCms\Modules\Reviews\Entity;

use TMCms\Orm\EntityRepository;
use TMCms\Orm\TableStructure;

/**
 * Class ReviewAttachmentEntityRepository
 * @package TMCms\Modules\Reviews\Entity
 *
 * @method $this setWhereEntityType(string $type)
 * @method $this setWhereEntityId(int $id)
 */
class ReviewAttachmentEntityRepository extends EntityRepository {
    protected $table_structure = [
        'fields' => [
            'entity_id' => [
                'type' => TableStructure::FIELD_TYPE_INDEX,
            ],
            'review_id' => [
                'type' => TableStructure::FIELD_TYPE_INDEX,
            ],
            'entity_type' => [
                'type' => TableStructure::FIELD_TYPE_VARCHAR_255,
            ],
        ],
    ];
}
