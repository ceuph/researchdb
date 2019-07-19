<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="integer")
     */
    private $yearCreated;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $monthCreated;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dayCreated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateModified;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $updatedBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DocumentAuthor", mappedBy="document", orphanRemoval=true)
     */
    private $documentAuthors;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DocumentProperty", mappedBy="document", orphanRemoval=true)
     */
    private $documentProperties;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $abstractFile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DocumentAttachment", mappedBy="document", orphanRemoval=true)
     */
    private $documentAttachments;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarks;

    public function __construct()
    {
        $this->documentAuthors = new ArrayCollection();
        $this->documentProperties = new ArrayCollection();
        $this->documentAttachments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getSubject();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setDateCreated(new \DateTime());
        $this->setDateModified(new \DateTime());
        $this->setCreatedBy('none');
        $this->setUpdatedBy('none');
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setDateModified(new \DateTime());
        $this->setUpdatedBy('none');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getYearCreated(): ?int
    {
        return $this->yearCreated;
    }

    public function setYearCreated(int $yearCreated): self
    {
        $this->yearCreated = $yearCreated;

        return $this;
    }

    public function getMonthCreated(): ?int
    {
        return $this->monthCreated;
    }

    public function setMonthCreated(?int $monthCreated): self
    {
        $this->monthCreated = $monthCreated;

        return $this;
    }

    public function getDayCreated(): ?int
    {
        return $this->dayCreated;
    }

    public function setDayCreated(?int $dayCreated): self
    {
        $this->dayCreated = $dayCreated;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(\DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return Collection|DocumentAuthor[]
     */
    public function getDocumentAuthors(): Collection
    {
        return $this->documentAuthors;
    }

    public function addDocumentAuthor(DocumentAuthor $documentAuthor): self
    {
        if (!$this->documentAuthors->contains($documentAuthor)) {
            $this->documentAuthors[] = $documentAuthor;
            $documentAuthor->setDocument($this);
        }

        return $this;
    }

    public function removeDocumentAuthor(DocumentAuthor $documentAuthor): self
    {
        if ($this->documentAuthors->contains($documentAuthor)) {
            $this->documentAuthors->removeElement($documentAuthor);
            // set the owning side to null (unless already changed)
            if ($documentAuthor->getDocument() === $this) {
                $documentAuthor->setDocument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DocumentProperty[]
     */
    public function getDocumentProperties(): Collection
    {
        return $this->documentProperties;
    }

    public function addDocumentProperty(DocumentProperty $documentProperty): self
    {
        if (!$this->documentProperties->contains($documentProperty)) {
            $this->documentProperties[] = $documentProperty;
            $documentProperty->setDocument($this);
        }

        return $this;
    }

    public function removeDocumentProperty(DocumentProperty $documentProperty): self
    {
        if ($this->documentProperties->contains($documentProperty)) {
            $this->documentProperties->removeElement($documentProperty);
            // set the owning side to null (unless already changed)
            if ($documentProperty->getDocument() === $this) {
                $documentProperty->setDocument(null);
            }
        }

        return $this;
    }

    public function getAbstractFile(): ?string
    {
        return $this->abstractFile;
    }

    public function setAbstractFile(?string $abstractFile): self
    {
        $this->abstractFile = $abstractFile;

        return $this;
    }

    /**
     * @return Collection|DocumentAttachment[]
     */
    public function getDocumentAttachments(): Collection
    {
        return $this->documentAttachments;
    }

    public function addDocumentAttachment(DocumentAttachment $documentAttachment): self
    {
        if (!$this->documentAttachments->contains($documentAttachment)) {
            $this->documentAttachments[] = $documentAttachment;
            $documentAttachment->setDocument($this);
        }

        return $this;
    }

    public function removeDocumentAttachment(DocumentAttachment $documentAttachment): self
    {
        if ($this->documentAttachments->contains($documentAttachment)) {
            $this->documentAttachments->removeElement($documentAttachment);
            // set the owning side to null (unless already changed)
            if ($documentAttachment->getDocument() === $this) {
                $documentAttachment->setDocument(null);
            }
        }

        return $this;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(?string $remarks): self
    {
        $this->remarks = $remarks;

        return $this;
    }
}
