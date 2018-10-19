<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 19/10/18
 * Time: 10:17
 */

namespace App\Domain\Interfaces;

interface HtmlGeneratorInterface
{
    /**
     * @param string $type
     * @param string $resource
     * @param        $value
     *
     * @return string
     */
    public function build(string $type, string $resource, $value): string;
}