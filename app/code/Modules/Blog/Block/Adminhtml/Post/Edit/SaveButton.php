<?php

namespace Modules\Blog\Block\Adminhtml\Post\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label'          => __('Save'),
            'class'          => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'modules_blog_post_form.modules_blog_post_form',
                                'actionName' => 'save',
                                'params'     => [
                                    false
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'class_name'     => Container::SPLIT_BUTTON,
            'options'        => $this->getOptions(),
            'sort_order'     => 90,
        ];
    }

    /**
     * Retrieve options
     *
     * @return array
     */
    private function getOptions()
    {
        return [
            [
                'label'          => __('Save & Duplicate'),
                'id_hard'        => 'save_and_duplicate',
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'modulesblog_post_form.modulesblog_post_form',
                                    'actionName' => 'save',
                                    'params'     => [
                                        true,
                                        [
                                            'back' => 'duplicate',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id_hard'        => 'save_and_close',
                'label'          => __('Save & Close'),
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'modulesblog_post_form.modulesblog_post_form',
                                    'actionName' => 'save',
                                    'params'     => [
                                        true,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}