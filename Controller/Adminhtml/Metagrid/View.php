<?php declare(strict_types=1);

namespace VinaiKopp\HyvaMetaGrid\Controller\Adminhtml\Metagrid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page as BackendPage;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;

class View extends Action
{
    const ADMIN_RESOURCE = 'VinaiKopp_HyvaMetaGrid::view';

    private PageFactory $pageFactory;

    private RequestInterface $request;

    public function __construct(Context $context, PageFactory $pageFactory, RequestInterface $request)
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->request     = $request;
    }

    public function execute()
    {
        /** @var BackendPage $page */
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend(__('Hyvä Grid: %1', $this->getGridName()));

        return $page;
    }

    private function getGridName(): string
    {
        return $this->request->getParam('grid_name', '');
    }
}
