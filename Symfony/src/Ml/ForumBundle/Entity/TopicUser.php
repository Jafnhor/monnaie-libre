<?php

namespace Ml\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TopicUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\ForumBundle\Entity\TopicUserRepository")
 */
class TopicUser
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ml\ForumBundle\Entity\Topic")
    */
    private $topic;

    /**
	 * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ml\UserBundle\Entity\User")
    */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="avis", type="boolean", nullable = true)
     */
    private $avis;
	
	public function __construct() {
		$this->avis = NULL;
	}

    /**
     * Set avis
     *
     * @param boolean $avis
     * @return TopicUser
     */
    public function setAvis($avis)
    {
        $this->avis = $avis;
    
        return $this;
    }

    /**
     * Get avis
     *
     * @return boolean 
     */
    public function getAvis()
    {
        return $this->avis;
    }

    /**
     * Set topic
     *
     * @param \Ml\ForumBundle\Entity\Topic $topic
     * @return TopicUser
     */
    public function setTopic(\Ml\ForumBundle\Entity\Topic $topic)
    {
        $this->topic = $topic;
    
        return $this;
    }

    /**
     * Get topic
     *
     * @return \Ml\ForumBundle\Entity\Topic 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set user
     *
     * @param \Ml\UserBundle\Entity\User $user
     * @return TopicUser
     */
    public function setUser(\Ml\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Ml\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
