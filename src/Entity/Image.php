<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @Vich\Uploadable
 * @AppAssert\Image
 */
class Image implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Gallery", inversedBy="images", cascade={"persist"})
    * @ORM\JoinColumn(nullable=true)
    */
    private $gallery;

     /** 
     * @Vich\UploadableField(mapping="picture", fileNameProperty="fileName")
     * @Assert\File(
     * mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"},
     * mimeTypesMessage= "formats autorisés: png, jpeg, jpg, gif"
     * )
     * 
     * @var File
     */
    private $imageFile;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $fileName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;  // TODO remettre en nullable=false quand on passera en mysql

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $tmpFile;

    public function __construct(File $file=null)
    {
        if ($file)
            $this->setImageFile($file);
            
        $this->updateAt = new \DateTime();
    }

    public function __toString()
    {
        return 'Image #'.$this->getId();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile)
        {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    public function getTmpFile(): ?string
    {
        return $this->tmpFile;
    }

    public function setTmpFile(?string $tmpFile): self
    {
        $this->tmpFile = $tmpFile;

        return $this;
    }

    public function serialize()
    {
        $this->imageFile = base64_encode($this->imageFile);
    }

    public function unserialize($serialized)
    {
        $this->imageFile = base64_decode($this->imageFile);

    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }
}
