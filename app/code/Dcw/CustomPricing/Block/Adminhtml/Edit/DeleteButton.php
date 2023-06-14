<?php

namespace Dcw\CustomPricing\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{


    public function getButtonData()
    {
        return [
            'label' => __('Delete'),
            'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to delete this item ?') . '\', \'' . $this->getDeleteUrl() . '\')',
            'class' => 'delete',
            'sort_order' => 20
        ];
    }

    public function getDeleteUrl()
    {
        $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $url = $urlInterface->getCurrentUrl();
        //echo $url;

        $request = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\RequestInterface');


        // $parts = explode('/', parse_url($url, PHP_URL_PATH));
        //   //print_r($parts);exit;
         //$id = $parts[6];
        $id = $request->getParam('id');
//print_r($id);exit;
        return $this->getUrl('*/*/delete', ['id' => $id]);
    }
}
