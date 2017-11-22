<?php
declare(strict_types=1);

namespace TMCms\Modules\Reviews;

use TMCms\Admin\Messages;
use TMCms\HTML\BreadCrumbs;
use TMCms\HTML\Cms\CmsForm;
use TMCms\HTML\Cms\CmsFormHelper;
use TMCms\Log\App;
use TMCms\Modules\IModule;
use TMCms\Modules\Reviews\Entity\ReviewAttachmentEntity;
use TMCms\Modules\Reviews\Entity\ReviewAttachmentEntityRepository;
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
     * @param Entity|null $entity if you need to limit reviews by entity only
     *
     * @return ReviewEntityRepository
     */
    public static function getReviews(Entity $entity = null): ReviewEntityRepository
    {
        $reviews = new ReviewEntityRepository();
        $reviews->addOrderByField('ts', true);
        $reviews->setWhereActive(true);

        if ($entity) {
            $attachments = new ReviewAttachmentEntityRepository();
            $attachments->setWhereEntityId($entity->getId());
            $attachments->setWhereEntityType($entity->getUnqualifiedShortClassName());

            $reviews->mergeWithCollection($attachments, 'id', 'review_id');
        }

        return $reviews;
    }

    /**
     * @param Entity $entity
     *
     * @return CmsForm
     */
    public static function getAttachmentsViewForCmsModules(Entity $entity): CmsForm
    {
        BreadCrumbs::getInstance()
            ->addCrumb(__(P_DO))
            ->addCrumb(__($entity->getUnqualifiedShortClassName()))
            ->addCrumb($entity->getId());

        $attachments = new ReviewAttachmentEntityRepository();
        $attachments->setWhereEntityType($entity->getUnqualifiedShortClassName());
        $attachments->setWhereEntityId($entity->getId());

        $reviews = new ReviewEntityRepository();

        return CmsFormHelper::outputForm([
            'action' => '?p='. P .'&do=_reviews_add',
            'button' => __('Update'),
            'fields' => [
                'review_id' => [
                    'type' => 'hidden',
                    'value' => $entity->getId(),
                ],
                'entity_id' => [
                    'type' => 'hidden',
                    'value' => $entity->getId(),
                ],
                'entity_type' => [
                    'type' => 'hidden',
                    'value' => $entity->getUnqualifiedShortClassName(),
                ],
                'attachments' => [
                    'type' => 'multiselect',
                    'options' => $reviews->getPairs('title'),
                    'selected' => $attachments->getPairs('review_id'),
                ],
            ],
        ]);
    }

    public static function createAttachmentsForCmsModules()
    {
        // Create existing
        $attachments = new ReviewAttachmentEntityRepository();
        $attachments->setWhereEntityType($_POST['entity_type']);
        $attachments->setWhereEntityId($_POST['entity_id']);

        $attachments->deleteObjectCollection();

        // Create new attachments
        foreach ($_POST['attachments'] as $review_id) {
            $attachment = new ReviewAttachmentEntity();
            $attachment->setReviewId($review_id);
            $attachment->setEntityId($_POST['entity_id']);
            $attachment->setEntityType($_POST['entity_type']);
            $attachment->save();
        }

        Messages::sendGreenAlert('Review attachments updated');
        App::add('Review attachments updated for Entity "'. $_POST['entity_type'] .'" with id "'. $_POST['entity_id'] .'"');

        back();
    }
}
