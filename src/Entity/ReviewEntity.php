<?php

namespace TMCms\Modules\Reviews;

use neTpyceB\TMCms\Orm\Entity;
use neTpyceB\TMCms\Modules\Images\ModuleImages;

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
    protected $db_table = 'm_reviews';
    protected $translation_fields = ['title', 'description', 'name'];

    protected function beforeDelete() {
        // Delete all related Images
        ModuleImages::deleteEntityImages($this);

        return $this;
    }
}