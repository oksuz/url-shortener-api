<?php

namespace App\Entity;

use App\Repository\ShortUrlRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=ShortUrlRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=false)
 */
class ShortUrl
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=1000, nullable=false)
     */
    private string $url;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private DateTime $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private DateTime $deletedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return ShortUrl
     */
    public function setUrl(string $url): ShortUrl
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
}
