<?php
declare(strict_types=1);

namespace TMCms\Modules\Reviews\Entity;

use TMCms\Orm\Entity;
use TMCms\Modules\Images\ModuleImages;

/**
 * Class ReviewEntity
 * @package TMCms\Modules\Reviews
 *
 * @method bool getActive()
 * @method string getDescription()
 * @method string getImage()
 * @method string getName()
 * @method float getRating()
 * @method string getTitle()
 * @method string getTs()
 *
 * @method $this setTs(int $ts)
 */
class ReviewEntity extends Entity {
    protected $translation_fields = [ReviewEntityRepository::FIELD_TITLE, ReviewEntityRepository::FIELD_DESCRIPTION, ReviewEntityRepository::FIELD_NAME];

    /**
     * Auto-call before object is Deleted
     */
    protected function beforeDelete() {
        // Delete all related Images
        ModuleImages::deleteEntityImages($this);

        return $this;
    }

    protected function beforeSave()
    {
        $this->setTs(NOW);

        return $this;
    }
}
