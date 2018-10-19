<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 19/10/18
 * Time: 11:05
 */

namespace App\Html\Traits;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

trait TwigEngineAwareTrait
{
    /**
     * @var TwigEngine
     */
    private $twigEngine;

    /**
     * @return TwigEngine
     */
    public function getTwigEngine(): TwigEngine
    {
        return $this->twigEngine;
    }

    /**
     * @param EngineInterface $twigEngine
     *
     * @return $this
     */
    public function setTwigEngine(EngineInterface $twigEngine)
    {
        $this->twigEngine = $twigEngine;

        return $this;
    }

}