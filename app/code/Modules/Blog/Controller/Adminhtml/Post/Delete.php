<?php

namespace Modules\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Modules\Blog\Model\PostRepository;

class Delete extends Action implements HttpGetActionInterface
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param PostRepository $postRepository
     */
    public function __construct(Action\Context $context, PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $this->postRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The post has been deleted.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a post to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}