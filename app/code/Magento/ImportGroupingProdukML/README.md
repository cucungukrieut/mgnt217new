# Import ML CUstom

Implementasi import grouping dengan memanggil entity [import.xml](/Magento/ImportProdukML/etc/import.xml)

    <entity name="import_namamodule" label="Nama Module" 
        model="Magento\NamaModule\Model\Import\Module" 
        behaviorModel="Magento\ImportExport\Model\Source\Import\Behavior\Basic" />