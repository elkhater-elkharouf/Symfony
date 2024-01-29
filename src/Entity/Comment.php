<?php

namespace App\Entity;
use App\Entity\Publication ;
use App\Repository\PublicationRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User ;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
    * @ORM\Column(type="string", length=255)
    *  @Groups("post:prog")
    * @Assert\NotBlank(message=" this field is required ")
    *  @Assert\Length(
    *      min = 10,
    *      max = 50,
    *      minMessage = "Your content must be at least 10 characters long",
    *      maxMessage = "Your content cannot be longer than 50 characters"
    * )
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("post:prog")
    * @Assert\NotBlank(message=" this field is required ")
    *  @Assert\Length(
    *      min = 5,
    *      max = 10,
    *      minMessage = "Your  name must be at least 5 characters long",
    *      maxMessage = "Your  name cannot be longer than 510 characters"
    * )
    */
    private $authorName;
   
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\ManyToOne(targetEntity=Publication::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publication;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="idcomment")
     */
    private $iduser;

    /**
     * @ORM\Column(type="integer")
     *  @Groups("post:prog")
     */
    private $idpub;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function getPublication(): ?Publication
    {
        return $this->publication;
    }

    public function setPublication(?Publication $publication): self
    {
        $this->publication = $publication;

        return $this;
    }
    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): self
    {
        $this->iduser = $iduser;
        return $this;
    }

    public function getIdpub(): ?int
    {
        return $this->idpub;
    }

    public function setIdpub(int $idpub): self
    {
        $this->idpub = $idpub;

        return $this;
    }
}
