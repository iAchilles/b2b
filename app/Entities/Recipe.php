<?php
namespace App\Entities;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Recipe class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="recipe")
 * @ORM\HasLifecycleCallbacks
 */
class Recipe
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
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetimetz", nullable=false)
     */
    private $createdAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     */
    private $user;

    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="Image", mappedBy="recipe")
     */
    private $file;

    /**
     * @var string
     */
    public $fileId;


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
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * @return Image
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @param Image $file
     */
    public function setFile(Image $file)
    {
        $this->file = $file;
    }


    /**
     * @ORM\PostPersist()
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        /**
         * @var Image $image
         */
        $image = $args->getEntityManager()->find(Image::class, $this->fileId);
        $image->setRecipe($this);
        $args->getEntityManager()->flush();
    }


    /**
     * @ORM\PostUpdate()
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $oldId = $this->getFile()->getId()->toString();
        $newId = $this->fileId;

        if (!is_null($newId) && $oldId !== $newId) {
            $name = $this->getFile()->getFile();
            @unlink(app()->basePath('/public/images/' . $name));
            $args->getEntityManager()->remove($this->getFile());
            $image = $args->getEntityManager()->find(Image::class, $this->fileId);
            $image->setRecipe($this);
            $args->getEntityManager()->flush();
        }
    }


    /**
     * @ORM\PreRemove()
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        /**
         * @var Image $file
         */
        $image = $this->getFile();
        $name  = $image->getFile();
        @unlink(app()->basePath('/public/images/' . $name));
    }


    /**
     * @param string
     */
    public function setFileId($uuid)
    {
        $this->fileId = $uuid;
    }
}
