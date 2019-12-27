<?php

namespace Modules\Blog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Modules\Blog\Api\Data\PostInterface;
use Modules\Blog\Api\Data\PostSearchResultInterface;

interface PostRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return PostInterface
     * @throws NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param PostInterface $post
     *
     * @return PostInterface
     */
    public function save(PostInterface $post);

    /**
     * @param PostInterface $post
     *
     * @return void
     */
    public function delete(PostInterface $post);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return PostSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}