<?php

namespace Modules\Blog\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class EmailHelper extends AbstractHelper
{
    const XML_PATH_EMAIL_TEMPLATE_FIELD = 'post/general/template_notification';

    /**
     * Sender email config path - from default CONTACT extension
     */
    const XML_PATH_EMAIL_SENDER = 'contact/email/sender_email_identity';

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * Demo constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder
    ) {
        parent::__construct($context);
        $this->storeManager      = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder  = $transportBuilder;
    }

    /**
     * Return store configuration value of your template field that which id you set for template
     *
     * @param string $path
     * @param int $storeId
     *
     * @return mixed
     */
    private function getConfigValue($path, $storeId)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Return store
     *
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore(): StoreInterface
    {
        return $this->storeManager->getStore();
    }

    /**
     * @param $variable
     * @param $receiverInfo
     * @param $templateId
     *
     * @return $this
     * @throws NoSuchEntityException
     */
    public function generateTemplate($variable, $receiverInfo, $templateId): EmailHelper
    {
        $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area'  => Area::AREA_ADMINHTML,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars($variable)
            ->setFrom($this->emailSender())
            ->addTo($receiverInfo['email'], $receiverInfo['name']);

        return $this;
    }

    /**
     * Return email for sender header
     *
     * @return mixed
     */
    public function emailSender()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param string $email
     * @param string $name
     *
     * @return $this
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function notify($email, $name): EmailHelper
    {
        $receiverInfo = [
            'email' => $email,
            'name'  => $name
        ];

        /* Assign values for your template variables  */
        $variable                 = [];
        $variable['varaibleName'] = 'some information';

        $templateId = $this->getConfigValue(self::XML_PATH_EMAIL_TEMPLATE_FIELD, $this->getStore()->getStoreId());
        $this->inlineTranslation->suspend();
        $this->generateTemplate($variable, $receiverInfo, $templateId);
        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();

        return $this;
    }
}