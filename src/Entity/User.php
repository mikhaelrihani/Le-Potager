<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Validator\EmailDomain;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"gardensWithRelations","usersWithRelations"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(max=64)
     * @Groups({"gardensWithRelations","usersWithRelations"})
     */
    private $username;

    /**
     *  @var string The hashed password
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * @Assert\Length(max=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     * @EmailDomain(blocked={"badDomain.fr"})
     * @Assert\Length(max=255)
     * @Groups({"gardensWithRelations","usersWithRelations"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(max=64)
     * @Groups({"gardensWithRelations","usersWithRelations"})
     */
    private $phone;

    /**
     * @ORM\Column(type="json", length=128)
     * @Groups({"usersWithRelations"})
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url
     * @Groups({"gardensWithRelations","usersWithRelations"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\NotBlank
     * @Groups({"usersWithRelations"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"usersWithRelations"})
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Garden::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     * @Groups({"usersWithRelations"})
     */
    private $gardens;

    /**
     * @ORM\OneToMany(targetEntity=Favorite::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     * @Groups({"usersWithRelations"})
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="user", cascade={"persist"})
     * @Groups({"usersWithRelations"})
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     * @Groups({"usersWithRelations"})
     */
    private $answers;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->gardens = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    /**
     * @Assert\IsTrue(message="The password cannot match your username")
     */
    public function isPasswordSafe()
    {
        return $this->username !== $this->password;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->createdAt = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updatedAt = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Garden>
     */
    public function getGardens(): Collection
    {
        return $this->gardens;
    }

    public function addGarden(Garden $garden): self
    {
        if (!$this->gardens->contains($garden)) {
            $this->gardens[] = $garden;
            $garden->setUser($this);
        }

        return $this;
    }

    public function removeGarden(Garden $garden): self
    {
        if ($this->gardens->removeElement($garden)) {
            // set the owning side to null (unless already changed)
            if ($garden->getUser() === $this) {
                $garden->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->setUser($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getUser() === $this) {
                $favorite->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Get the questions associated with this user.
     *
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    /**
     * Add a question to the user.
     *
     * @param Question $question
     */
    public function addQuestion(Question $question): void
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setUser($this);
        }
    }

    /**
     * Remove a question from the user.
     *
     * @param Question $question
     */
    public function removeQuestion(Question $question): void
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);

            // Remove the association from the question as well
            $question->setUser(null);
        }
    }
  /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setUser($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getUser() === $this) {
                $answer->setUser(null);
            }
        }

        return $this;
    }
    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}