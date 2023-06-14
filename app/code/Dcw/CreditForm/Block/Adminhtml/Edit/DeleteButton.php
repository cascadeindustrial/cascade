<?php
namespace Dcw\CreditForm\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 */
 class DeleteButton extends GenericButton implements ButtonProviderInterface
 {
     public function getButtonData()
     {
         return [
             'label' => __('Delete'),
             'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to delete this userinfo ?') . '\', \'' . $this->getDeleteUrl() . '\')',
             'class' => 'delete',
             'sort_order' => 20
         ];
     }

     public function getDeleteUrl()
     {

         $request = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\RequestInterface');
         // $url = $urlInterface->getCurrentUrl();
         //
         // $parts = explode('/', parse_url($url, PHP_URL_PATH));
         // $id = $parts[7];
         $id = $request->getParam('id');
         return $this->getUrl('*/*/delete', ['id' => $id]);
     }
 }
