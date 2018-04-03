<?php
namespace Test\HelloTest\Controller\Adminhtml\Hello;
use \Magento\Backend\App\Action;


/**
 * Created by PhpStorm.
 * User: MULIA
 * Date: 18/01/2018
 * Time: 14:34
 */
class Index extends Action
{

    /**
    protected $pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }*/


    /**
     * Execute action based on request and return result
     *
     */
    public function execute()
    {
        // TODO: Implement execute() method.
        ///return $resultPage = $this->pageFactory->create();
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}