<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
class CategorySearch
{
 /**
 * @ORM\ManyToOne(targetEntity="App\Entity\Librairie")
 */
private $category;
public function getCategory():?Librairie
{
return $this->category;
}
public function setCategory(?Librairie $category): self
{
$this->category = $category;
return $this;
}
}
