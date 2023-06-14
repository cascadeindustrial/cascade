<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cminds\Creditline\Model\Import;

use Magento\CustomerImportExport\Model\ResourceModel\Import\Customer\Storage;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\ImportExport\Model\Import\AbstractEntity;
use Magento\CustomerImportExport\Model\ResourceModel\Import\Customer\StorageFactory;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\ImportExport\Model\ImportFactory;
use Magento\ImportExport\Model\ResourceModel\Helper;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManagerInterface;
use Cminds\Creditline\Model\TransactionFactory;
use Cminds\Creditline\Model\BalanceFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Stdlib\DateTime;
use Magento\ImportExport\Model\Import;

/**
 * Cminds Creditline.
 *
 * @category Cminds
 * @package  Cminds_Creditline
 */
class Transaction extends AbstractEntity
{
    /**#@+
     * Error codes
     */
    const ERROR_WEBSITE_IS_EMPTY = 'websiteIsEmpty';
    const ERROR_EMAIL_IS_EMPTY = 'emailIsEmpty';
    const ERROR_INVALID_WEBSITE = 'invalidWebsite';
    const ERROR_INVALID_EMAIL = 'invalidEmail';
    const ERROR_VALUE_IS_REQUIRED = 'valueIsRequired';
    const ERROR_CUSTOMER_NOT_FOUND = 'customerNotFound';

    /**
     * @var string
     */
    private $websiteColumn = 'website_id';

    /**
     * @var string
     */
    private $customerColumn = 'customer_email';

    /**
     * Transaction DB table name.
     *
     * @var string
     */
    protected $entityTable;

    /**
     * Balance DB table name.
     *
     * @var string
     */
    protected $balanceTable;

    /**
     * Customer model
     *
     * @var Transaction
     */
    protected $transactionModel;

    /**
     * {@inheritdoc}
     */
    protected $masterAttributeCode = 'transaction_id';

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Website code-to-ID
     *
     * @var array
     */
    protected $websiteCodeToId = [];

    /**
     * All stores code-ID pairs.
     *
     * @var array
     */
    protected $storeCodeToId = [];

    /**
     * @var StorageFactory
     */
    protected $storageFactory;

    /**
     * @param StorageFactory $storageFactory
     * @param StringUtils $string
     * @param ScopeConfigInterface $scopeConfig
     * @param ImportFactory $importFactory
     * @param Helper $resourceHelper
     * @param ResourceConnection $resource
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @param StoreManagerInterface $storeManager
     * @param TransactionFactory $transactionFactory
     * @param BalanceFactory $balanceFactory
     * @param CustomerFactory $customerFactory
     * @param array $data
     */
    public function __construct(
        StorageFactory $storageFactory,
        StringUtils $string,
        ScopeConfigInterface $scopeConfig,
        ImportFactory $importFactory,
        Helper $resourceHelper,
        ResourceConnection $resource,
        ProcessingErrorAggregatorInterface $errorAggregator,
        StoreManagerInterface $storeManager,
        TransactionFactory $transactionFactory,
        BalanceFactory $balanceFactory,
        CustomerFactory $customerFactory,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->storageFactory = $storageFactory;
        $this->customerFactory = $customerFactory;
        $this->balanceFactory = $balanceFactory;

        parent::__construct(
            $string,
            $scopeConfig,
            $importFactory,
            $resourceHelper,
            $resource,
            $errorAggregator,
            $data
        );

        $this->addMessageTemplate(self::ERROR_WEBSITE_IS_EMPTY, __('Please specify a website.'));
        $this->addMessageTemplate(self::ERROR_EMAIL_IS_EMPTY, __('Please specify a customer email.'));
        $this->addMessageTemplate(self::ERROR_INVALID_WEBSITE, __('We found an invalid value in a website column.'));
        $this->addMessageTemplate(self::ERROR_INVALID_EMAIL, __('Please enter a valid email.'));
        $this->addMessageTemplate(self::ERROR_VALUE_IS_REQUIRED, __('Please make sure attribute "%s" is not empty.'));
        $this->addMessageTemplate(
            self::ERROR_CUSTOMER_NOT_FOUND,
            __('We can\'t find a customer who matches this email and website code.')
        );

        $this->initStores(true);

        $this->transactionModel = $transactionFactory->create();

        $this->entityTable = $this->transactionModel->getResource()->getMainTable();
        $this->balanceTable = $this->balanceFactory->create()->getResource()->getMainTable();

        $this->initWebsites(true);
    }

