<?php
namespace Magento\ImportProdukML\Model\Import;

use Magento\ImportProdukML\Model\Import\ImportProdukML\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Framework\App\ResourceConnection;
use \Magento\Framework\Json\Helper\Data;
use \Magento\ImportExport\Model\ResourceModel\Helper;
use \Magento\Framework\Stdlib\StringUtils;
use \Magento\Customer\Model\GroupFactory;
use \Magento\ImportExport\Model\Import;
use \Magento\ImportExport\Model\Import\Entity\AbstractEntity;
use \Magento\Framework\Model\ResourceModel\Db\TransactionManagerInterface;

class ImportGroupingWarnaML extends AbstractEntity {

    /**
     * @var TransactionManagerInterface
     */
    protected $transactionManager;

    /**
     * Master Produk
     */
    const GROUPINGWARNA_created = 'created';
    const GROUPINGWARNA_updated = 'updated';
    const GROUPINGWARNA_kode = 'grouping_kode';
    const GROUPINGWARNA_custom_kode = 'custom_kode';
    const GROUPINGWARNA_nama = 'nama';
    const GROUPINGWARNA_isactive = 'isactive';


    /**
     * Table Product
     *
     * @var string
     */
    const TABLE_GROUPING_PRODUK = 'catalogml_grouping_warna';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_KODE_IS_EMPTY => 'KODE kosong',
    ];

     protected $_permanentAttributes = [self::GROUPINGWARNA_kode];

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
        self::GROUPINGWARNA_created,
        self::GROUPINGWARNA_updated,
        self::GROUPINGWARNA_kode,
        self::GROUPINGWARNA_custom_kode,
        self::GROUPINGWARNA_nama,
        self::GROUPINGWARNA_isactive
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
     * ImportProdukML constructor.
     *
     * @param Data $jsonHelper
     * @param \Magento\ImportExport\Helper\Data $importExportData
     * @param \Magento\ImportExport\Model\ResourceModel\Import\Data $importData
     * @param ResourceConnection $resource
     * @param Helper $resourceHelper
     * @param StringUtils $string
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @param GroupFactory $groupFactory
     * @param TransactionManagerInterface $transactionManager
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
        return 'import_grouping_warna';
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
        if (!isset($rowData[self::GROUPINGWARNA_kode]) || empty($rowData[self::GROUPINGWARNA_kode])) {
            $this->addRowError(ValidatorInterface::ERROR_KODE_IS_EMPTY, $rowNum);
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
                    $rowProducts = $rowData[self::GROUPINGWARNA_kode];
                    $listProducts[] = $rowProducts;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }
        if ($listProducts) {
            $this->deleteBody(array_unique($listProducts),self::TABLE_GROUPING_PRODUK);
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
                    $this->addRowError(ValidatorInterface::ERROR_KODE_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }

                $rowProducts = $rowData[self::GROUPINGWARNA_kode];
                $listProducts[] = $rowProducts;
                $bodyList[$rowProducts][] = [
                    self::GROUPINGWARNA_created => $rowData[self::GROUPINGWARNA_created],
                    self::GROUPINGWARNA_updated => $rowData[self::GROUPINGWARNA_updated],
                    self::GROUPINGWARNA_custom_kode => $rowData[self::GROUPINGWARNA_custom_kode],
                    self::GROUPINGWARNA_kode => $rowData[self::GROUPINGWARNA_kode],
                    self::GROUPINGWARNA_nama => $rowData[self::GROUPINGWARNA_nama],
                    self::GROUPINGWARNA_isactive => $rowData[self::GROUPINGWARNA_isactive]
                ];
            }

            if (Import::BEHAVIOR_REPLACE == $behavior)
            {
                if ($listProducts)
                {
                    if ($this->deleteBody(array_unique($listProducts), self::TABLE_GROUPING_PRODUK)) {
                        $this->saveBody($bodyList, self::TABLE_GROUPING_PRODUK);
                    }
                }
            } elseif (Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveBody($bodyList, self::TABLE_GROUPING_PRODUK);
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
                    self::GROUPINGWARNA_created,
                    self::GROUPINGWARNA_updated,
                    self::GROUPINGWARNA_kode,
                    self::GROUPINGWARNA_custom_kode,
                    self::GROUPINGWARNA_nama,
                    self::GROUPINGWARNA_isactive
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
                    $this->_connection->quoteInto('custom_kode IN (?)', $listProducts)
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
