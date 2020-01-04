<?php

namespace Modules\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Modules\Blog\Api\Data\PostInterface;
use Modules\Blog\Api\PostRepositoryInterface;
use Modules\Blog\Model\Post;
use Modules\Blog\Model\PostFactory;

/**
 * Save CMS page action.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var PostFactory
     */
    private $postFactory;

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * Save constructor.
     *
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param PostFactory $postFactory
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        PostFactory $postFactory,
        PostRepositoryInterface $postRepository
    ) {
        $this->dataProcessor  = $dataProcessor;
        $this->dataPersistor  = $dataPersistor;
        $this->postFactory    = $postFactory;
        $this->postRepository = $postRepository;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        $data           = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            if (isset($data['enabled'])) {
                $data['enabled'] = $data['enabled'] ? Post::STATUS_ENABLED : Post::STATUS_DISABLED;
            }

            if (isset($data['title'])) {
                $data['slug'] = strtolower($data['title']);
            }

            if (empty($data['id'])) {
                $data['id'] = null;
            }

            if (empty($data['product_id'])) {
                $data['product_id'] = null;
            }

            if (!empty($data['id'])) {
                try {
                    $model = $this->postRepository->getById($data['id']);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This post no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $model = $this->postFactory->create();
            }

            $data = $this->_filterImageData($data);
            $model->setData($data);

            try {
                $this->postRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the post.'));

                return $this->processResultRedirect($model, $resultRedirect, $data);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the post.'));
            }

            $this->dataPersistor->set('modules_blog_post', $data);

            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process result redirect
     *
     * @param PostInterface $model
     * @param Redirect $resultRedirect
     * @param array $data
     *
     * @return Redirect
     * @throws LocalizedException
     */
    private function processResultRedirect($model, $resultRedirect, $data)
    {
        if ($this->getRequest()->getParam('back', false) === 'duplicate') {
            $newPage = $this->postFactory->create(['data' => $data]);
            $newPage->setId(null);
            $slug = $model->getSlug() . '-' . uniqid();
            $newPage->setSlug($slug);
            $newPage->setEnabled(false);
            $this->postRepository->save($newPage);
            $this->messageManager->addSuccessMessage(__('You duplicated the post.'));

            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'id'       => $newPage->getId(),
                    '_current' => true,
                ]
            );
        }

        $this->dataPersistor->clear('modules_blog_post');

        if ($this->getRequest()->getParam('back')) {
            return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    public function _filterImageData(array $data)
    {
        if (isset($data['image_path'][0]['url'])) {
            $data['image_path'] = $data['image_path'][0]['url'];
        } else {
            $data['image_path'] = null;
        }

        return $data;
    }
}