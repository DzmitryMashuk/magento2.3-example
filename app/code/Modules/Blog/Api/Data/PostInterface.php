<?php

namespace Modules\Blog\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface PostInterface extends ExtensibleDataInterface
{
    const ID         = 'id';
    const TITLE      = 'title';
    const CONTENT    = 'content';
    const ENABLED    = 'enabled';
    const SLUG       = 'slug';
    const IMAGE_PATH = 'image_path';
    const CREATE_AT  = 'create_at';
    const UPDATE_AT  = 'update_at';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return PostInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return PostInterface
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return PostInterface
     */
    public function setContent($content);

    /**
     * @return bool
     */
    public function getEnabled();

    /**
     * @param bool $enabled
     * @return PostInterface
     */
    public function setEnabled($enabled);

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $slug
     * @return PostInterface
     */
    public function setSlug($slug);

    /**
     * @return string
     */
    public function getImagePath();

    /**
     * @param string $imagePath
     * @return PostInterface
     */
    public function setImagePath($imagePath);

    /**
     * @return string
     */
    public function getCreateAt();

    /**
     * @param string $createAt
     * @return PostInterface
     */
    public function setCreateAt($createAt);

    /**
     * @return string
     */
    public function getUpdateAt();

    /**
     * @param string $updateAt
     * @return PostInterface
     */
    public function setUpdateAt($updateAt);
}