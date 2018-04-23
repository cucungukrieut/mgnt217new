<?php
namespace MuliaLestari\CatalogML\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;


/**
 * Class InstallSchema
 * For create table if not exist when install module or upgrade
 * @package Magento\CatalogML\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     * Running ketika module di install (setup:upgrade)
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (!$installer->tableExists('catalogml_grouping_produk')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('catalogml_grouping_produk'))
                ->addColumn(
                    'grouping_id',
                    Table::TYPE_INTEGER,
                    10,
                    ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true]
                )
                ->addColumn('created', Table::TYPE_DATETIME, null, ['nullable' => false])
                ->addColumn('updated', Table::TYPE_DATETIME, null, ['nullable' => false])
                ->addColumn('grouping_kode', Table::TYPE_TEXT, 10, ['nullable' => false], 'Grouping Product Code')
                ->addColumn('custom_kode', Table::TYPE_TEXT, 10, ['default' => ''], 'Custom code from value')
                ->addColumn('nama', Table::TYPE_TEXT, 500, ['nullable' => false])
                ->addColumn('isactive', Table::TYPE_INTEGER, 1, ['nullable' => false])
                ->addColumn('deskripsi', Table::TYPE_TEXT, 1000, ['nullable' => true])
                ->setComment('Catalog ML Table');

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
