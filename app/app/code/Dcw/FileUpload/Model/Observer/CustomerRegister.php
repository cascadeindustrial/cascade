<?php

namespace Dcw\FileUpload\Model\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
class CustomerRegister implements ObserverInterface
{
    protected $customerRepository;
    protected $uploaderFactory;
    protected $filesystem;
    protected $adapterFactory;
    public function __construct(CustomerRepositoryInterface $customerRepository,
                                UploaderFactory $uploaderFactory,
                                \Magento\Customer\Model\Customer $customer,
                                \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory,
                                Filesystem $filesystem,AdapterFactory $adapterFactory
    ) {
      $this->customer = $customer;
      $this->customerRepository = $customerRepository;
      $this->uploaderFactory = $uploaderFactory;
      $this->customerFactory = $customerFactory;
      $this->filesystem = $filesystem;
      $this->adapterFactory = $adapterFactory;
    }
    public function execute(Observer $observer)
    {

        if(!isset($_FILES['uploadfile']))
              return;

              $logFile='/var/log/custregister.log';
                      $writer = new \Zend\Log\Writer\Stream(BP . $logFile);
                      $logger = new \Zend\Log\Logger();
                      $logger->addWriter($writer);
                      $logger->info("In Product Import File");

        //  $customer = $observer->getCustomer();
         $files = $_FILES['uploadfile'];

         $logger->info($files);

         $size = count($files['name']);
         $isValueExists = $files['name'][0];

         if(empty($isValueExists))
             return true;



         for($i=0;$i<$size;$i++)
         {
               $allFiles[$i]['name'] = $files['name'][$i];
               $allFiles[$i]['type'] = $files['type'][$i];
               $allFiles[$i]['tmp_name'] = $files['tmp_name'][$i];
               $allFiles[$i]['error'] = $files['error'][$i];
               $allFiles[$i]['size'] = $files['size'][$i];
         }

         $id = $observer->getEvent()->getCustomer()->getId();
         $customer = $this->customer->load($id);
	       for ($i=0; $i < $size; $i++) {
                if (isset($_FILES['uploadfile']['name']) && $_FILES['uploadfile']['name'] != "") {
                   		$uploaderFactory = $this->uploaderFactory->create(['fileId' => $allFiles[$i]]);
                      $imageAdapter = $this->adapterFactory->create();
                   		$uploaderFactory->setAllowedExtensions(['pdf','jpg', 'jpeg', 'gif', 'png']);
                   		$uploaderFactory->setAllowRenameFiles(true);
                   		$mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                   		$destinationPath = $mediaDirectory->getAbsolutePath('registration/pdf');
                   		$result = $uploaderFactory->save($destinationPath);
                   		$filePath = 'registration/pdf/'.$result['file'];
                   		$arrayval[] = $filePath;
                   		$arrayvalv =		implode(',', (array) $arrayval);
                      $customerData = $customer->getDataModel();
                      $customerData->setCustomAttribute('uploadfile',$arrayvalv);
                      $customer->updateData($customerData);
                      $customerResource = $this->customerFactory->create();
                      $customerResource->saveAttribute($customer, 'uploadfile');
                 }
        }
   }
}
