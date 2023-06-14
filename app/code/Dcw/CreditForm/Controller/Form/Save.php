<?php
namespace Dcw\CreditForm\Controller\Form;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Directory\Model\Country;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\RegionFactory;
class Save extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $_postFactory;
	protected $uploaderFactory;
	protected $filesystem;
	protected $_messageManager;
	 public $countryFactory;
	 public $regionFactory;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		UploaderFactory $uploaderFactory,
		Filesystem $filesystem,\Magento\Framework\Message\ManagerInterface $messageManager,
		\Magento\Framework\View\Result\PageFactory $pageFactory,\Dcw\CreditForm\Model\PostFactory $postFactory,
		CountryFactory $countryFactory,RegionFactory $regionFactory)
	{
		$this->_pageFactory = $pageFactory;
		$this->_postFactory = $postFactory;
		$this->filesystem = $filesystem;
		$this->uploaderFactory = $uploaderFactory;
		$this->_messageManager = $messageManager;
		$this->countryFactory = $countryFactory;
		$this->regionFactory = $regionFactory;
		return parent::__construct($context);
	}
	public function execute()
	{
		$post = $this->getRequest()->getPostValue();
		//echo "<pre>"; print_r($post);exit;
		$countryId = $post['country_id'];
		$regionId =  $post['region_id'];
		$country = $this->countryFactory->create()->loadByCode($countryId);
		$region = $this->regionFactory->create()->load($regionId)->getData();
		 $countryName = $country->getName();
		//print_r($region);
		$regionName = $region['name'];
		$post['country'] = $countryName;
		$post['state'] = $regionName;

		$creditCountryId = $post['credit_country_id'];
		$creditRegionId =  $post['credit_region_id'];
		$creditCountry = $this->countryFactory->create()->loadByCode($creditCountryId);
		$creditRegion = $this->regionFactory->create()->load($creditRegionId)->getData();
        $creditCountryName = $creditCountry->getName();
        $creditRegionName = $creditRegion['name'];
        $post['credit_country'] = $creditCountryName;
		$post['credit_state'] = $creditRegionName;

		$uploadedfiles = $this->getRequest()->getFiles('upload_file');
		$isValueExists = $uploadedfiles[0]['name'];
		if(!empty($isValueExists)) {
		$count= count($uploadedfiles);
		for ($i=0; $i < $count; $i++) {
				 try {
					$uploaderFactory = $this->uploaderFactory->create(['fileId' => $uploadedfiles[$i]]);
					$uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png','pdf','zip','rar','docx','doc']);
					$uploaderFactory->setAllowRenameFiles(true);
					$mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
					$destinationPath = $mediaDirectory->getAbsolutePath('creditform/files');
					$result = $uploaderFactory->save($destinationPath);
					$filePath = 'creditform/files/'.$result['file'];
					$arrayval[] = $filePath;
				  $arrayvalv =		implode(',', (array) $arrayval);
				  $post['upload_file']= $arrayvalv;
			    }catch (\Exception $e) {
				 $this->messageManager->addError(__($e->getMessage()));
			 }
    } }
		$modelcreation = $this->_postFactory->create();
		$modelcreation->setData($post);
		$modelcreation->save();
		$this->messageManager->addSuccess(__('Your form has been submitted successfully.'));
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		$resultRedirect->setPath('customer/account/');
		return $resultRedirect;
	}
}
?>
