<?php

namespace Ml\GroupBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\GroupBundle\Entity\GroupUserRepository")
 */
class GroupUser
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ml\GroupBundle\Entity\Groupp")
    */
    private $groupp;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ml\UserBundle\Entity\User")
    */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accepted", type="boolean")
     */
    private $accepted;

	/**
     * @var string
     *
     * @ORM\Column(name="message", type="string", nullable=true)
     */
    private $message;
	
	public function __construct() {
		$this->message = NULL;
		$this->accepted = false;
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

    // Getter et setter for Groupp entity 
	  public function setGroupp(\Ml\GroupBundle\Entity\Groupp $groupp)
	  {
		$this->groupp = $groupp;
	  }
	  public function getGroupp()
	  {
		return $this->groupp;
	  }

	// Getter et setter for User entity
	  public function setUser(\Ml\UserBundle\Entity\User $user)
	  {
		$this->user = $user;
	  }
	  public function getUser()
	  {
		return $this->user;
	  }

    /**
     * Set accepted
     *
     * @param boolean $accepted
     * @return GroupUser
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;
    
        return $this;
    }

    /**
     * Get accepted
     *
     * @return boolean 
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return GroupUser
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }
}
