<?php

namespace Ml\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Topic
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\ForumBundle\Entity\TopicRepository")
 */
class Topic
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Ml\UserBundle\Entity\User")
    */
    private $author;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbViews", type="integer")
     */
    private $nbViews;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

	public function __construct() {
		$this->creationDate = date_create(date('Y-m-d H:i:s'));
		$this->nbViews = 0;
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
     * @return Topic
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
     * Set description
     *
     * @param string $description
     * @return Topic
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set nbViews
     *
     * @param integer $nbViews
     * @return Topic
     */
    public function setNbViews($nbViews)
    {
        $this->nbViews = $nbViews;
    
        return $this;
    }

    /**
     * Get nbViews
     *
     * @return integer 
     */
    public function getNbViews()
    {
        return $this->nbViews;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Topic
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
     * Set author
     *
     * @param \Ml\UserBundle\Entity\User $author
     * @return Topic
     */
    public function setAuthor(\Ml\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return \Ml\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
