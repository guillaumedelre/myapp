<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 19/10/18
 * Time: 12:53
 */

namespace App\Domain\Interfaces;

interface HtmlRendererAwareInterface
{
    /**
     * @param HtmlRendererInterface[] $htmlRenderers
     *
     * @return HtmlRendererAwareInterface
     */
    public function setHtmlRenderers(array $htmlRenderers = []): HtmlRendererAwareInterface;

    /**
     * @return HtmlRendererInterface[]
     */
    public function getHtmlRenderers(): array;
}