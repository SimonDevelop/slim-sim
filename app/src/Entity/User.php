<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Entity\UserRepository")
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id_user", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var integer $rank
     * @ORM\ManyToOne(targetEntity="App\Entity\Rank", cascade={"persist"})
     * @ORM\JoinColumn(name="id_rank", referencedColumnName="id_rank", nullable=true)
     */
    private $rank;

    /**
     * @var datetime $date_create
     *
     * @ORM\Column(type="datetime")
     */
    private $date_create;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date_create = new \DateTime();
        $this->rank = null;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set date_create
     *
     * @param \DateTime $date_create
     *
     * @return User
     */
    public function setDateCreate(\DateTime $date_create)
    {
        $this->date_create = $date_create;
        return $this;
    }

    /**
     * Get date_create
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
    * Get rank
    *
    * @return \Doctrine\Common\Collections\Collection
    */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set rank
     *
     * @param \App\Entity\Rank $rank
     *
     * @return User
     */
    public function setRank(\App\Entity\Rank $rank = null)
    {
        $this->rank = $rank;
        return $this;
    }
}
