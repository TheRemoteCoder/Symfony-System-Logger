<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use function hash;
use function strtoupper;
use function trim;

/**
 * Simple custom database logger.
 *
 * Intended use: Dev/Debug and monitoring purposes as alternative
 * to wasteful CLI output and the default file based logging.
 *
 * @todo CHECK: Hashsum uniqueness safe and good to do this way? What should happen on failure?
 * @ORM\Entity(repositoryClass="App\Repository\SystemLogsRepository")
 * @ORM\Table(name="system_logs")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="hashsum", message="Hashsum cannot be duplicate.")
 */
class SystemLogs
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $createdAt;

    /**
     * @var string
     * @ORM\Column(name="hashsum", type="string", length=255, nullable=false, unique=true)
     * @Assert\NotBlank
     */
    private $hashsum;

    /**
     * @var string
     * @ORM\Column(name="channel", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $channel;

    /**
     * @var string
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getHashsum() ?? '';
    }

    /**
     * @ORM\PrePersist
     */
    public function doPrePersistHook()
    {
        if ($this->getCreatedAt() === null) $this->setCreatedAt(new DateTime());
        if ($this->getHashsum() === null) $this->setHashsum();
    }

    /**
     * @ORM\PreUpdate
     * @return void
     */
    public function doPreUpdateHook()
    {
        $this->setHashsum();
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return void
     */
    public function setHashsum()
    {
        $this->hashsum = hash('sha256', $this->channel . $this->content);
    }

    /**
     * @return null|string
     */
    public function getHashsum(): ?string
    {
        return $this->hashsum;
    }

    /**
     * @param string $channel
     * @return SystemLog
     */
    public function setChannel(string $channel): self
    {
        $this->channel = strtoupper(trim($channel));

        return $this;
    }

    /**
     * @return null|string
     */
    public function getChannel(): ?string
    {
        return $this->channel;
    }

    /**
     * @param string $content
     * @return SystemLog
     */
    public function setContent(string $content): self
    {
        $this->content = trim($content);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param DateTime $date
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return null|DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }
}
