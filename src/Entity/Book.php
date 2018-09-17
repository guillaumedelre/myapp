<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/09/18
 * Time: 11:43
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation as ApiPlatform;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ApiPlatform\ApiResource()
 */
class Book
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
}