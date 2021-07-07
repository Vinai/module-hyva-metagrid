<?php declare(strict_types=1);

namespace VinaiKopp\HyvaMetaGrid\Model;

use function array_keys as keys;
use function array_map as map;
use function array_merge as merge;
use function array_reduce as reduce;
use function array_values as values;
use Hyva\Admin\Model\Config\HyvaGridDirs;
use Hyva\Admin\Model\HyvaGridDefinitionInterfaceFactory;
use Hyva\Admin\ViewModel\HyvaGridInterfaceFactory;
use Magento\Framework\App\State as AppState;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;

class HyvaGridCollector
{
    /**
     * @var HyvaGridDirs
     */
    private $hyvaGridDirs;

    /**
     * @var AppState
     */
    private $appState;

    /**
     * @var ComponentRegistrarInterface
     */
    private $componentRegistrar;

    /**
     * @var HyvaGridInterfaceFactory
     */
    private $gridFactory;

    /**
     * @var HyvaGridDefinitionInterfaceFactory
     */
    private $gridDefinitionFactory;

    public function __construct(
        HyvaGridDirs $hyvaGridDirs,
        AppState $appState,
        ComponentRegistrarInterface $componentRegistrar,
        HyvaGridDefinitionInterfaceFactory $gridDefinitionFactory
    ) {
        $this->hyvaGridDirs          = $hyvaGridDirs;
        $this->appState              = $appState;
        $this->componentRegistrar    = $componentRegistrar;
        $this->gridDefinitionFactory = $gridDefinitionFactory;
    }

    public function listAllGridXml(): array
    {
        return merge($this->buildGridDataForArea('adminhtml'), $this->buildGridDataForArea('frontend'));
    }

    private function buildGridDataForArea(string $area)
    {
        $gridDirs  = $this->appState->emulateAreaCode($area, function () {
            return $this->hyvaGridDirs->list();
        });
        $gridFiles = $this->getAllGridFiles($gridDirs);
        return values(reduce($gridFiles, function (array $map, string $file) use ($area): array {
            $gridName = substr(basename($file), 0, -4);
            if (!isset($map[$gridName])) {
                $gridDefinition                = $this->gridDefinitionFactory->create(['gridName' => $gridName]);
                $map[$gridName]['gridName']    = $gridName;
                $map[$gridName]['area']        = $area;
                $map[$gridName]['sourceType']  = keys($gridDefinition->getSourceConfig())[0];
                $map[$gridName]['source']      = isset($gridDefinition->getSourceConfig()['query'])
                    ? 'table: ' . $gridDefinition->getSourceConfig()['query']['select']['from']['table']
                    : values($gridDefinition->getSourceConfig())[0];
                $map[$gridName]['ajaxEnabled'] = ($gridDefinition->getNavigationConfig()['@isAjaxEnabled'] ?? '') !== 'false';
            }
            $map[$gridName]['modules'][] = $this->extractModuleName($file);

            return $map;
        }, []));
    }

    private function getAllGridFiles(array $dirs): array
    {
        return merge([], ...map(function (string $dir): array {
            return glob($dir . '/*.xml');
        }, $dirs));
    }

    private function extractModuleName(string $gridXmlFile): string
    {
        $moduleDir     = dirname(dirname(dirname(dirname($gridXmlFile))));
        $dirsToModules = array_flip($this->componentRegistrar->getPaths(ComponentRegistrar::MODULE));

        return $dirsToModules[$moduleDir] ?? 'unknown module';
    }
}
