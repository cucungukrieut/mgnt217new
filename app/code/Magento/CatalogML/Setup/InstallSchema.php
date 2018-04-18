<?php
namespace Magento\CatalogML\Setup;

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
               // ->addColumn('aksesoris_sku', Table::TYPE_TEXT, 25, ['nullable' => true])
                //->addColumn('harga', Table::TYPE_TEXT, null, ['nullable' => false])
                ->addColumn('deskripsi', Table::TYPE_TEXT, 1000, ['nullable' => true])
                //->addColumn('kategori', Table::TYPE_TEXT, 150, ['nullable' => false])
                //->addColumn('berat', Table::TYPE_DECIMAL, null, ['nullable' => false])
                //->addColumn('type', Table::TYPE_TEXT, 150, ['nullable' => false])
                //->addColumn('img_url', Table::TYPE_TEXT, 250, ['nullable' => true])
                //->addColumn('gramasi', Table::TYPE_TEXT, 25, ['nullable' => true])
                //->addColumn('lebar', Table::TYPE_TEXT, 50, ['nullable' => false])
                //->addColumn('jenis_kain', Table::TYPE_TEXT, 150, ['nullable' => false])
                //->addColumn('kategori_warna', Table::TYPE_TEXT, 25, ['nullable' => false])
                //->addColumn('serno', Table::TYPE_TEXT, 25, ['nullable' => false])
                ->setComment('Catalog ML Table');

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('catalogml_grouping_produk_attachment_rel')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('catalogml_grouping_produk_attachment_rel'))
                ->addColumn('grouping_id', Table::TYPE_INTEGER, 10, ['nullable' => false, 'unsigned' => true])
                ->addColumn('product_id', Table::TYPE_INTEGER, 10, ['nullable' => false, 'unsigned' => true], 'Magento Product Id')
                ->addForeignKey(
                    $installer->getFkName(
                        'catalogml_grouping_produk',
                        'grouping_id',
                        'catalogml_grouping_produk_attachment_rel',
                        'grouping_id'
                    ),
                    'grouping_id',
                    $installer->getTable('catalogml_grouping_produk'),
                    'grouping_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName(
                        'catalogml_grouping_produk_attachment_rel',
                        'grouping_id',
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    'product_id',
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('CatalogML Product Attachment relation table');

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
