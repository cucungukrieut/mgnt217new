<?php

namespace MuliaLestari\CatalogML\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class ProdukActions extends Column
{
    /** Url path */
    const PRODUK_URL_PATH_EDIT = 'mlprodukgrid/produkmulia/edit';
    const PRODUK_URL_PATH_DELETE = 'mlprodukgrid/produkmulia/delete';

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::PRODUK_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     * Tambahan action untuk table row (column action)
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['grouping_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['grouping_id' => $item['grouping_id']]),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::PRODUK_URL_PATH_DELETE, ['grouping_id' => $item['grouping_id']]),
                        'label' => __('Hapus'),
                        'confirm' => [
                            'title' => __('Hapus "${ $.$data.attachment_name }"'),
                            'message' => __('Anda yakin ingin menghapus "${ $.$data.name }"?')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
