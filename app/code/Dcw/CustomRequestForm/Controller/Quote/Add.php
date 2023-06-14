<?php
namespace Dcw\CustomRequestForm\Controller\Quote;

use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart as Quote;
use Cart2Quote\Quotation\Model\QuotationCart;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;



use Magento\Framework\App\Filesystem\DirectoryList;
	use Magento\MediaStorage\Model\File\UploaderFactory;
	use Magento\Framework\Image\AdapterFactory;
	use Magento\Framework\Filesystem;


class Add extends \Cart2Quote\Quotation\Controller\Quote
{
    protected $_resultPageFactory;
    protected $productRepository;
    //protected $productRepository;

    /**
     * @var \Magento\Catalog\Helper\Product
     */
    protected $productHelper;

    /**
     * @var \Cart2Quote\Quotation\Helper\Data
     */
    protected $quotationDataHelper;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $resolverInterface;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    private $jsonHelper;

    /**
     * @var \Cart2Quote\Quotation\Model\Quote\Request\Strategy\Provider
     */
    private $strategyProvider;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;


    protected $_storeManager;
    /**
     * @var \Magento\Checkout\Model\Cart\RequestInfoFilterInterface
     */
    private $requestInfoFilter;
    private $serializer;

    /**
     * @var UploaderFactory
     */
	protected $uploaderFactory;
	
	/**
     * @var AdapterFactory
     */
    protected $adapterFactory;
	
	/**
     * @var Filesystem
     */
    protected $filesystem;




    public function __construct(\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Cart2Quote\Quotation\Model\QuotationCart $cart,
        \Cart2Quote\Quotation\Model\Session $quotationSession,
        \Cart2Quote\Quotation\Model\QuoteFactory $quoteFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Helper\Product $productHelper,
        \Cart2Quote\Quotation\Helper\Data $quotationDataHelper,
        \Magento\Framework\Locale\ResolverInterface $resolverInterface,
        \Magento\Framework\Escaper $escaper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Cart2Quote\Quotation\Model\Quote\Request\Strategy\Provider $strategyProvider,
        \Magento\Customer\Model\Session $customerSession,



    	UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem
    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->quotationDataHelper = $quotationDataHelper;
        $this->productHelper = $productHelper;
        $this->productRepository = $productRepository;
        $this->resolverInterface = $resolverInterface;
        $this->escaper = $escaper;
        $this->jsonHelper = $jsonHelper;
        $this->_storeManager = $storeManager;
        $this->serializer = $serializer;
        $this->strategyProvider = $strategyProvider;
        $this->customerSession = $customerSession;


        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;


        parent::__construct($context,
            $scopeConfig,
            $storeManager,
            $formKeyValidator,
            $cart,
            $quotationSession,
            $quoteFactory,
            $resultPageFactory,
            $logger);
    }

