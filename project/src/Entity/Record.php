<?php

namespace CPANA\App\Entity;

use CPANA\App\Entity\Image;
use CPANA\App\Repository\RecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecordRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Record
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="record", fetch="EAGER", cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $cast = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cert;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $directors = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $genres = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $headline;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $external_id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $lastUpdated;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $quote;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reviewAuthor;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $skyGoId;

    /**
     * @ORM\Column(type="string", length=4000, nullable=true)
     */
    private $skyGoUrl;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $sum;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=4000, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $viewingWindowStartDate;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $viewingWindowWayToWatch;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $viewingWindowEndDate;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;


    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getImages(): ?iterable
    {
        return $this->images;
    }

    public function setImages(?iterable $images): self
    {
        $this->images = $images;

        return $this;
    }


    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setRecord($this);
        }

        return $this;
    }

    /**
     * @param \CPANA\App\Entity\Image $image
     * @return Record
     */
    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getRecord() === $this) {
                $image->setRecord(null);
            }
        }


        return $this;
    }

    public function getCast(): ?array
    {
        return $this->cast;
    }

    public function setCast(?array $cast): self
    {
        $this->cast = $cast;

        return $this;
    }

    public function getCert(): ?string
    {
        return $this->cert;
    }

    public function setCert(?string $cert): self
    {
        $this->cert = $cert;

        return $this;
    }

    public function getDirectors(): ?array
    {
        return $this->directors;
    }

    public function setDirectors(?array $directors): self
    {
        $this->directors = $directors;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getGenres(): ?array
    {
        return $this->genres;
    }

    public function setGenres(?array $genres): self
    {
        $this->genres = $genres;

        return $this;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(?string $headline): self
    {
        $this->headline = $headline;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->external_id;
    }

    public function setExternalId(?string $external_id): self
    {
        $this->external_id = $external_id;

        return $this;
    }

    public function getLastUpdated(): ?\DateTimeInterface
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(?\DateTimeInterface $lastUpdated): self
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(?string $quote): self
    {
        $this->quote = $quote;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReviewAuthor(): ?string
    {
        return $this->reviewAuthor;
    }

    public function setReviewAuthor(?string $reviewAuthor): self
    {
        $this->reviewAuthor = $reviewAuthor;

        return $this;
    }

    public function getSkyGoId(): ?string
    {
        return $this->skyGoId;
    }

    public function setSkyGoId(?string $skyGoId): self
    {
        $this->skyGoId = $skyGoId;

        return $this;
    }

    public function getSkyGoUrl(): ?string
    {
        return $this->skyGoUrl;
    }

    public function setSkyGoUrl(?string $skyGoUrl): self
    {
        $this->skyGoUrl = $skyGoUrl;

        return $this;
    }

    public function getSum(): ?string
    {
        return $this->sum;
    }

    public function setSum(?string $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getViewingWindowStartDate(): ?\DateTimeInterface
    {
        return $this->viewingWindowStartDate;
    }

    public function setViewingWindowStartDate(?\DateTimeInterface $viewingWindowStartDate): self
    {
        $this->viewingWindowStartDate = $viewingWindowStartDate;

        return $this;
    }

    public function getViewingWindowWayToWatch(): ?string
    {
        return $this->viewingWindowWayToWatch;
    }

    public function setViewingWindowWayToWatch(?string $viewingWindowWayToWatch): self
    {
        $this->viewingWindowWayToWatch = $viewingWindowWayToWatch;

        return $this;
    }

    public function getViewingWindowEndDate(): ?\DateTimeInterface
    {
        return $this->viewingWindowEndDate;
    }

    public function setViewingWindowEndDate(?\DateTimeInterface $viewingWindowEndDate): self
    {
        $this->viewingWindowEndDate = $viewingWindowEndDate;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Extract only cardImages from all images
     * @return array
     */
    function getCardImages()
    {
        $cardImages = [];
        foreach ($this->images as $image) {
            /* @var $image \CPANA\App\Entity\Image  */
            if($image->getType() === Image::TYPE_CARD ) {
                $cardImages[] = $image;
            }
        }
        return $cardImages;
    }

    /**
     * Extract only KeyArtImages from all images
     * @return array
     */
    function getKeyArtImages()
    {
        $keyArtImages = [];
        foreach ($this->images as $image) {
            /* @var $image \CPANA\App\Entity\Image  */
            if($image->getType() === Image::TYPE_KEY_ART ) {
                $keyArtImages[] = $image;
            }
        }
        return $keyArtImages;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

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
}
