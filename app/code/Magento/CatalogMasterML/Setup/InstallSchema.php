<?php
namespace Magento\CatalogMasterML\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;


/**
 * Class InstallSchema
 * For create table if not exist when install module or upgrade
 * @package Magento\CatalogMasterML\Setup
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

        if (!$installer->tableExists('catalogml_produk')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('catalogml_produk'))
                ->addColumn(
                    'produk_id',
                    Table::TYPE_INTEGER,
                    10,
                    ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true]
                )
                ->addColumn('created', Table::TYPE_DATETIME, null, ['nullable' => false])
                ->addColumn('updated', Table::TYPE_DATETIME, null, ['nullable' => false])
                ->addColumn('kode', Table::TYPE_TEXT, 150, ['nullable' => false])
                ->addColumn('nama', Table::TYPE_TEXT, 500, ['nullable' => false])
                ->addColumn('isactive', Table::TYPE_INTEGER, 1, ['nullable' => false])
                ->addColumn('qty_bruto', Table::TYPE_DECIMAL, null, ['nullable' => true])
                ->addColumn('qty_netto', Table::TYPE_DECIMAL, null, ['nullable' => false])
                ->addColumn('kategori', Table::TYPE_TEXT, 10, ['nullable' => true])
                ->addColumn('harga', Table::TYPE_DECIMAL, null, ['nullable' => false])
                ->addColumn('lebar', Table::TYPE_TEXT, 15, ['nullable' => true])
                ->addColumn('gramasi', Table::TYPE_TEXT, 15, ['nullable' => true])
                ->addColumn('lot', Table::TYPE_TEXT, 25, ['nullable' => true])
                ->addColumn('img_url', Table::TYPE_TEXT, 500, ['nullable' => true])
                ->addColumn('kategori_warna', Table::TYPE_TEXT, 25, ['nullable' => false])
                ->setComment('Catalog Master ML Table');

            $installer->getConnection()->createTable($table);
        }


        $installer->endSetup();
    }
}
