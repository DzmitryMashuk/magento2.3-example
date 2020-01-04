<?php

namespace Modules\Blog\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Modules\Blog\Api\Data\PostInterface;

class Post extends AbstractModel implements IdentityInterface, PostInterface
{
    const CACHE_TAG       = 'modules_blog_post';
    const STATUS_ENABLED  = 1;
    const STATUS_DISABLED = 0;

    protected $_cacheTag    = 'modules_blog_post';
    protected $_eventPrefix = 'modules_blog_post';

    protected function _construct()
    {
        $this->_init(ResourceModel\Post::class);
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @param int $id
     *
     * @return PostInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_getData(self::TITLE);
    }

    /**
     * @param string $title
     *
     * @return PostInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->_getData(self::CONTENT);
    }

    /**
     * @param string $content
     *
     * @return PostInterface
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @return bool
     */
    public function getEnabled()
    {
        return $this->_getData(self::ENABLED);
    }

    /**
     * @param bool $enabled
     *
     * @return PostInterface
     */
    public function setEnabled($enabled)
    {
        return $this->setData(self::ENABLED, $enabled);
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->_getData(self::SLUG);
    }

    /**
     * @param string $slug
     *
     * @return PostInterface
     */
    public function setSlug($slug)
    {
        return $this->setData(self::SLUG, $slug);
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->_getData(self::IMAGE_PATH);
    }

    /**
     * @param string $imagePath
     *
     * @return PostInterface
     */
    public function setImagePath($imagePath)
    {
        return $this->setData(self::IMAGE_PATH, $imagePath);
    }

    /**
     * @return string
     */
    public function getCreateAt()
    {
        return $this->_getData(self::CREATE_AT);
    }

    /**
     * @param string $createAt
     *
     * @return PostInterface
     */
    public function setCreateAt($createAt)
    {
        return $this->setData(self::CREATE_AT, $createAt);
    }

    /**
     * @return string
     */
    public function getUpdateAt()
    {
        return $this->_getData(self::UPDATE_AT);
    }

    /**
     * @param string $updateAt
     *
     * @return PostInterface
     */
    public function setUpdateAt($updateAt)
    {
        return $this->setData(self::UPDATE_AT, $updateAt);
    }

    /**
     * @return string|null
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Prepare banner's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}