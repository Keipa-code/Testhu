<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TestRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TestRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'security' => "is_granted('ROLE_USER') or is_granted('ROLE_ANON')"
        ],
        'post' => [
            'security' => "is_granted('ROLE_ANON')"
        ],
    ],
    itemOperations: [
        'put' => [
            'security' => "is_granted('ROLE_USER') or is_granted('ROLE_ANON')"
        ],
        'get' => [
            'security' => "is_granted('ROLE_USER') or object == user"
        ]
    ],
    attributes: ['pagination_items_per_page' => 2],
    denormalizationContext: ['groups' => ['tests:write']],
    normalizationContext: ['groups' => ['tests:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['testName' => 'partial', 'tags.tagName' => 'exact'])]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['tests:read', 'users:read'])]
    private $id;

    #[ORM\Column(type: "string", length: 500)]
    #[Groups(['tests:read', 'tests:write', 'users:read'])]
    private ?string $testName;

    #[ORM\Column(type: "string", length: 2000, nullable: true)]
    #[Groups(['tests:read', 'tests:write'])]
    private ?string $description;

    #[ORM\Column(type: "string", length: 2000, nullable: true)]
    #[Groups(['tests:read', 'tests:write'])]
    private ?string $rules;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    #[Groups(['tests:read', 'tests:write'])]
    private DateTimeImmutable $date;

    #[ORM\Column(type: "integer", nullable: true)]
    #[Assert\Regex(pattern: "/^\d{1,43200}$/",
        message: "Имя пользователя может содержать только латинские символы и цифры"
    )]
    #[Groups(['tests:read', 'tests:write'])]
    private ?int $timeLimit;

    #[ORM\Column(type: "integer", nullable: true)]
    #[Groups(['tests:read', 'tests:write'])]
    private ?int $done;

    #[ORM\Column(type: "integer", nullable: true)]
    #[Groups(['tests:read', 'tests:write'])]
    private ?int $passed;

    #[ORM\Column(type: "boolean", nullable: true)]
    #[Groups(['tests:read', 'tests:write'])]
    private bool $isSubmitted = false;

    #[ORM\OneToMany(mappedBy: "test", targetEntity: "App\Entity\Result")]
    #[Groups(['tests:write', 'tests:read'])]
    private $results;

    #[ORM\OneToMany(mappedBy: "test", targetEntity: "App\Entity\Question")]
    #[Groups(['tests:read', 'tests:write'])]
    private $questions;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(['tests:read', 'tests:write'])]
    private $link;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "tests")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: true)]
    private $user_id;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: "tests")]
    #[ORM\JoinColumn(name: "tag_id", referencedColumnName: "id", nullable: true)]
    #[Groups(['tests:read', 'tests:write'])]
    private $tags;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTestName(): string
    {
        return $this->testName;
    }

    public function setTestName(string $testName): self
    {
        $this->testName = $testName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRules(): ?string
    {
        return $this->rules;
    }

    public function setRules(?string $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTimeLimit(): ?int
    {
        return $this->timeLimit;
    }

    public function setTimeLimit(?int $timeLimit): self
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(?Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setTest($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getTest() === $this) {
                $result->setTest(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setTest($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getTest() === $this) {
                $question->setTest(null);
            }
        }

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

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
            $tag->addTest($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeTest($this);
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDone(): ?int
    {
        return $this->done;
    }

    /**
     * @param int|null $done
     */
    public function setDone(?int $done): void
    {
        $this->done = $done;
    }

    /**
     * @return int|null
     */
    public function getPassed(): ?int
    {
        return $this->passed;
    }

    /**
     * @param int|null $passed
     */
    public function setPassed(?int $passed): void
    {
        $this->passed = $passed;
    }

    /**
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return $this->isSubmitted;
    }

    /**
     * @param bool $isSubmitted
     */
    public function setIsSubmitted(bool $isSubmitted): void
    {
        $this->isSubmitted = $isSubmitted;
    }
}
