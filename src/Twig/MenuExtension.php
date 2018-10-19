<?php

namespace App\Twig;

use App\Redis\ApiDocStorage;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    /**
     * @var array
     */
    private $apiDoc;

    /**
     * MenuExtension constructor.
     *
     * @param ApiDocStorage    $apiDocStorage
     * @param DecoderInterface $decoder
     */
    public function __construct(ApiDocStorage $apiDocStorage, DecoderInterface $decoder)
    {
        $this->apiDoc = $decoder->decode($apiDocStorage->getApiDocAuthentication(), JsonEncoder::FORMAT);
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function doSomething($value)
    {
        // ...
    }
}
