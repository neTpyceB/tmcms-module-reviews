<?php
declare(strict_types=1);

namespace TMCms\Modules\Reviews;

use TMCms\Admin\Messages;
use TMCms\HTML\BreadCrumbs;
use TMCms\HTML\Cms\CmsForm;
use TMCms\HTML\Cms\CmsFormHelper;
use TMCms\HTML\Cms\CmsTableHelper;
use TMCms\Log\App;
use TMCms\Modules\Gallery\ModuleGallery;
use TMCms\Modules\Images\Entity\ImageEntityRepository;
use TMCms\Modules\Reviews\Entity\ReviewEntity;
use TMCms\Modules\Reviews\Entity\ReviewEntityRepository;

/**
 * Class CmsReviews
 *
 * @package TMCms\Modules\Reviews
 */
class CmsReviews {
    public function _default()
    {
        BreadCrumbs::getInstance()
            ->addCrumb('All Reviews')
            ->addAction('Add Review', '?p=' . P . '&do=add')
        ;

        $reviews = new ReviewEntityRepository();
        $reviews->addOrderByField('ts', true);

        $images = new ImageEntityRepository();
        $images->setWhereItemType($reviews);

        echo CmsTableHelper::outputTable([
            'data' => $reviews,
            'columns' => [
                'ts' => [
                    'title' => 'Date',
                    'type' => 'datetime',
                ],
                'name' => [
                    'translation' => true,
                ],
                'images'     => [
                    'href'   => '?p=' . P . '&do=images&review_id={%id%}',
                    'type'   => 'gallery',
                    'images' => $images,
                ],
            ],
            'active' => true,
            'edit' => true,
            'delete' => true,
        ]);
    }

    /**
     * @param null $data
     *
     * @return CmsForm
     */
    private function _reviews_form($data = NULL): CmsForm
    {
        $form_array = [
            'data' => $data,
            'action' => '?p=' . P . '&do=_add',
            'button' => 'Add',
            'fields' => [
                'image' => [
                    'edit' => 'files',
                    'path' => DIR_IMAGES_URL . 'reviews'
                ],
                'ts' => [
                    'title' => __('Date'),
                    'type' => 'date',
                ],
                'name' => [
                    'translation' => true
                ],
                'title' => [
                    'translation' => true
                ],
                'description' => [
                    'type' => 'textarea',
                    'translation' => true
                ],
            ]
        ];

        return CmsFormHelper::outputForm(ModuleReviews::$tables['reviews'],
            $form_array
        );
    }

    public function add()
    {
        BreadCrumbs::getInstance()
            ->addCrumb('Add Review')
        ;

        echo $this->_reviews_form();
    }

    public function edit()
    {
        $id = abs((int)$_GET['id']);
        if (!$id) {
            return;
        }

        $review = new ReviewEntity($id);

        echo BreadCrumbs::getInstance()
            ->addCrumb(ucfirst(P), '?p='. P)
            ->addCrumb('Edit Review')
            ->addCrumb($review->getTitle())
        ;

        echo $this->_reviews_form($review)
            ->setAction('?p=' . P . '&do=_edit&id=' . $id)
            ->setButtonSubmit('Update');
    }

    public function _add()
    {
        $_POST['ts'] = strtotime($_POST['ts']);

        $review = new ReviewEntity;
        $review->loadDataFromArray($_POST);
        $review->save();

        App::add('Review "' . $review->getTitle() . '" added');

        Messages::sendMessage('Review added');

        go('?p='. P .'&highlight='. $review->getId());
    }

    public function _edit()
    {
        $id = abs((int)$_GET['id']);
        if (!$id) {
            return;
        }

        $review = new ReviewEntity($id);
        $review->loadDataFromArray($_POST);
        $review->save();

        App::add('Review "' . $review->getTitle() . '" edited');

        Messages::sendMessage('Review updated');

        go('?p='. P .'&highlight='. $review->getId());
    }

    public function _active()
    {
        $id = abs((int)$_GET['id']);
        if (!$id) {
            return;
        }

        $review = new ReviewEntity($id);
        $review->flipBoolValue('active');
        $review->save();

        App::add('Review "' . $review->getTitle() . '" ' . ($review->getActive() ? '' : 'de') . 'activated');

        Messages::sendMessage('Review updated');

        if (IS_AJAX_REQUEST) {
            die('1');
        }
        back();
    }


    public function _delete()
    {
        $id = abs((int)$_GET['id']);
        if (!$id) {
            return;
        }

        $review = new ReviewEntity($id);
        $review->deleteObject();

        App::add('Review "' . $review->getTitle() . '" deleted');

        Messages::sendMessage('Review deleted');

        back();
    }


    /** IMAGES */
    public function images() {
        $id = abs((int)$_GET['review_id']);
        if (!$id) {
            return;
        }

        $review = new ReviewEntity($id);

        echo ModuleGallery::getViewForCmsModules($review);
    }

    public function _images_delete() {
        $id = abs((int)$_GET['id']);
        if (!$id) {
            return;
        }

        ModuleGallery::deleteImageForCmsModules($id);

        back();
    }

    public function _images_move() {
        $id = abs((int)$_GET['id']);
        if (!$id) {
            return;
        }

        ModuleGallery::orderImageForCmsModules($id, $_GET['direct']);

        back();
    }
}
