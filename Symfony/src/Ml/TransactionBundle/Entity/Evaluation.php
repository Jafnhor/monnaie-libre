<?php

namespace Ml\TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evaluation
 *
 * @ORM\MappedSuperclass
 * 
 */
abstract class Evaluation
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
    * @ORM\ManyToOne(targetEntity="Ml\UserBundle\Entity\User",inversedBy="service")
    * @ORM\JoinColumn(nullable=false)
     */
    private $subscriber;

    /**
    * @ORM\ManyToOne(targetEntity="Ml\UserBundle\Entity\User",inversedBy="service")
    * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @var boolean
     *
     * @ORM\Column(name="payed", type="boolean")
     */
    private $payed;

    /**
     * @var integer
     *
     * @ORM\Column(name="eval", type="integer")
     */
    private $eval;


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
     * Set payed
     *
     * @param boolean $payed
     * @return Eval
     */
    public function setPayed($payed)
    {
        $this->payed = $payed;

        return $this;
    }

    /**
     * Get payed
     *
     * @return boolean 
     */
    public function getPayed()
    {
        return $this->payed;
    }

    /**
     * Set eval
     *
     * @param integer $eval
     * @return Eval
     */
    public function setEval($eval)
    {
        $this->eval = $eval;

        return $this;
    }

    /**
     * Get eval
     *
     * @return integer 
     */
    public function getEval()
    {
        return $this->eval;
    }
}