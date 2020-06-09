<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=400)
     */
    private $dir;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhotosAlbum", inversedBy="photos", cascade={"persist"})
     */
    private $photosAlbum;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDir(): ?string
    {
        return $this->dir;
    }

    public function setDir(string $dir): self
    {
        $this->dir = $dir;

        return $this;
    }

    public function getPhotosAlbum(): ?PhotosAlbum
    {
        return $this->photosAlbum;
    }

    public function setPhotosAlbum(?PhotosAlbum $photosAlbum): self
    {
        $this->photosAlbum = $photosAlbum;

        return $this;
    }
}
