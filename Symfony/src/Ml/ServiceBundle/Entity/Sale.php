<?php

namespace Ml\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sale
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\ServiceBundle\Entity\SaleRepository")
 */
class Sale extends Service
{
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;
	
	/**
     * @var string
     *
     * @ORM\Column(name="linkHostedPicture", type="string", length=255, nullable=true)
     */
    protected $linkHostedPicture;
	
	 public function __construct() {
        parent::__construct();
		$this->type = "Sale";
		$this->linkHostedPicture = NULL;
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
     * Set type
     *
     * @param string $type
     * @return Sale
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set linkHostedPicture
     *
     * @param string $linkHostedPicture
     * @return Sale
     */
    public function setLinkHostedPicture($linkHostedPicture)
    {
        $this->linkHostedPicture = $linkHostedPicture;
    
        return $this;
    }

    /**
     * Get linkHostedPicture
     *
     * @return string 
     */
    public function getLinkHostedPicture()
    {
        return $this->linkHostedPicture;
    }
}
