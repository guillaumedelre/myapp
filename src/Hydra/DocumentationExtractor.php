<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 19/10/18
 * Time: 16:37
 */

namespace App\Hydra;

use App\Redis\ApiDocStorage;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class DocumentationExtractor
{
    /**
     * @var ApiDocStorage
     */
    private $apiDocStorage;

    /**
     * @var JsonEncoder
     */
    private $jsonEncoder;

    /**
     * @var array
     */
    private static $propertyRanges = [
        'xmls:string',
        'xmls:integer',
        'xmls:boolean',
    ];

    /**
     * DocumentationExtractor constructor.
     *
     * @param ApiDocStorage $apiDocStorage
     * @param JsonEncoder   $jsonEncoder
     */
    public function __construct(ApiDocStorage $apiDocStorage, JsonEncoder $jsonEncoder)
    {
        $this->apiDocStorage = $apiDocStorage;
        $this->jsonEncoder = $jsonEncoder;
    }

    /**
     * @param string $supportedClassname
     *
     * @return array
     */
    public function getPropertiesForSupportedClassname(string $supportedClassname): array
    {
        $apiDoc = $this->jsonEncoder->decode(
            $this->apiDocStorage->getApiDocAuthentication(),
            JsonEncoder::FORMAT
        );

        $foundSupportedClass = null;
        foreach ($apiDoc['hydra:supportedClass'] as $supportedClass) {
            if (mb_strtolower($supportedClass['hydra:title']) !== mb_strtolower($supportedClassname)) {
                continue;
            }

            $foundSupportedClass = $supportedClass;
        }

        $properties = [];

        if (empty($foundSupportedClass)) {
            return $properties;
        }

        foreach ($foundSupportedClass['hydra:supportedProperty'] as $supportedProperty) {
            if (!in_array($supportedProperty['hydra:property']['range'], self::$propertyRanges)) {
                continue;
            }

            $type = '';
            $sorting = '';
            switch ($supportedProperty['hydra:property']['range']) {
                case 'xmls:string':
                    $sorting = 'sort-alpha';
                    $type = 'string';
                    break;
                case 'xmls:integer':
                    $sorting = 'sort-numeric';
                    $type = 'integer';
                    break;
                case 'xmls:boolean':
                    $type = 'boolean';
                    break;
            }

            $properties[] = [
                'title' => $supportedProperty['hydra:title'],
                'attrClass' => $sorting,
                'type' => $type,
            ];
        }

        return $properties;
    }
}