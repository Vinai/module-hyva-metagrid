<?php declare(strict_types=1);

namespace VinaiKopp\HyvaMetaGrid\Controller\Adminhtml\Metagrid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page as BackendPage;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class ListAction extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'VinaiKopp_HyvaMetaGrid::view';

    /**
     * @var PageFactory
     */
    private $pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        /** @var BackendPage $page */
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend(__('Hyvä Grids'));

        return $page;
    }
}
