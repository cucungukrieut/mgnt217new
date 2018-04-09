<?php
namespace Magento\ImportTesting\Model\Import;

use Magento\ImportTesting\Model\Import\ImportTesting\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Framework\App\ResourceConnection;
use \Magento\Framework\Json\Helper\Data;
use \Magento\ImportExport\Model\ResourceModel\Helper;
use \Magento\Framework\Stdlib\StringUtils;
use \Magento\Customer\Model\GroupFactory;
use \Magento\ImportExport\Model\Import;
use \Magento\ImportExport\Model\Import\Entity\AbstractEntity;
use \Magento\Framework\Model\ResourceModel\Db\TransactionManagerInterface;

class ImportTesting extends AbstractEntity {

    /**
     * @var TransactionManagerInterface
     */
    protected $transactionManager;

    /**
     * @var string
     */
    const SKU = 'sku';

    /**
     * @var string
     */
    const NAME = 'name';

    /**
     * @var int
     */
    const ENTITY_ID = 'entity_id';

    /**
     * @var int
     */
    const STOCK = 'stock';

    /**
     * foreign key untuk aksesoris
     * SKU (Kode)
     * @var string
     */
    const SKU_AKSESORIS = 'aksesoris_sku';

    const HARGA = 'harga';
    const CREATED = 'created';
    const UPDATED = 'updated';

    // Untuk catalog aksesoris
    const AKSESORIS_SKU = 'sku';
    const AKSESORIS_NAME = 'name';
    const AKSESORIS_ENTITYID = 'entity_id';
    const AKSESORIS_STOCK = 'stock';
    const AKSESORIS_BODYSKU = 'body_sku';
    const AKSESORIS_HARGA = 'harga';
    const AKSESORIS_CREATED = 'created';
    const AKSESORIS_UPDATED = 'updated';

