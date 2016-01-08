<?php

namespace TMCms\Modules\Reviews;

use TMCms\Admin\Messages;
use TMCms\HTML\BreadCrumbs;
use TMCms\HTML\Cms\CmsFormHelper;
use TMCms\HTML\Cms\CmsTable;
use TMCms\HTML\Cms\Column\ColumnActive;
use TMCms\HTML\Cms\Column\ColumnData;
use TMCms\HTML\Cms\Column\ColumnDelete;
use TMCms\HTML\Cms\Column\ColumnEdit;
use TMCms\HTML\Cms\Column\ColumnImg;
use TMCms\HTML\Cms\Columns;
use TMCms\Log\App;
use TMCms\Modules\Gallery\ModuleGallery;
use TMCms\Modules\Images\Object\ImageCollection;
use TMCms\Modules\Reviews\Entity\ReviewEntity;
use TMCms\Modules\Reviews\Entity\ReviewEntityRepository;

class CmsReviews {
    public function _default()
    {
        $breadcrumbs = BreadCrumbs::getInstance()
            ->addCrumb(ucfirst(P))
            ->addCrumb('All Reviews')
        ;

        $reviews = new ReviewEntityRepository();
        $reviews->addSimpleSelectFields(['id', 'title', 'active', 'image']);
        $reviews->addSimpleSelectFieldsAsString('"0" as `images`');
        $reviews->addOrderByField('id');

        $images = new ImageCollection();
        $images->addSimpleSelectFieldsAsString('(SELECT COUNT(*) FROM `'. $images->getDbTableName() .'` WHERE `item_id` = `'. $reviews->getDbTableName() .'`.`id` AND `item_type` = "review") AS `images`');

        $reviews->mergeWithCollection($images, 'id', 'item_id', 'left');


        $table = CmsTable::getInstance()
            ->addData($reviews)
            ->addColumn(ColumnImg::getInstance('image')->imgHeight('100'))
            ->addColumn(ColumnEdit::getInstance('title')
                ->enableOrderableColumn()
                ->enableTranslationColumn()
                ->setHref('?p=' . P . '&do=edit&id={%id%}')
            )
            ->addColumn(ColumnData::getInstance('images')
                ->enableOrderableColumn()
                ->setWidth('1%')
                ->setAlignRight()
                ->setHref('?p='. P .'&do=images&id={%id%}')
            )
            ->addColumn(ColumnActive::getInstance('active')
                ->setHref('?p=' . P . '&do=_active&id={%id%}')
                ->enableOrderableColumn()
                ->enableAjax()
            )
            ->addColumn(ColumnDelete::getInstance('delete')
                ->setHref('?p=' . P . '&do=_delete&id={%id%}')
            )
        ;

        echo Columns::getInstance()
            ->add($breadcrumbs)
            ->add('<a class="btn btn-success" href="?p=' . P . '&do=add">Add Review</a>', ['align' => 'right'])
        ;

        echo $table;
    }

    public function __reviews_form($data = NULL)
    {
        $form_array = [
            'data' => $data,
            'action' => '?p=' . P . '&do=_add',
            'button' => 'Add',
            'fields' => [
                'image' => [
                    'edit' => 'files',
                    'path' => DIR_IMAGES_URL .'reviews'
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
        echo BreadCrumbs::getInstance()
            ->addCrumb(ucfirst(P))
            ->addCrumb('Add Review')
        ;

        echo self::__reviews_form();
    }
    
    public function edit()
    {
        $id = abs((int)$_GET['id']);
        if (!$id) return;

        $review = new ReviewEntity($id);

        echo BreadCrumbs::getInstance()
            ->addCrumb(ucfirst(P), '?p='. P)
            ->addCrumb('Edit Review')
            ->addCrumb($review->getTitle())
        ;

        echo self::__reviews_form($review)
            ->setAction('?p=' . P . '&do=_edit&id=' . $id)
            ->setSubmitButton('Update');
    }

    public function _add()
    {
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
        if (!$id) return;

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
        if (!$id) return;

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
        if (!$id) return;

        $review = new ReviewEntity($id);
        $review->deleteObject();

        App::add('Review "' . $review->getTitle() . '" deleted');

        Messages::sendMessage('Review deleted');

        back();
    }


    /** IMAGES */
    public function images() {
        $id = abs((int)$_GET['id']);
        if (!$id) return;

        $review = new ReviewEntity($id);

        echo ModuleGallery::getViewForCmsModules($review);
    }

    public function _images_delete() {
        $id = abs((int)$_GET['id']);
        if (!$id) return;

        ModuleGallery::deleteImageForCmsModules($id);

        back();
    }

    public function _images_move() {
        $id = abs((int)$_GET['id']);
        if (!$id) return;

        ModuleGallery::orderImageForCmsModules($id, $_GET['direct']);

        back();
    }
}