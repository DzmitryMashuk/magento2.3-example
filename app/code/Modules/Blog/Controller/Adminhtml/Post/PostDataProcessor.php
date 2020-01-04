<?php

namespace Modules\Blog\Controller\Adminhtml\Post;

use Magento\Framework\Config\Dom\ValidationException;
use Magento\Framework\Config\Dom\ValidationSchemaException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Model\Layout\Update\Validator;
use Magento\Framework\View\Model\Layout\Update\ValidatorFactory;

/**
 * Processes form data
 */
class PostDataProcessor
{
    /**
     * @var Date
     */
    protected $dateFilter;

    /**
     * @var ValidatorFactory
     */
    protected $validatorFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @param Date $dateFilter
     * @param ManagerInterface $messageManager
     * @param ValidatorFactory $validatorFactory
     */
    public function __construct(
        Date $dateFilter,
        ManagerInterface $messageManager,
        ValidatorFactory $validatorFactory
    ) {
        $this->dateFilter       = $dateFilter;
        $this->messageManager   = $messageManager;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array $data
     *
     * @return array
     */
    public function filter($data)
    {
        $filterRules = [];

        foreach (['custom_theme_from', 'custom_theme_to'] as $dateField) {
            if (!empty($data[$dateField])) {
                $filterRules[$dateField] = $this->dateFilter;
            }
        }

        return (new \Zend_Filter_Input($filterRules, [], $data))->getUnescaped();
    }

    /**
     * Validate post data
     *
     * @param array $data
     *
     * @return bool     Return FALSE if some item is invalid
     * @deprecated 103.0.3
     */
    public function validate($data)
    {
        if (!empty($data['layout_update_xml']) || !empty($data['custom_layout_update_xml'])) {
            /** @var $layoutXmlValidator Validator */
            $layoutXmlValidator = $this->validatorFactory->create(
                [
                    'validationState' => $this->validationState,
                ]
            );

            if (!$this->validateData($data, $layoutXmlValidator)) {
                $validatorMessages = $layoutXmlValidator->getMessages();

                foreach ($validatorMessages as $message) {
                    $this->messageManager->addErrorMessage($message);
                }

                return false;
            }
        }

        return true;
    }

    /**
     * Check if required fields is not empty
     *
     * @param array $data
     *
     * @return bool
     */
    public function validateRequireEntry(array $data)
    {
        $requiredFields = [
            'title'     => __('BlogPage Title'),
            'stores'    => __('Store View'),
            'is_active' => __('Status'),
        ];
        $errorNo        = true;

        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $errorNo = false;
                $this->messageManager->addErrorMessage(
                    __('To apply changes you should fill in hidden required "%1" field', $requiredFields[$field])
                );
            }
        }

        return $errorNo;
    }

    /**
     * Validate data, avoid cyclomatic complexity
     *
     * @param array $data
     * @param Validator $layoutXmlValidator
     *
     * @return bool
     */
    private function validateData($data, $layoutXmlValidator)
    {
        try {
            if (!empty($data['layout_update_xml']) && !$layoutXmlValidator->isValid($data['layout_update_xml'])) {
                return false;
            }

            if (!empty($data['custom_layout_update_xml']) &&
                !$layoutXmlValidator->isValid($data['custom_layout_update_xml'])
            ) {
                return false;
            }
        } catch (ValidationException $e) {
            return false;
        } catch (ValidationSchemaException $e) {
            return false;
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e);

            return false;
        }

        return true;
    }
}