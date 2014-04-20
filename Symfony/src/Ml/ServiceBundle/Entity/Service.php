<?php

namespace Ml\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\MappedSuperclass
 * 
 */
abstract class Service
{

    /**
    * @ORM\ManyToOne(targetEntity="Ml\UserBundle\Entity\User",inversedBy="service")
    * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var integer
     *
     * @ORM\Column(name="indicate", type="integer")
     */
    private $indicate; //signalement abus

     /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visibility", type="boolean")
     */
    private $visibility;//visibilité de l'annonce sur le site si abus ou vente terminee
	
	/**
    * @ORM\ManyToOne(targetEntity="Ml\GroupBundle\Entity\Groupp")
    * @ORM\JoinColumn(nullable=true)
     */
    private $associatedGroup;

	
	public function __construct() {
		$this->creationDate = date_create(date('Y-m-d'));
		$this->indicate = 0;
		$this->visibility = true;
		$this->price = 0;
		$this->associatedGroup = NULL;
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
     * Set title
     *
     * @param string $title
     * @return Service
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Service
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Service
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set indicate
     *
     * @param integer $indicate
     * @return Service
     */
    public function setIndicate($indicate)
    {
        $this->indicate = $indicate;

        return $this;
    }

    /**
     * Get indicate
     *
     * @return integer 
     */
    public function getIndicate()
    {
        return $this->indicate;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Service
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set visibility
     *
     * @param boolean $visibility
     * @return Service
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return boolean 
     */
    public function getVisibility()
    {
        return $this->visibility;
    }
	
		// Getter et setter pour l'entité User
	  public function setUser(\Ml\UserBundle\Entity\User $user)
	  {
		$this->user = $user;
	  }
	  
	  public function getUser()
	  {
		return $this->user;
	  }

    /**
     * Set associatedGroup
     *
     * @param \Ml\UserBundle\Entity\Groupp $associatedGroup
     * @return Service
     */
    public function setAssociatedGroup(\Ml\UserBundle\Entity\Groupp $associatedGroup = null)
    {
        $this->associatedGroup = $associatedGroup;
    
        return $this;
    }

    /**
     * Get associatedGroup
     *
     * @return \Ml\UserBundle\Entity\Groupp 
     */
    public function getAssociatedGroup()
    {
        return $this->associatedGroup;
    }
}
