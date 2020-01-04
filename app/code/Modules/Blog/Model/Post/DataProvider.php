<?php

namespace Modules\Blog\Model\Post;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Modules\Blog\Model\Post;
use Modules\Blog\Model\ResourceModel\Post\Collection;
use Modules\Blog\Model\ResourceModel\Post\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $postCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $postCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $postCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        /** @var Post $post */
        foreach ($items as $post) {
            $this->loadedData[$post->getId()] = $post->getData();
//            if ($post->getIcon()) {
//                $m['icon'][0]['name'] = $post->getIcon();
//                $m['icon'][0]['url'] = $this->getMediaUrl() . $post->getIcon();
//                $fullData = $this->loadedData;
//                $this->loadedData[$post->getId()] = array_merge($fullData[$post->getId()], $m);
//            }
        }

        $data = $this->dataPersistor->get('modules_blog_post');

        if (!empty($data)) {
            $post = $this->collection->getNewEmptyItem();
            $post->setData($data);
            $this->loadedData[$post->getId()] = $post->getData();
            $this->dataPersistor->clear('modules_blog_post');
        }

        return $this->loadedData;
    }

//    public function getMediaUrl()
//    {
//        $mediaUrl = $this->storeManager->getStore()
//                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'blog/tmp/icon/';
//        return $mediaUrl;
//    }
}