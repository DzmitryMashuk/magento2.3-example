<?php

namespace Modules\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Modules\Blog\Model\PostFactory;
use Modules\Blog\Model\PostRepository;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @var PostFactory
     */
    private $postFactory;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param PostRepository $postRepository
     * @param PostFactory $postFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        PostRepository $postRepository,
        PostFactory $postFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->postRepository = $postRepository;
        $this->postFactory    = $postFactory;
    }

    /**
     * Init actions
     *
     * @return Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Modules_Blog::blog_post')
            ->addBreadcrumb(__('BLOG'), __('BLOG'))
            ->addBreadcrumb(__('Manage Posts'), __('Manage Posts'));
        return $resultPage;
    }

    /**
     * Edit BlogPage post
     *
     * @return Page|Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        // 2. Initial checking
        if ($id) {
            try {
                $model = $this->postRepository->getById($id);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $model = $this->postFactory->create();
        }

        // 5. Build edit form
        /** @var Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit BlogPage') : __('New BlogPage'),
            $id ? __('Edit BlogPage') : __('New BlogPage')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New BlogPage'));
        return $resultPage;
    }
}