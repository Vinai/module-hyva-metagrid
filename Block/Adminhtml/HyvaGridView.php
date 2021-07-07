<?php declare(strict_types=1);

namespace VinaiKopp\HyvaMetaGrid\Block\Adminhtml;

use Hyva\Admin\Block\Adminhtml\HyvaGrid;
use Hyva\Admin\ViewModel\HyvaGridInterface;
use Hyva\Admin\ViewModel\HyvaGridInterfaceFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template;

class HyvaGridView extends HyvaGrid
{
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(
        Template\Context $context,
        string $gridTemplate,
        HyvaGridInterfaceFactory $gridFactory,
        RequestInterface $request,
        array $data = []
    ) {
        parent::__construct($context, $gridTemplate, $gridFactory, $data);
        $this->request = $request;
    }

    public function getGrid(): HyvaGridInterface
    {
        $this->setData('grid_name', $this->request->getParam('grid_name'));
        return parent::getGrid();
    }
}
