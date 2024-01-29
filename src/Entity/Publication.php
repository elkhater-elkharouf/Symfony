<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User ;
use App\Entity\Comment ;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Notifications\NouveauPublicationNotification;
use Swift_SmtpTransport;
use Swift_Message;
use Swift_Mailer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PublicationRepository::class)
 */
class Publication
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
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="idpublication")
     */
    private $iduser;
    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="publication")
     */
    private $comments;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $abn;
    public function __construct()
    {
        $this->comments = new ArrayCollection();
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
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
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
    /**
     * @return Collection|Comment[]
    */
    

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPublication($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPublication() === $this) {
                $comment->setPublication(null);
            }
        }

        return $this;
    }

    public function getAbn(): ?int
    {
        return $this->abn;
    }

    public function setAbn(?int $abn): self
    {
        $this->abn = $abn;

        return $this;
    }
}