    /**
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'creditline_transaction';
    }

    /**
     * Retrieve website id by code or false when website code not exists
     *
     * @param string $websiteCode
     * @return int|false
     */
    public function getWebsiteId($websiteCode)
    {
        if (isset($this->websiteCodeToId[$websiteCode])) {
            return $this->websiteCodeToId[$websiteCode];
        }

        return false;
    }

    /**
     * Initialize website values
     *
     * @param bool $withDefault
     * @return $this
     */
    protected function initWebsites($withDefault = false)
    {
        /** @var $website Website */
        foreach ($this->storeManager->getWebsites($withDefault) as $website) {
            $this->websiteCodeToId[$website->getCode()] = $website->getId();
        }
        return $this;
    }

    /**
     * Initialize stores data
     *
     * @param bool $withDefault
     * @return $this
     */
    protected function initStores($withDefault = false)
    {
        /** @var $store Store */
        foreach ($this->storeManager->getStores($withDefault) as $store) {
            $this->storeCodeToId[$store->getCode()] = $store->getId();
        }
        return $this;
    }

    /**
     * Validate data row
     *
     * @param array $rowData
     * @param int   $rowNumber
     * @return bool
     */
    public function validateRow(array $rowData, $rowNumber)
    {
        if (isset($this->_validatedRows[$rowNumber])) {
            // check that row is already validated
            return !$this->getErrorAggregator()->isRowInvalid($rowNumber);
        }
        $this->_validatedRows[$rowNumber] = true;
        $this->_processedEntitiesCount++;
        if ($this->getBehavior($rowData) == Import::BEHAVIOR_ADD_UPDATE) {
            $this->checkRowForUpdate($rowData, $rowNumber);
        } elseif ($this->getBehavior($rowData) == Import::BEHAVIOR_DELETE) {
            $this->checkRowForDelete($rowData, $rowNumber, true);
        }

        return !$this->getErrorAggregator()->isRowInvalid($rowNumber);
    }

