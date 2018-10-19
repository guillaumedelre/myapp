<?php

namespace App\Twig;

use App\Domain\Html\Molecules;
use App\Domain\Interfaces\HtmlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TableExtension extends AbstractExtension
{
    /**
     * @var HtmlGeneratorInterface
     */
    private $htmlGenerator;

    /**
     * TableExtension constructor.
     *
     * @param HtmlGeneratorInterface $htmlGenerator
     */
    public function __construct(HtmlGeneratorInterface $htmlGenerator)
    {
        $this->htmlGenerator = $htmlGenerator;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('table', [$this, 'tableResponsive']),
        ];
    }

    /**
     * @param string $resource
     * @param array  $responseData
     *
     * @return string
     */
    public function tableResponsive(string $resource, array $responseData): string
    {
        return $this->htmlGenerator->build(
            Molecules::MOLECULE_TABLE_RESPONSIVE,
            $resource,
            $responseData
        );
    }
}