    public function execute()
    {

  $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
  $customRequestFormPostData = $this->getRequest()->getPostValue();
  $productOptions  = $customRequestFormPostData;
  $fileOptionId = $productOptions['file_option_id'];
  $fileOptionParam = $productOptions['file_option_id']."_"."action";
  $resource=$objectManager->create('\Magento\Framework\App\ResourceConnection');
  $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
  
  
 /*  echo "<pre>";
   print_r($productOptions);
  exit;   */


  $dateOptionId = $stateOptionId = $countryOptionId = '';

  // state&country needs to be added as addiG257Gtional options not as custom options [Done]
  // Only US,Canada&Mexico needs to be shown

  if(isset($productOptions['state-option-id']))
    $stateOptionId = $productOptions['state-option-id'];

  if(isset($productOptions['country-option-id']))
    $countryOptionId = $productOptions['country-option-id'];

  if(isset($productOptions['date-option-id']))
  {
      $dateOptionId = $productOptions['date-option-id'];
      if(is_array($dateOptionId))
      {
        foreach($dateOptionId as $dateId)
        {
          if($productOptions[$dateId]=='')
                continue;

          $selectedDate = $productOptions[$dateId];//dynamic for date field
          $selectedDateTime=strtotime($selectedDate);
          $day=date("d",$selectedDateTime);
          $month=date("m",$selectedDateTime);
          $year=date("Y",$selectedDateTime);
          $formattedDate = array("month"=>$month,"day"=>$day,"year"=>$year);
          $productOptions[$dateId]  =  $formattedDate; // i.e for dateFormat asper magento
        }
      }
      else{
        $selectedDate = $productOptions[$dateOptionId];//dynamic for date field
        $selectedDateTime=strtotime($selectedDate);
        $day=date("d",$selectedDateTime);
        $month=date("m",$selectedDateTime);
        $year=date("Y",$selectedDateTime);
        $formattedDate = array("month"=>$month,"day"=>$day,"year"=>$year);
        $productOptions[$dateOptionId]  =  $formattedDate; // i.e for dateFormat asper magento
      }
  }
  
 /* echo "<pre>";
   print_r($productOptions);
  exit;  */

  $formKey = $productOptions['form_key'];
  if(isset($productOptions['country_id']))
  {
    $country =$productOptions['country_id'];
    if(isset($productOptions['state']))
    {
      $regionId =$productOptions['state'];
      $region = $objectManager->create('Magento\Directory\Model\Region')
                            ->load($regionId);
      $regionData = $region->getData();
      // echo "<pre>";
      // print_r($regionData);
      if(count($regionData))
      {
        $stateName = $regionData['name'];

        //printLog($stateName);

        $sql = "SELECT * FROM `catalog_product_option_type_title` where title='$country'";
        $countryOptionData = $connection->fetchRow($sql);

        //$stateName = 'N/A';

        $countryId = $countryOptionData['option_type_id'];

        $sql1 = "SELECT * FROM `catalog_product_option_type_title` where title='$stateName'";
        $stateOptionData = $connection->fetchRow($sql1);

        $stateId = $stateOptionData['option_type_id'];

        $productOptions[$countryOptionId] = $countryId;
        $productOptions[$stateOptionId] = $stateId;

      }
      else{

        $sql = "SELECT * FROM `catalog_product_option_type_title` where title='$country'";
        $countryOptionData = $connection->fetchRow($sql);

        $stateName = 'N/A';

        //printLog("in else loop");

        $sql1 = "SELECT * FROM `catalog_product_option_type_title` where title='$stateName'";
        $stateOptionData = $connection->fetchRow($sql1);

        $stateId = $stateOptionData['option_type_id'];

        $countryId = $countryOptionData['option_type_id'];

        //echo "in nested if condition";

        $productOptions[$stateOptionId] = $stateId;
        $productOptions[$countryOptionId] = $countryId;
        //$productOptions[$stateOptionId] = $stateId;
      }
    }else{


      $sql = "SELECT * FROM `catalog_product_option_type_title` where title='$country'";
      $countryOptionData = $connection->fetchRow($sql);

      $stateName = 'N/A';

      $sql1 = "SELECT * FROM `catalog_product_option_type_title` where title='$stateName'";
      $stateOptionData = $connection->fetchRow($sql1);

      $stateId = $stateOptionData['option_type_id'];

      $countryId = $countryOptionData['option_type_id'];

      //echo "in nested if condition";

      $productOptions[$stateOptionId] = $stateId;
      $productOptions[$countryOptionId] = $countryId;
      //$productOptions[$stateOptionId] = $stateId;
    }

  }


 $data = $this->getRequest()->getParams();
	if ($data) {
		$files = $this->getRequest()->getFiles();
		//if (isset($files['filesubmission']) && !empty($files['filesubmission']["name"])){
    // $successfulluploadedfiles = [];
    $successfulluploadedfiles = array();
		foreach ($_FILES as $key => $value) {

			// if($value['name']){

								try{
								$uploaderFactory = $this->uploaderFactory->create(['fileId' => $key]);
								//check upload file type or extension
								$uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'doc', 'txt', 'zip', 'csv']);
								// $imageAdapter = $this->adapterFactory->create();
								//$uploaderFactory->addValidateCallback('custom_image_upload',$imageAdapter,'validateUploadFile');
								$uploaderFactory->setAllowRenameFiles(true);
								$uploaderFactory->setFilesDispersion(true);
								$mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
								$destinationPath = $mediaDirectory->getAbsolutePath('requestaquote');
								$result = $uploaderFactory->save($destinationPath);
								if (!$result) {
									throw new LocalizedException(
										__('File cannot be saved to path: $1', $destinationPath)
									);
								}
								$imagePath = 'requestaquote'.$result['file'];
								
								//Set file path with name for save into database
								$successfulluploadedfiles[] = "http://cascadeindustrial.com/media/".$imagePath;
							} catch (\Exception $e) {

							//	$data[$key.'-error'] = $e->getMessage();
							// $this->messageManager->addErrorMessage($e->getMessage());
							}


			// }

		}
	}





  //unset($productOptions['upload_file']);
  unset($productOptions['submit']);
  unset($productOptions['form_key']);
 // unset($productOptions['country_id']);
 // unset($productOptions['region']);
  //unset($productOptions['state']);
  //unset($productOptions['country-option-id']);
  //unset($productOptions['state-option-id']);
  unset($productOptions['date-option-id']);

  //unset($productOptions['options_28_file']);
  unset($productOptions['options_28_file_action']);

  $productId = $this->getProductId();
  $params = array (
	'product' => $productId,
	'selected_configurable_option' => '',
	'related_product' => '',
	'item' => $productId,
	'custom_price' => '0',
	'form_key' => $formKey,
	'options' =>$productOptions,
	$fileOptionParam => 'save_new',
	'qty' => '1',
	);



        try {

              
                      $inquiry  = $productOptions['9'];
                      $company_name = $productOptions['10'];
                      $fname = $productOptions['11'];
                      $lname = $productOptions['12'];
                      $phone = $productOptions['13'];
                      $email = $productOptions['14'];
                      $project_name = $productOptions['15'];
                      $mailing_list = $productOptions['16'];
                      $best_way = $productOptions['17'];
                      $address = $productOptions['18'];
                      $city = $productOptions['19'];
                      $country = $productOptions['country_id'];
                      /* $state = $productOptions['state']; */
                      $postcode = $productOptions['22'];
                      $business = $productOptions['23'];
                      $account  = $productOptions['24'];
                      $test= $productOptions['25']; 
                      $i = 0;
                      foreach($test as $value){
                      ${'something'.$i} = $value;
                      $i++;
                      }
                      $ddate= $something0 .' /'.$something1 .'/'.$something2; 

				 
                        $imgmessage = '';
                        foreach($successfulluploadedfiles as $successfulluploadedfile)
                        {

                          $pathinfo = pathinfo($successfulluploadedfile);
                          $imgname =  $pathinfo['filename'].'.'.$pathinfo['extension'];
                          if($pathinfo['extension'] == 'csv'){
                            $imgmessage .=  $successfulluploadedfile."<br/>";
                          }
                          elseif($pathinfo['extension'] == 'zip'){
                            $imgmessage .=  $successfulluploadedfile."<br/>";
                          }
                           else{
                          $imgmessage .=  "<a target = '_blank' href = '".$successfulluploadedfile."'>".$imgname."</a><br/>";
                          }
                        }

                        $Request  = $productOptions['26'];
                        $sender = 'postmaster@cascadeindustrial.com';//'cascadeindustrial@gmail.com';
                        // $recipient = 'developer.nids@gmail.com';
                        // $recipient = 'sales@cascadeindustrial.com';

                        //$recipients = ['shivudeveloper@gmail.com', 'shiwanisaini1995@gmail.com'];
                        $recipient = 'sales@cascadeindustrial.com';
                        $useraddress = $email;
                        $subject = "Cascade Industrial Services Corp. Quote Request";
            						$message = "
            							<html>
            							<head>
            							<title>Custom Quote Form</title>
                        
            							</head>
            							<body>
            							<p>Hello Cascade Industrial,</p>

                                        <p>You received a new quote request from the ".$fname." ".$lname." from ".$company_name."</p>
            							
            							<table style='width:40%; padding:30px; background-color: #f2f2f2;border:1px solid #d1d1d1;'>
            							<tr style='padding:0; margin:0;'><b>Is this inquiry urgent?</b></tr>
            							<tr style='padding:0; margin:0;'>".$inquiry."</tr>
            							<tr style='padding:0; margin:0;'><b>Company Name</b></tr>
            							<tr style='padding:0; margin:0;'>".$company_name."</tr>
            							<tr style='padding:0; margin:0;'><b>First Name</b></tr>
            							<tr style='padding:0; margin:0;'>".$fname."</tr>
            							<tr style='padding:0; margin:0;'><b>Last Name</b></tr>
            							<tr style='padding:0; margin:0;'>".$lname."</tr>
            							<tr style='padding:0; margin:0;'><b>Phone Number</b></tr>
            							<tr style='padding:0; margin:0;'>".$phone."</tr>
            							<tr style='padding:0; margin:0;'><b>Email Address</b></tr>
            							<tr style='padding:0; margin:0;'>".$email."</tr>
            							<tr style='padding:0; margin:0;'><b>Project Name</b></tr>
            							<tr style='padding:0; margin:0;'>".$project_name."</tr>
            							<tr style='padding:0; margin:0;'><b>Would you like to join our mailing list?</b></tr>
            							<tr style='padding:0; margin:0;'>".$mailing_list."</tr>
            							<tr style='padding:0; margin:0;'><b>Best way to contact you?</b></tr>
            							<tr style='padding:0; margin:0;'>".$best_way."</tr>
            							<tr style='padding:0; margin:0;'><b>Address</b></tr>
            							<tr style='padding:0; margin:0;'>".$address."</tr>
            							<tr style='padding:0; margin:0;'><b>City</b></tr>
            							<tr style='padding:0; margin:0;'>".$city."</tr>
            							<tr style='padding:0; margin:0;'><b>Country</b></tr>
            							<tr style='padding:0; margin:0;'>".$country."</tr>
            							<tr style='padding:0; margin:0;'><b>State/Province</b></tr>
            							<tr style='padding:0; margin:0;'>".$stateName."</tr>
            							<tr style='padding:0; margin:0;'><b>Postal Code</b></tr>
            							<tr style='padding:0; margin:0;'>".$postcode."</tr>
            							<tr style='padding:0; margin:0;'><b>Type of Business</b></tr>
            							<tr style='padding:0; margin:0;'>".$business."</tr>
            							<tr style='padding:0; margin:0;'><b>Do you have an account with us?</b></tr>
            							<tr style='padding:0; margin:0;'>".$account."</tr>
            							<tr style='padding:0; margin:0;'><b>Quote Deadline</b></tr>
            							<tr style='padding:0; margin:0;'>".$ddate."</tr>
            							<tr style='padding:0; margin:0;'><b>Details of Request</b></tr>
            							<tr style='padding:0; margin:0;'>".$Request."</tr>
            							<tr style='padding:0; margin:0;'><b>Upload Document</b></tr>
            							<tr style='padding:0; margin:0;'>".$imgmessage."</tr>
            							</table>
            							</body>
            							</html>
            							";
                          $message1 = "
            							<html>
            							<head>
            							<title>Custom Quote Form</title>
            							</head>
            							<body>
            							<p>Hello  ".$fname." ".$lname.",</p>

                          <p>Thank you for your quote request, urgent requests are typically replied to within an hour. If you did not mark your request as urgent our sales department will respond to you with a quote within 24 hours. </p>

                          <p>If you have questions about your quote request, you can email us at sales@cascadeindustrial.com or call us at 877.280.7285. </p>
            							
            							<table style='width:40%;padding:30px;background-color: #f2f2f2;border:1px solid #d1d1d1;'>
            							<tr style='padding:0; margin:0;'><b>Is this inquiry urgent?</b></tr>
            							<tr style='padding:0; margin:0;'>".$inquiry."</tr>
            							<tr style='padding:0; margin:0;'><b>Company Name</b></tr>
            							<tr style='padding:0; margin:0;'>".$company_name."</tr>
            							<tr style='padding:0; margin:0;'><b>First Name</b></tr>
            							<tr style='padding:0; margin:0;'>".$fname."</tr>
            							<tr style='padding:0; margin:0;'><b>Last Name</b></tr>
            							<tr style='padding:0; margin:0;'>".$lname."</tr>
            							<tr style='padding:0; margin:0;'><b>Phone Number</b></tr>
            							<tr style='padding:0; margin:0;'>".$phone."</tr>
            							<tr style='padding:0; margin:0;'><b>Email Address</b></tr>
            							<tr style='padding:0; margin:0;'>".$email."</tr>
            							<tr style='padding:0; margin:0;'><b>Project Name</b></tr>
            							<tr style='padding:0; margin:0;'>".$project_name."</tr>
            							<tr style='padding:0; margin:0;'><b>Would you like to join our mailing list?</b></tr>
            							<tr style='padding:0; margin:0;'>".$mailing_list."</tr>
            							<tr style='padding:0; margin:0;'><b>Best way to contact you?</b></tr>
            							<tr style='padding:0; margin:0;'>".$best_way."</tr>
            							<tr style='padding:0; margin:0;'><b>Address</b></tr>
            							<tr style='padding:0; margin:0;'>".$address."</tr>
            							<tr style='padding:0; margin:0;'><b>City</b></tr>
            							<tr style='padding:0; margin:0;'>".$city."</tr>
            							<tr style='padding:0; margin:0;'><b>Country</b></tr>
            							<tr style='padding:0; margin:0;'>".$country."</tr>
            							<tr style='padding:0; margin:0;'><b>State/Province</b></tr>
            							<tr style='padding:0; margin:0;'>".$stateName."</tr>
            							<tr style='padding:0; margin:0;'><b>Postal Code</b></tr>
            							<tr style='padding:0; margin:0;'>".$postcode."</tr>
            							<tr style='padding:0; margin:0;'><b>Type of Business</b></tr>
            							<tr style='padding:0; margin:0;'>".$business."</tr>
            							<tr style='padding:0; margin:0;'><b>Do you have an account with us?</b></tr>
            							<tr style='padding:0; margin:0;'>".$account."</tr>
            							<tr style='padding:0; margin:0;'><b>Quote Deadline</b></tr>
            							<tr style='padding:0; margin:0;'>".$ddate."</tr>
            							<tr style='padding:0; margin:0;'><b>Details of Request</b></tr>
            							<tr style='padding:0; margin:0;'>".$Request."</tr>
            							</table>
            							</body>
            							</html>
            							";
              						$headers = 'From: Post Master Cascade <' . $sender. ">\r\n";
									$headers .= 'X-Sender: testsite <' . $sender. ">\n";
									$headers .= 'X-Mailer: PHP/' . phpversion();
									$headers .= "X-Priority: 1\n"; // Urgent message!
									$headers .= 'Return-Path: '. $sender. '\n'; // Return path for errors
									$headers .= "MIME-Version: 1.0\r\n";
              						//$headers .= "Content-type: text/html\r\n";
									$headers .= "Content-Type: text/html; charset=iso-8859-1\n";
              					    if (mail($recipient, $subject, $message, $headers,'-fpostmaster@cascadeindustrial.com')  && mail($useraddress, $subject, $message1, $headers,'-fpostmaster@cascadeindustrial.com'))
              						{
              							echo "Message accepted";
              						}
              						else
              						{
              							echo "Error: Message not accepted";
              						}
									
                          $message = __(
                              'You have successfully submitted your quote request. We will send you a confirmation email and we will folllow-up with you shortly.'
                              );

                          //still using the depricated addSuccess as addSuccessMessage removes the url
                          $this->messageManager->addSuccess($message);
             

                return $this->goBack(null, $product);
            
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            //echo $e->getMessage();//exit;

            $customUrl = $this->_storeManager->getStore()->getUrl('customquoteform');

            if ($this->_quotationSession->getUseNotice(true)) {
                $this->messageManager->addNoticeMessage(
                    $this->escaper->escapeHtml($e->getMessage())
                );
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->messageManager->addErrorMessage(
                        $this->escaper->escapeHtml($message)
                    );
                }
            }
            $url = $this->_quotationSession->getRedirectUrl(true);
            // echo $url."url";
            // exit;
            if ($url) {
                return $this->goBack($customUrl);
            }
        } catch (\Exception $e) {
           // $this->messageManager->addExceptionMessage($e, __('We can\'t add this item to your quote right now.'));
            $this->logger->critical($e);
        }

        return $this->goBack();

        // $resultPage = $this->_resultPageFactory->create();
        // return $resultPage;
    }

