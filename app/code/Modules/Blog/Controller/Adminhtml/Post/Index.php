<?php

namespace Modules\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Modules\Blog\Model\PostFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var PostFactory
     */
    protected $postFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param PostFactory $postFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory, PostFactory $postFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->postFactory       = $postFactory;
    }

    /**
     * Load the page defined in view/adminhtml/layout/modulesblog_post_index.xml
     *
     * @return Page
     */
    public function execute()
    {
        return $resultPage = $this->resultPageFactory->create();
    }
}