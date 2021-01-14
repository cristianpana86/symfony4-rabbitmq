<?php

namespace CPANA\App\Entity;

use CPANA\App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    // Keep tracks of images coming from cardImages or from KeyArtImages
    public const TYPE_CARD = 'card';
    public const TYPE_KEY_ART = 'keyArt';

    // Keep track of the current status regarding image processing (caching)
    public const STATUS_CREATED             = 'created';
    public const STATUS_SENT_TO_QUEUE       = 'sent_to_queue';
    public const STATUS_PICKED_FROM_QUEUE   = 'picked_from_queue';
    public const STATUS_CACHING_SUCCESS     = 'success';
    public const STATUS_CACHING_FAILED      = 'failed';


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4000)
     */
    private $url;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="string", length=4000, nullable=true)
     */
    private $urlCache;


    /**
     * @ORM\Column(type="string", length=100,  nullable=false)
     */
    private $status;


    /**
     * @ORM\ManyToOne(targetEntity=Record::class, inversedBy="images")
     * @ORM\JoinColumn(name="record_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $record;

    /**
     * Images are either card or keyArt (see above constants)
     * @ORM\Column(type="string", length=100)
     */
    private $type;


    /**
     * Success or error messages etc
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;


    public function __construct()
    {
        $this->status = self::STATUS_CREATED;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getUrlCache(): ?string
    {
        return $this->urlCache;
    }

    public function setUrlCache(?string $urlCache): self
    {
        $this->urlCache = $urlCache;

        return $this;
    }

    /**
     * @return Record
     */
    public function getRecord(): Record
    {
        return $this->record;
    }

    public function setRecord(Record $record): self
    {
        $this->record = $record;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
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

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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
        $this->updated = new \DateTime();
    }

    public function __toString()
    {
       return $this->url;
    }
}
