<?php

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
 * @method string getTitle()
 */
class ReviewEntity extends Entity {
    protected $translation_fields = ['title', 'description', 'name'];

    protected function beforeDelete() {
        // Delete all related Images
        ModuleImages::deleteEntityImages($this);

        return $this;
    }
}