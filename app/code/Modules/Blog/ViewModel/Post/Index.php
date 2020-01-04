<?php

namespace Modules\Blog\ViewModel\Post;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Modules\Blog\Api\Data\PostInterface;
use Modules\Blog\Helper\BlogConfigHelper;
use Modules\Blog\Model\ResourceModel\Post\Collection;
use Modules\Blog\Model\ResourceModel\Post\CollectionFactory;

class Index implements ArgumentInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var BlogConfigHelper
     */
    private $blogConfigHelper;

    /**
     * Index constructor.
     *
     * @param CollectionFactory $collectionFactory
     * @param BlogConfigHelper $blogConfigHelper
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        BlogConfigHelper $blogConfigHelper,
        UrlInterface $urlBuilder
    ) {
        $this->blogConfigHelper  = $blogConfigHelper;
        $this->urlBuilder        = $urlBuilder;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return Collection
     */
    public function getPostsCollection(): Collection
    {
        return $this->collectionFactory->create()->addFieldToFilter(PostInterface::ENABLED, 1)
            ->setPageSize($this->blogConfigHelper->getGeneralConfig());
    }

    /**
     * @param string $slug
     *
     * @return string
     */
    public function generateUrl($slug): string
    {
        return $this->urlBuilder->getUrl(null, ['blog' => $slug]);
    }
}