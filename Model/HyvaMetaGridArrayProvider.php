<?php declare(strict_types=1);

namespace VinaiKopp\HyvaMetaGrid\Model;

use Hyva\Admin\Api\HyvaGridArrayProviderInterface;

class HyvaMetaGridArrayProvider implements HyvaGridArrayProviderInterface
{
    /**
     * @var HyvaGridCollector
     */
    private $hyvaGridCollector;

    public function __construct(HyvaGridCollector $hyvaGridCollector)
    {
        $this->hyvaGridCollector = $hyvaGridCollector;
    }

    public function getHyvaGridData(): array
    {
        return $this->hyvaGridCollector->listAllGridXml();
    }
}
