<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsRepository")
 */
class News
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=10000, nullable=true)
     */
    private $news_content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uploaded;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PhotosAlbum", cascade={"persist"})
     */
    private $photosAlbum;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\VideosAlbum", cascade={"persist"})
     */
    private $videosAlbum;  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNewsContent(): ?string
    {
        return $this->news_content;
    }

    public function setNewsContent(string $news_content): self
    {
        $this->news_content = $news_content;

        return $this;
    }

    public function getUploaded(): ?\DateTimeInterface
    {
        return $this->uploaded;
    }

    public function setUploaded(\DateTimeInterface $uploaded): self
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

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
