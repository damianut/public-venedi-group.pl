<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video
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
     * @ORM\ManyToOne(targetEntity="App\Entity\VideosAlbum", inversedBy="videos")
     */
    private $videosAlbum;

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

    public function getVideosAlbum(): ?VideosAlbum
    {
        return $this->videosAlbum;
    }

    public function setVideosAlbum(?VideosAlbum $videosAlbum): self
    {
        $this->videosAlbum = $videosAlbum;

        return $this;
    }
}
