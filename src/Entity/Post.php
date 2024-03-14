<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePost = null;

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $Commentaires;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $createur = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'postsAimes')]
    private Collection $jaime;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    public function __construct()
    {
        $this->Commentaires = new ArrayCollection();
        $this->jaime = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDatePost(): ?\DateTimeInterface
    {
        return $this->datePost;
    }

    public function setDatePost(\DateTimeInterface $datePost): static
    {
        $this->datePost = $datePost;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->Commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->Commentaires->contains($commentaire)) {
            $this->Commentaires->add($commentaire);
            $commentaire->setPost($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->Commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPost() === $this) {
                $commentaire->setPost(null);
            }
        }

        return $this;
    }

    public function __toString(): String
    {
        return 'Post du' . $this->datePost->format('d/m/y h:i:s') . ' : ' . substr($this->contenu, 0, 20) . '...';
    }

    public function getCreateur(): ?Utilisateur
    {
        return $this->createur;
    }

    public function setCreateur(?Utilisateur $createur): static
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getJaime(): Collection
    {
        return $this->jaime;
    }

    public function addJaime(Utilisateur $jaime): static
    {
        if (!$this->jaime->contains($jaime)) {
            $this->jaime->add($jaime);
        }

        return $this;
    }

    public function removeJaime(Utilisateur $jaime): static
    {
        $this->jaime->removeElement($jaime);

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }
}
