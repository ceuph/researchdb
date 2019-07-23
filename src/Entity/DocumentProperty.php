<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentPropertyRepository")
 */
class DocumentProperty
{
    const PROPERTY_CONFERENCE = 'CONFERENCE';
    const PROPERTY_PRESENTATION = 'PRESENTATION';
    const PROPERTY_PUBLICATION = 'PUBLICATION';
    const PROPERTY_EXHIBITION = 'EXHIBITION';
    const PROPERTY_INTERNATIONAL = 'INTERNATIONAL';
    const PROPERTY_PATENT = 'PATENT';
    const PROPERTY_LOCATION = 'LOCATION';
    const PROPERTY_SCHOOL_YEAR = 'SCHOOL_YEAR';
    const PROPERTY_AWARD = 'AWARD';
    const PROPERTY_AWARD_BODY = 'AWARD_BODY';
    const PROPERTY_NATPROD = 'NATPROD';
    const TYPE_TEXT = 'TEXT';
    const TYPE_NUMBER = 'NUMBER';
    const TYPE_BOOL = 'BOOL';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Document", inversedBy="documentProperties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }
}
