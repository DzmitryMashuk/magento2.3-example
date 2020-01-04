<?php

namespace Modules\Blog\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modules\Blog\Helper\EmailHelper;

class NewPostObserver implements ObserverInterface
{
    /**
     * @var EmailHelper
     */
    private $emailHelper;

    /**
     * NewPostObserver constructor.
     *
     * @param EmailHelper $emailHelper
     */
    public function __construct(EmailHelper $emailHelper)
    {
        $this->emailHelper = $emailHelper;
    }

    public function execute(Observer $observer)
    {
        $post = $observer->getPost();
        if ($post->isObjectNew()) {
            $this->emailHelper->notify('d.mashuk@itransition.com', 'Test');
        }
    }
}