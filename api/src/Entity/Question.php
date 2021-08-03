<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 * @ApiResource()
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $questionText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * Добавить константы по разным типам вопроса
     */
    private $questionType;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $variants = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $answer = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;


    /**
     * @ORM\ManyToOne(targetEntity=Test::class, inversedBy="questions")
     */
    private $test;

    /**
     * @ORM\OneToMany(targetEntity=Tag::class, mappedBy="question")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=true)
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionText(): ?string
    {
        return $this->questionText;
    }

    public function setQuestionText(?string $questionText): self
    {
        $this->questionText = $questionText;

        return $this;
    }

    public function getQuestionType(): ?string
    {
        return $this->questionType;
    }

    public function setQuestionType(string $questionType): self
    {
        $this->questionType = $questionType;

        return $this;
    }

    public function getVariants(): ?array
    {
        return $this->variants;
    }

    public function setVariants(?array $variants): self
    {
        $this->variants = $variants;

        return $this;
    }

    public function getAnswer(): ?array
    {
        return $this->answer;
    }

    public function setAnswer(?array $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getTest(): ?Test
    {
        return $this->test;
    }

    public function setTest(?Test $test): self
    {
        $this->test = $test;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(?Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->setQuestion($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            if ($tag->getQuestion() === $this) {
                $tag->setQuestion(null);
            }
        }

        return $this;
    }


}