    /**
     * Import data rows
     *
     * @return bool
     */
    protected function _importData()
    {
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entitiesToCreate = [];
            $entitiesToUpdate = [];
            $entitiesToDelete = [];

            $balances = [];
            foreach ($bunch as $rowNumber => $rowData) {
                if (!$this->validateRow($rowData, $rowNumber)) {
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNumber);
                    continue;
                }
                if ($this->getBehavior($rowData) == Import::BEHAVIOR_DELETE) {
                    $entitiesToDelete[] = $rowData[$this->masterAttributeCode];
                    $transaction = $this->transactionModel->load($rowData[$this->masterAttributeCode]);
                    $balanceId = $transaction->getBalanceId();
                } elseif ($this->getBehavior($rowData) == Import::BEHAVIOR_ADD_UPDATE) {
                    /** @var Customer $customer */
                    $customer = $this->customerFactory->create()->getCollection()
                        ->addFieldToFilter('email', $rowData[$this->customerColumn])->getFirstItem();

                    $balanceId = $this->balanceFactory->create()->loadByCustomer($customer->getId())->getId();

                    $rowData['balance_id'] = $balanceId;
                    $processingData = $this->prepareDataForUpdate($rowData);

                    $existTransactions = $this->transactionModel->getCollection()->getAllIds();
                    if (
                        !empty($processingData[$this->masterAttributeCode]) &&
                        in_array($processingData[$this->masterAttributeCode], $existTransactions)
                    ) {
                        $entitiesToUpdate[] = $processingData;
                    } else {
                        if (array_key_exists($this->masterAttributeCode, $processingData)) {
                            unset($processingData[$this->masterAttributeCode]);
                        }

                        $entitiesToCreate[] = $processingData;
                    }
                }
                $balances[] = [
                    'balance_id'  => $balanceId,
                ];
            }
            $this->updateItemsCounterStats($entitiesToCreate, $entitiesToUpdate, $entitiesToDelete);
            /**
             * Save prepared data
             */
            if ($entitiesToCreate || $entitiesToUpdate) {
                $this->saveTransactionEntities($entitiesToCreate, $entitiesToUpdate);
            }
            if ($entitiesToDelete) {
                $this->deleteTransactionEntities($entitiesToDelete);
            }
            if ($balances) {
                $this->updateBalanceEntities($balances);
            }
        }

        return true;
    }

    /**
     * Prepare customer data for update
     *
     * @param array $rowData
     * @return array
     */
    protected function prepareDataForUpdate(array $rowData)
    {
        if (empty($rowData['created_at'])) {
            $rowData['created_at'] = (new \DateTime())
                ->format(DateTime::DATETIME_PHP_FORMAT);
        } else {
            $rowData['created_at'] = (new \DateTime())->setTimestamp(strtotime($rowData['created_at']))
                ->format(DateTime::DATETIME_PHP_FORMAT);
        }

        if (!array_key_exists('updated_at', $rowData)) {
            $rowData['updated_at'] = $rowData['created_at'];
        }
        if (array_key_exists('is_notified', $rowData)) {
            $rowData['is_notified'] = strtolower($rowData['is_notified']) == __('no') ? 0 : 1;
        }
        if (array_key_exists('customer_name', $rowData)) {
            unset($rowData['customer_name']);
        }
        unset($rowData[$this->customerColumn], $rowData[$this->websiteColumn]);

        $rowData['balance_amount'] = 0;

        return $rowData;
    }

    /**
     * Update and insert data in entity table
     *
     * @param array $entitiesToCreate Rows for insert
     * @param array $entitiesToUpdate Rows for update
     * @return $this
     */
    protected function saveTransactionEntities(array $entitiesToCreate, array $entitiesToUpdate)
    {
        if ($entitiesToCreate) {
            $this->_connection->insertMultiple($this->entityTable, $entitiesToCreate);
        }

        if ($entitiesToUpdate) {
            $this->_connection->insertOnDuplicate(
                $this->entityTable,
                $entitiesToUpdate
            );
        }

        return $this;
    }

    /**
     * @param array $entitiesToDelete
     * @return $this
     */
    protected function deleteTransactionEntities(array $entitiesToDelete)
    {
        $condition = $this->_connection->quoteInto('transaction_id IN (?)', $entitiesToDelete);
        $this->_connection->delete($this->entityTable, $condition);

        return $this;
    }

    /**
     * @param array $balances
     * @return $this
     */
    public function updateBalanceEntities($balances)
    {
        foreach ($balances as $balanceId) {
            $this->balanceFactory->create()->setId($balanceId)->recalculateBalance();
        }

        return $this;
    }

    /**
     * @param array $rowData
     * @param int   $rowNumber
     * @return bool
     */
    protected function checkRowForDelete(array $rowData, $rowNumber)
    {
        if (empty($rowData[$this->masterAttributeCode])) {
            $this->addRowError(static::ERROR_VALUE_IS_REQUIRED, $rowNumber, $this->masterAttributeCode);
        }

        return !$this->getErrorAggregator()->isRowInvalid($rowNumber);
    }
    /**
     * @param array $rowData
     * @param int   $rowNumber
     * @return bool
     */
    protected function checkRowForUpdate(array $rowData, $rowNumber)
    {
        if (empty($rowData[$this->customerColumn])) {
            $this->addRowError(static::ERROR_EMAIL_IS_EMPTY, $rowNumber, $this->customerColumn);
        } else {
            $email = strtolower($rowData[$this->customerColumn]);

            if (!\Zend_Validate::is($email, 'EmailAddress')) {
                $this->addRowError(static::ERROR_INVALID_EMAIL, $rowNumber, $this->customerColumn);
            }
            if (
                isset($rowData[$this->websiteColumn]) &&
                !isset($this->websiteCodeToId[$rowData[$this->websiteColumn]])
            ) {
                $this->addRowError(static::ERROR_INVALID_WEBSITE, $rowNumber, $this->websiteColumn);
            }
            $customer = $this->customerFactory->create()->getCollection()
                ->addFieldToFilter('email', $email)->getFirstItem();
            if (!$customer || !$customer->getId()) {
                $this->addRowError(self::ERROR_CUSTOMER_NOT_FOUND, $rowNumber);
            }
            if (!isset($rowData['balance_delta'])) {
                $this->addRowError(static::ERROR_VALUE_IS_REQUIRED, $rowNumber, 'balance_delta');
            }
        }

        return !$this->getErrorAggregator()->isRowInvalid($rowNumber);
    }
}
