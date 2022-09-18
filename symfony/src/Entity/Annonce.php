<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;


/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    const EMPLOI = 1;
    const IMMOBILIER = 2;
    const AUTOMOBILE = 3;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity=Emploi::class, inversedBy="annonces", cascade={"persist", "remove"})
     *
     */
    private $emploi;

    /**
     * @ORM\ManyToOne(targetEntity=Automobile::class, inversedBy="annonces", cascade={"persist", "remove"})
     *
     */
    private $automobile;

    /**
     * @ORM\ManyToOne(targetEntity=Immobilier::class, inversedBy="annonces", cascade={"persist", "remove"})
     */
    private $immobilier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getEmploi(): ?emploi
    {
        return $this->emploi;
    }

    public function setEmploi(?emploi $emploi): self
    {
        $this->emploi = $emploi;

        return $this;
    }

    public function getAutomobile(): ?automobile
    {
        return $this->automobile;
    }

    public function setAutomobile(?automobile $automobile): self
    {
        $this->automobile = $automobile;

        return $this;
    }

    public function getImmobilier(): ?immobilier
    {
        return $this->immobilier;
    }

    public function setImmobilier(?immobilier $immobilier): self
    {
        $this->immobilier = $immobilier;

        return $this;
    }

    /**
     * @Ignore()
     * @return int|null
     */
    public function getCategorieId() {
        if ($this->getEmploi()) {
            return self::EMPLOI;
        } elseif ($this->getImmobilier()) {
            return self::IMMOBILIER;
        } elseif ($this->getAutomobile()) {
            return self::AUTOMOBILE;
        }

        return null;
    }
}
