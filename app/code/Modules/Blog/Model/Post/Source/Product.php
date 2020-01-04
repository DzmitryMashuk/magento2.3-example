<?php

namespace Modules\Blog\Model\Post\Source;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Product implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;

    public function __construct(CollectionFactory $productCollectionFactory)
    {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function toOptionArray()
    {
        /**
         * @var \Magento\Catalog\Model\Product $product ;
         */
        $products        = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*');
        $productNameList = [];
        foreach ($products as $product) {
            $productNameList[] = ['value' => $product->getEntityId(), 'label' => $product->getName()];
        }
        return $productNameList;
    }
}