<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 19/10/18
 * Time: 10:31
 */

namespace App\Html\Renderer;

use App\Domain\Html\Molecules;
use App\Domain\Interfaces\HtmlRendererInterface;
use App\Html\Traits\TwigEngineAwareTrait;
use App\Hydra\DocumentationExtractor;

class TableResponsiveRenderer implements HtmlRendererInterface
{
    use TwigEngineAwareTrait;

    /**
     * @var DocumentationExtractor
     */
    private $documentationExtractor;

    /**
     * TableResponsiveRenderer constructor.
     *
     * @param DocumentationExtractor $documentationExtractor
     */
    public function __construct(DocumentationExtractor $documentationExtractor)
    {
        $this->documentationExtractor = $documentationExtractor;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function canRender(string $type): bool
    {
        return Molecules::MOLECULE_TABLE_RESPONSIVE === $type;
    }

    /**
     * @param string $resource
     * @param array  $responseData
     *
     * @return string
     * @throws \Twig\Error\Error
     */
    public function render(string $resource, array $responseData): string
    {
        $data = [
            'resource' => $resource,
            'items'    => $responseData['hydra:member'],
            'table'    => [
                'attrId'         => "{$responseData['@id']}-datatable",
                'attrClass'      => 'table table-striped table-hover',
                'itemProperties' => $this->documentationExtractor->getPropertiesForSupportedClassname($resource),
                //                'caption'   => $this->documentationExtractor->getPropertiesForSupportedClassname($resource),
            ],
        ];

        return $this->getTwigEngine()
                    ->render(
                        '/molecules/' . Molecules::MOLECULE_TABLE_RESPONSIVE . '.html.twig',
                        $data
                    ) ?? '';
    }

}