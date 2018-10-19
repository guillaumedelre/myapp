<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 19/10/18
 * Time: 10:22
 */

namespace App\Html;

use App\Domain\Interfaces\HtmlGeneratorInterface;
use App\Domain\Interfaces\HtmlRendererInterface;

class HtmlGenerator implements HtmlGeneratorInterface
{
    /**
     * @var HtmlRendererInterface[]
     */
    private $htmlRenderers;

    /**
     * @param HtmlRendererInterface $htmlRenderer
     */
    public function addHtmlRenderer(HtmlRendererInterface $htmlRenderer)
    {
        $this->htmlRenderers[] = $htmlRenderer;
    }

    /**
     * @param string $type
     * @param string $resource
     * @param        $responseData
     *
     * @return string
     */
    public function build(string $type, string $resource, $responseData): string
    {
        $selectedRenderer = null;
        foreach ($this->htmlRenderers as $htmlRenderer) {
            if (!$htmlRenderer->canRender($type)) {
                continue;
            }

            $selectedRenderer = $htmlRenderer;
        }

        return $selectedRenderer->render($resource, $responseData);
    }

}