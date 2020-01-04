<?php

namespace Modules\Blog\ViewModel\Post;

use Modules\Blog\Api\Data\PostInterface;
use Modules\Blog\Model\Post;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Modules\Blog\Model\Post\ImageUploader;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class View implements ArgumentInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ImageUploader
     */
    private $imageUploader;
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(
        Registry $registry,
        ProductRepository $productRepository,
        ImageUploader $imageUploader,
        UrlInterface $url,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->url               = $url;
        $this->imageUploader     = $imageUploader;
        $this->registry          = $registry;
    }

    /**
     * @return Post
     */
    private function getPost(): Post
    {
        return $this->registry->registry('current_post');
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getPost()->getTitle();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getImagePath(): string
    {
        return $this->getPost()->getImagePath();
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->getPost()->getContent();
    }

    /**
     * @return string
     */
    public function getCreateAt(): string
    {
        return $this->getPost()->getCreateAt();
    }

    /**
     * @return bool
     */
    public function isProductId(): bool
    {
        $productId = $this->getPost()->getProductId();
        return isset($productId);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getProductUrl(): string
    {
        $product = $this->getProduct($this->getPost()->getProductId());
        if (isset($product)) {
            return $product->getProductUrl();
        } else {
            return '';
        }
    }

    /**
     * @param $productEntityId
     *
     * @return ProductInterface|null
     * @throws NoSuchEntityException
     */
    private function getProduct($productEntityId): ?ProductInterface
    {
        if (!isset($productEntityId)) {
            return null;
        }
        return $this->productRepository->getById($productEntityId);
    }
}