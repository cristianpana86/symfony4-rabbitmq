<?php

namespace CPANA\App\Entity;

use CPANA\App\Repository\CachingLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CachingLogRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class CachingLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=4000)
     */
    protected $url;

    /**
     * @ORM\Column(type="string", length=4000, nullable=true)
     */
    protected $url_cache;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $status;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    protected $comment;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    protected $consumerInfo;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrlCache(): ?string
    {
        return $this->url_cache;
    }

    public function setUrlCache(?string $url_cache): self
    {
        $this->url_cache = $url_cache;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConsumerInfo()
    {
        return $this->consumerInfo;
    }

    /**
     * Save info about which consumer executed this task (like hostname or ip)
     * @param mixed $consumerInfo
     */
    public function setConsumerInfo($consumerInfo)
    {
        $this->consumerInfo = $consumerInfo;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
        if ($this->created == null) {
            $this->created = new \DateTime();
        }
    }



}
