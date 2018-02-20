<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * User class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */

/**
 * @ORM\Entity
 * @ORM\Table(name="""user""")
 * @ORM\HasLifecycleCallbacks
 */
class User
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
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=false)
     */
    private $createdAt;


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
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }


    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = \Illuminate\Support\Facades\Hash::make($password);
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

}
