<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\ContainsHtmlCharacters;
/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"questionsWithRelations"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @ContainsHtmlCharacters
     * @Groups({"questionsWithRelations"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @ContainsHtmlCharacters
     * @Groups({"questionsWithRelations"})
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"questionsWithRelations"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"questionsWithRelations"})
     */
    private $votes;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"questionsWithRelations"})
     */
    private $isBlocked;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="questions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"questionsWithRelations"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question", orphanRemoval=true,cascade={"persist", "remove"})
     * @ORM\OrderBy({"isValidated" = "DESC", "createdAt" = "ASC"})
     * @Groups({"questionsWithRelations"})
     */
    private $answers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="questions", cascade={"persist"})
     * @Groups({"questionsWithRelations"})
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"questionsWithRelations"})
     */
    private $isSolved;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"questionsWithRelations"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"questionsWithRelations"})
     */
    private $isActive;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->createdAt = new \DateTime;
        $this->votes = 0;
        $this->isBlocked = false;
        $this->isSolved = false;
        $this->isActive = true;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId()
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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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

    public function getVotes(): ?int
    {
        return $this->votes;
    }

    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    public function getIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
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
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        // if (!$this->tags->contains($tag)) {
        $this->tags[] = $tag;
        //  }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getIsSolved(): ?bool
    {
        return $this->isSolved;
    }

    public function setIsSolved(bool $isSolved): self
    {
        $this->isSolved = $isSolved;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}