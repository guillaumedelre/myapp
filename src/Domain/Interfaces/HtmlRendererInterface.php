<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 19/10/18
 * Time: 10:26
 */

namespace App\Domain\Interfaces;

interface HtmlRendererInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function canRender(string $type): bool;

    /**
     * @param string $resource
     * @param array  $responseData
     *
     * @return string
     */
    public function render(string $resource, array $responseData): string;
}