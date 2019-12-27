<?php

namespace Modules\Blog\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PostSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return PostInterface[]
     */
    public function getItems();

    /**
     * @param PostInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}