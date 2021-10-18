<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: QuestionRepository::class)]
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
    denormalizationContext: ['groups' => ['questions:write']],
    normalizationContext: ['groups' => ['questions:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['test' => 'exact', 'position' => 'exact'])]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['questions:read', 'questions:write'])]
    private $id;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(['questions:read', 'questions:write'])]
    private $questionText;

    /**
     * Добавить константы по разным типам вопроса
     */
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(['questions:read', 'questions:write'])]
    private $questionType;

    #[ORM\Column(type: "json", nullable: true)]
    #[Groups(['questions:read', 'questions:write'])]
    private $variants = [];

    #[ORM\Column(type: "json", nullable: true)]
    #[Groups(['questions:read', 'questions:write'])]
    private $answer = [];

    #[ORM\Column(type: "integer", nullable: true)]
    #[Groups(['questions:read', 'questions:write'])]
    private $points;

    #[ORM\Column(type: "integer", nullable: true)]
    #[Groups(['questions:read', 'questions:write'])]
    private $position;

    #[ORM\ManyToOne(targetEntity: Test::class, cascade: ["persist"], inversedBy: "questions")]
    #[ORM\JoinColumn(name: "test", referencedColumnName: "id", nullable: true)]
    #[Groups(['questions:read', 'questions:write'])]
    private $test;

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
}
