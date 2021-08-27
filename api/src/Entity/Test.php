<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TestRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TestRepository::class)
 * Добавить метод для сравнения позиции вопросов. Не должно быть одинаковых.
 *
 * @internal
 */
#[ApiResource(
    itemOperations: [
        'put' => [
            'denormalization_context' => ['groups' =>['put:Test']]
        ],
        'get' => [
            'normalization_context' => ['groups' => ['read:collection', 'read:item', 'read:Post']]
        ]
    ],
    normalizationContext: ['groups' => ['read:collection']]
)]
class Test
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:collection', 'read:item'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    #[Groups(['read:collection', 'read:item'])]
    private string $testName;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    #[Groups(['read:item'])]
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    #[Groups(['read:item'])]
    private ?string $rules;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[Groups(['read:collection', 'read:item'])]
    private ?DateTimeImmutable $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    #[Groups(['read:collection', 'read:item'])]
    private ?int $timeLimit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Result", mappedBy="test")
     */
    #[Groups(['read:item', 'put:Test'])]
    private $results;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="test")
     */
    #[Groups(['read:item', 'put:Test'])]
    private $questions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['read:item'])]
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tests")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user_id;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->questions = new ArrayCollection();
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
}