//     public function reArrayFiles(&$file_post) {
//
//     $file_ary = array();
//     $file_count = count($file_post['name']);
//     $file_keys = array_keys($file_post);
//
//     for ($i=0; $i<$file_count; $i++) {
//         foreach ($file_keys as $key) {
//             $file_ary[$i][$key] = $file_post[$key][$i];
//         }
//     }
//
//     return $file_ary;
// }

    public function getProductId(){

      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $resource=$objectManager->create('\Magento\Framework\App\ResourceConnection');
      $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
      $skuQuery = "SELECT entity_id  FROM `catalog_product_entity` WHERE `sku` LIKE 'custom-request-form'";
      $result1 = $connection->fetchRow($skuQuery);
      return $result1['entity_id'];
    }

    protected function _initProduct()
    {
        $productId = $this->getProductId();

        if ($productId) {
            $storeId = $this->_storeManager->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                return false;
            }
        }

        return false;
    }

    protected function goBack($backUrl = null, $product = null, $addedFlag = true)
    {
        if (!$this->getRequest()->isAjax()) {
            return parent::_goBack($backUrl);
        }

        $result = [];
        if ($backUrl || $backUrl = $this->getBackUrl()) {
            $result['backUrl'] = $backUrl;
        }

        if (!$addedFlag) {
            $result['added'] = false;
        }

        $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($result)
        );
    }

    /**
     * Get resolved back url
     *
     * @param null|string $defaultUrl
     * @return string|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getBackUrl($defaultUrl = null)
    {
        $returnUrl = $this->getRequest()->getParam('return_url');
        if ($returnUrl && $this->_isInternalUrl($returnUrl)) {
            $this->messageManager->getMessages()->clear();

            return $returnUrl;
        }

        //use magento cart settings
        $shouldRedirectToCart = $this->_scopeConfig->getValue(
            'checkout/cart/redirect_to_cart',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        //get quote strategy
      /*  $strategy = $this->strategyProvider->getStrategy();

        if (($shouldRedirectToCart && $strategy != 'quick_quote') || $this->getRequest()->getParam('in_quote')) {
            if ($this->getRequest()->getActionName() == 'add' && !$this->getRequest()->getParam('in_quote')) {
                $this->_quotationSession->setContinueShoppingUrl($this->_redirect->getRefererUrl());
            }

            return $this->_url->getUrl('quotation/quote');
        }*/

        return $defaultUrl;
    }

    // public function test(\Magento\Catalog\Model\Product $product){
    //     $options = ['qty'=> 1];
    //     $this->quote->addProduct($product, $options);
    //     //OR  $this->quote->addProductsByIds([$product->getId()]);
    //
    //     $this->quote->save();
    // }
}
