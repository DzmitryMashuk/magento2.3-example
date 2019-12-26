<?php

namespace Modules\Blog\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'modules_blog_post';

    protected $_cacheTag = 'modules_blog_post';

    protected $_eventPrefix = 'modules_blog_post';

    protected function _construct()
    {
        $this->_init('Modules\Blog\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}