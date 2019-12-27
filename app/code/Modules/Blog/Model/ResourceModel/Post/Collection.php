<?php

namespace Modules\Blog\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Modules\Blog\Model\Post;
use Modules\Blog\Model\ResourceModel\Post as ResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'modules_blog_post_collection';
    protected $_eventObject = 'post_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Post::class, ResourceModel::class);
    }
}