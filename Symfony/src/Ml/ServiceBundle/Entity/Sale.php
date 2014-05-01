<?php

namespace Ml\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\File(maxSize="6000000")
	 * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    protected $file;
	
	/**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;
	
	 public function __construct() {
        parent::__construct();
		$this->type = "Sale";
		$this->file = NULL;
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


	public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/documents';
    }
	
	public function upload() {
		// la propriété « file » peut être vide si le champ n'est pas requis
		if (null === $this->file) {
			return;
		}

		// utilisez le nom de fichier original ici mais
		// vous devriez « l'assainir » pour au moins éviter
		// quelconques problèmes de sécurité

		// la méthode « move » prend comme arguments le répertoire cible et
		// le nom de fichier cible où le fichier doit être déplacé
		$this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());

		// définit la propriété « path » comme étant le nom de fichier où vous
		// avez stocké le fichier
		$this->path = $this->file->getClientOriginalName();

		// « nettoie » la propriété « file » comme vous n'en aurez plus besoin
		$this->file = null;
	}

    /**
     * Set file
     *
     * @param string $file
     * @return Sale
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Sale
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
}
