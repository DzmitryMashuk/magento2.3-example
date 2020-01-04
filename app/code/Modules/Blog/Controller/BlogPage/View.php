<?php

namespace Modules\Blog\Controller\BlogPage;

use Modules\Blog\Model\PostRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class View extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var PostRepository
     */
    private $postRepository;

    private $registry;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PostRepository $postRepository
     * @param PageFactory $pageFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        PostRepository $postRepository,
        PageFactory $pageFactory,
        Registry $registry
    ) {
        $this->postRepository = $postRepository;
        $this->pageFactory    = $pageFactory;
        $this->registry       = $registry;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $this->registry->register(
            'current_post',
            $this->postRepository->getBySlug($this->getRequest()->getParam('slug', null))
        );

        return $this->pageFactory->create();
    }
}