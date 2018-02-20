<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Image class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="image")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @var \Ramsey\Uuid\Uuid Identifier.
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $file;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $temporary = true;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=false)
     */
    private $createdAt;

    /**
     * @var Recipe
     * @ORM\OneToOne(targetEntity="Recipe", inversedBy="file")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id")
     */
    private $recipe;


    /**
     * @return Uuid
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param Uuid $uuid
     */
    public function setId($uuid)
    {
        $this->id = $uuid;
    }


    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


    /**
     * @return bool
     */
    public function getTemporary()
    {
        return $this->temporary;
    }


    /**
     * @param bool $temporary
     */
    public function setTemporary($temporary)
    {
        $this->temporary = $temporary;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }


    /**
     * @return Recipe
     */
    public function getRecipe()
    {
        return $this->recipe;
    }


    /**
     * @param Recipe $recipe
     */
    public function setRecipe(Recipe $recipe)
    {
        $this->recipe = $recipe;
        $this->setTemporary(false);
    }
}
