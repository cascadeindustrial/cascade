<?php
namespace Dcw\Products\Controller\Adminhtml\Banner;
/**
 * MassStatus action
 * @category WeltPixel
 * @package  WeltPixel_OwlCarouselSlider
 * @module   OwlCarouselSlider
 * @author   WeltPixel Developer
 */
class MassStatus extends \WeltPixel\OwlCarouselSlider\Controller\Adminhtml\Banner
{
    /**
     * Dispatch request
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $bannerIds = $this->getRequest()->getParam('banner');
        $status = $this->getRequest()->getParam('status');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $this->_objectManager->get('\Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $tblBanner = $connection->getTableName('weltpixel_owlcarouselslider_banners');

        if (!is_array($bannerIds) || empty($bannerIds)) {
            $this->messageManager->addError(__('Please select banner(s).'));
        } else {
            $bannerCollection = $this->_bannerCollectionFactory->create()
                ->addFieldToFilter('id', ['in' => $bannerIds]);
            try {
                foreach ($bannerCollection as $banner) {
                    if($status==2)
                        $status=0;    
                    $result1 = $connection->rawQuery('UPDATE `' . $tblBanner .
                          '`SET status = ' . trim($status) .
                          ' WHERE id=' .$banner['id']);    
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 banner(s) status have been changed.', count($bannerIds))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/', ['store' => $this->getRequest()->getParam('store')]);
    }
}