    /**
     * Table Product
     *
     * @var string
     */
    const TABLE_BODY = 'catalog_body';
    const TABLE_AKSESORIS = 'catalog_aksesoris';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_SKU_IS_EMPTY => 'SKU kosong',
    ];

     protected $_permanentAttributes = [self::SKU];

    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;
    protected $groupFactory;

    /**
     * Validasi column names
     *
     * @array
     */
    protected $validColumnNames = [
        self::SKU,
        self::NAME,
        self::ENTITY_ID,
        self::STOCK,
        self::SKU_AKSESORIS,
        self::HARGA,
        self::CREATED,
        self::UPDATED,
        self::AKSESORIS_BODYSKU,
    ];

    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;
    protected $_validators = [];

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;
    protected $_resource;


    /**
     * ImportTesting constructor.
     *
     * @param Data $jsonHelper
     * @param \Magento\ImportExport\Helper\Data $importExportData
     * @param \Magento\ImportExport\Model\ResourceModel\Import\Data $importData
     * @param ResourceConnection $resource
     * @param Helper $resourceHelper
     * @param StringUtils $string
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @param GroupFactory $groupFactory
     */
    public function __construct(Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        ResourceConnection $resource, Helper $resourceHelper,
        StringUtils $string, ProcessingErrorAggregatorInterface $errorAggregator,
        GroupFactory $groupFactory, TransactionManagerInterface $transactionManager )
    {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->groupFactory = $groupFactory;
    }


    /**
     * Get valid column names
     *
     * @return array
     */
    public function getValidColumnNames() {
        return $this->validColumnNames;
    }


    /**
     * Entity type code getter (dari file import.xml).
     *
     * @return string
     */
    public function getEntityTypeCode() {
        return 'import_testing';
    }


    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum) {
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }

        $this->_validatedRows[$rowNum] = true;
        if (!isset($rowData[self::SKU]) || empty($rowData[self::SKU])) {
            $this->addRowError(ValidatorInterface::ERROR_SKU_IS_EMPTY, $rowNum);
            return false;
        }

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }


    /**
     * Create Advanced price data from raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData() {
        if (Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->deleteEntity();
        } elseif (Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->replaceEntity();
        } elseif (Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveEntity();
        }

        return true;
    }


    /**
     * Save newsletter subscriber
     *
     * @return $this
     */
    public function saveEntity() {
        $this->saveAndReplaceEntity();
        return $this;
    }


    /**
     * Replace newsletter subscriber
     *
     * @return $this
     */
    public function replaceEntity() {
        $this->saveAndReplaceEntity();
        return $this;
    }


    /**
     * Deletes data from DB
     *
     * @return $this
     */
    public function deleteEntity() {
        $listProducts = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);
                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    $rowProducts = $rowData[self::SKU];
                    $listProducts[] = $rowProducts;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }
        if ($listProducts) {
            $this->deleteBody(array_unique($listProducts),self::TABLE_Entity);
        }
        return $this;
    }


    /**
     * Save and replace entity (insert/update)
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity() {
        $behavior = $this->getBehavior();
        $listProducts = [];
        //$bubunch = $this->_dataSourceModel->getNextBunch();
        while ($bunch = $this->_dataSourceModel->getNextBunch())
        {
            $bodyList = [];
            foreach ($bunch as $rowNum => $rowData)
            {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(ValidatorInterface::ERROR_SKU_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                $rowProducts = $rowData[self::SKU];
                $listProducts[] = $rowProducts;
                $bodyList[$rowProducts][] = [
                    self::SKU => $rowData[self::SKU],
                    self::NAME => $rowData[self::NAME],
                    self::ENTITY_ID => $rowData[self::ENTITY_ID],
                    self::STOCK => $rowData[self::STOCK],
                    self::SKU_AKSESORIS => $rowData[self::SKU_AKSESORIS],
                    self::HARGA => $rowData[self::HARGA],
                    self::CREATED => $rowData[self::CREATED],
                    self::UPDATED => $rowData[self::UPDATED]
                ];
            }

            if (Import::BEHAVIOR_REPLACE == $behavior)
            {
                if ($listProducts)
                {
                    if ($this->deleteBody(array_unique($listProducts), self::TABLE_BODY)) {
                        $this->saveBody($bodyList, self::TABLE_BODY);
                    }
                }
            } elseif (Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveBody($bodyList, self::TABLE_BODY);
            }
        }
        return $this;
    }


    /**
     * Save product.
     *
     * @param array $bodyList
     * @param string $table
     * @return $this
     * @internal param array $entityData
     * @internal param array $priceData
     */
    protected function saveBody(array $bodyList, $table) {
        if ($bodyList) {
            $tableName = $this->_connection->getTableName($table);
            $bodyInsert = [];
            foreach ($bodyList as $id => $bodyRows) {
                    foreach ($bodyRows as $row) {
                        $bodyInsert[] = $row;
                    }
            }

            if ($bodyInsert) {
                $this->_connection->insertOnDuplicate($tableName, $bodyInsert,[
                    self::SKU,
                    self::NAME,
                    self::ENTITY_ID,
                    self::STOCK,
                    self::SKU_AKSESORIS,
                    self::HARGA,
                    self::CREATED,
                    self::UPDATED
                ]);
            }
        }
        return $this;
    }

    protected function saveAksesoris(array $aksesorisList, $table) {
        if ($aksesorisList) {
            $tableName = $this->_connection->getTableName($table);
            $aksesorisInsert = [];
            foreach ($aksesorisList as $id => $aksesorisRows) {
                foreach ($aksesorisRows as $row) {
                    $aksesorisInsert[] = $row;
                }
            }

            if ( $aksesorisInsert) {
                $this->_connection->insertOnDuplicate($tableName, $aksesorisInsert,[
                    self::AKSESORIS_SKU,
                    self::AKSESORIS_NAME,
                    self::AKSESORIS_ENTITYID,
                    self::AKSESORIS_STOCK,
                    self::AKSESORIS_BODYSKU,
                    self::AKSESORIS_HARGA,
                    self::AKSESORIS_CREATED,
                    self::AKSESORIS_UPDATED
                ]);
            }
        }
        return $this;
    }


    /**
     * Delete entity
     *
     * @param array $listProducts
     * @param $table
     * @return bool
     * @internal param array $bodyList
     * @internal param array $listTitle
     */
    protected function deleteBody(array $listProducts, $table) {
        if ($table && $listProducts) {
            try {
                $this->countItemsDeleted += $this->_connection->delete(
                    $this->_connection->getTableName($table),
                    $this->_connection->quoteInto('sku IN (?)', $listProducts)
                );
                return true;
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }


    /**
     * Delete products.
     *
     * @return $this
     * @throws \Exception
     */
    protected function _deleteProducts()
    {
        $productEntityTable = $this->_resourceFactory->create()->getEntityTable();

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idsToDelete = [];

            foreach ($bunch as $rowNum => $rowData) {
                if ($this->validateRow($rowData, $rowNum) && self::SCOPE_DEFAULT == $this->getRowScope($rowData)) {
                    $idsToDelete[] = $this->_oldSku[$rowData[self::COL_SKU]]['entity_id'];
                }
            }
            if ($idsToDelete) {
                $this->countItemsDeleted += count($idsToDelete);
                $this->transactionManager->start($this->_connection);
                try {
                    $this->objectRelationProcessor->delete(
                        $this->transactionManager,
                        $this->_connection,
                        $productEntityTable,
                        $this->_connection->quoteInto('entity_id IN (?)', $idsToDelete),
                        ['entity_id' => $idsToDelete]
                    );
                    $this->_eventManager->dispatch(
                        'catalog_product_import_bunch_delete_commit_before',
                        [
                            'adapter' => $this,
                            'bunch' => $bunch,
                            'ids_to_delete' => $idsToDelete
                        ]
                    );
                    $this->transactionManager->commit();
                } catch (\Exception $e) {
                    $this->transactionManager->rollBack();
                    throw $e;
                }
                $this->_eventManager->dispatch('catalog_product_import_bunch_delete_after', ['adapter' => $this, 'bunch' => $bunch]);
            }
        }
        return $this;
    }
}
