<?php
namespace Magento\ImportTesting\Model\Import;

use Magento\ImportTesting\Model\Import\ImportTesting\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Framework\App\ResourceConnection;

class ImportTesting extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity {

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
    const AKSESORIS_SKU = 'aksesoris_sku';

    /**
     * @var float
     */
    const HARGA = 'harga';

    /**
     * Created Products
     * @var timestamp
     */
    const CREATED = 'created';

    /**
     * Update products
     * @var timestamp
     */
    const UPDATED = 'updated';

    /**
     * Table Product
     *
     * @var string
     */
    const TABLE_Entity = 'catalog_body';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_SKU_IS_EMPTY => 'SKU is empty',
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
        self::AKSESORIS_SKU,
        self::HARGA,
        self::CREATED,
        self::UPDATED,
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
     * Default Constructor
     *
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\ImportExport\Helper\Data $importExportData
     * @param \Magento\ImportExport\Model\ResourceModel\Import\Data $importData
     * @param ResourceConnection $resource
     * @param \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @param \Magento\Customer\Model\GroupFactory $groupFactory
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Customer\Model\GroupFactory $groupFactory
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->groupFactory = $groupFactory;
    }


    /**
     * Get valid column names
     *
     * @return array
     */
    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }


    /**
     * Entity type code getter (dari file import.xml).
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'import_testing';
    }


    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
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
    protected function _importData()
    {
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
            $this->deleteEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
            $this->replaceEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $this->getBehavior()) {
            $this->saveEntity();
        }

        return true;
    }


    /**
     * Save newsletter subscriber
     *
     * @return $this
     */
    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }


    /**
     * Replace newsletter subscriber
     *
     * @return $this
     */
    public function replaceEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }


    /**
     * Deletes newsletter subscriber data from raw data.
     *
     * @return $this
     */
    public function deleteEntity()
    {
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);
                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    $rowTtile = $rowData[self::SKU];
                    $listTitle[] = $rowTtile;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }
        if ($listTitle) {
            $this->deleteEntityFinish(array_unique($listTitle),self::TABLE_Entity);
        }
        return $this;
    }


    /**
     * Save and replace newsletter subscriber
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        $listProducts = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(ValidatorInterface::ERROR_SKU_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                $rowTtile= $rowData[self::SKU];
                $listProducts[] = $rowTtile;
                $entityList[$rowTtile][] = [
                    self::SKU => $rowData[self::SKU],
                    self::NAME => $rowData[self::NAME],
                    self::ENTITY_ID => $rowData[self::ENTITY_ID],
                    self::STOCK => $rowData[self::STOCK],
                    self::AKSESORIS_SKU => $rowData[self::AKSESORIS_SKU],
                    self::HARGA => $rowData[self::HARGA],
                    self::CREATED => $rowData[self::CREATED],
                    self::UPDATED => $rowData[self::UPDATED]
                ];
            }
            if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {
                if ($listProducts) {
                    if ($this->deleteEntityFinish(array_unique($listProducts), self::TABLE_Entity)) {
                        $this->saveEntityFinish($entityList, self::TABLE_Entity);
                    }
                }
            } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveEntityFinish($entityList, self::TABLE_Entity);
            }
        }
        return $this;
    }


    /**
     * Save product.
     *
     * @param array $entityData
     * @param string $table
     * @return $this
     * @internal param array $priceData
     */
    protected function saveEntityFinish(array $entityData, $table)
    {
        if ($entityData) {
            $tableName = $this->_connection->getTableName($table);
            $entityInsert = [];
            foreach ($entityData as $id => $entityRows) {
                    foreach ($entityRows as $row) {
                        $entityInsert[] = $row;
                    }
            }
            if ($entityInsert) {
                $this->_connection->insertOnDuplicate($tableName, $entityInsert,[
                self::SKU,
                self::NAME,
                self::ENTITY_ID,
                self::STOCK,
                self::AKSESORIS_SKU,
                self::HARGA,
                self::CREATED,
                self::UPDATED

            ]);
            }
        }
        return $this;
    }


    /**
     * Delete entity
     *
     * @param array $listTitle
     * @param $table
     * @return bool
     */
    protected function deleteEntityFinish(array $listTitle, $table)
    {
        if ($table && $listTitle) {
                try {
                    $this->countItemsDeleted += $this->_connection->delete(
                        $this->_connection->getTableName($table),
                        $this->_connection->quoteInto('sku IN (?)', $listTitle)
                    );
                    return true;
                } catch (\Exception $e) {
                    return false;
                }
        } else {
            return false;
        }
    }
}
