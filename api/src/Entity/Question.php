<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 * @ApiResource(
 *     attributes={"security": "is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get": {"security": "is_granted('ROLE_USER') or is_granted('ROLE_ANON')", "security_message": "Sorry, but you are not the book owner."},
 *         "post": {"security": "is_granted('ROLE_ANON')"}
 *     },
 *     itemOperations={
 *         "get": {"security": "is_granted('ROLE_USER') or is_granted('ROLE_ANON')", "security_message": "Sorry, but you are not the book owner."},
 *         "put": {"security": "is_granted('ROLE_ADMIN') or object == user"}
 *     }
 * )
 * Добавить метод для сравнения позиции вопросов. Не должно быть одинаковых.
 */
#[ApiResource(
    itemOperations: [
    'put' => [
        'denormalization_context' => ['groups' =>['put:Question']]
    ],
    'get' => [
        'normalization_context' => ['groups' => ['read:collection', 'read:item']]
    ]
],
    denormalizationContext: ['groups' => ['write:Question']],
    forceEager: false,
    normalizationContext: ['groups' => ['read:collection']],
)]
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:collection', 'read:item', 'write:Question'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['read:collection', 'read:item', 'write:Question'])]
    private $questionText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * Добавить константы по разным типам вопроса
     */
    #[Groups(['read:collection', 'read:item', 'write:Question'])]
    private $questionType;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    #[Groups(['read:item', 'write:Question'])]
    private $variants = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    #[Groups(['read:item', 'write:Question'])]
    private $answer = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    #[Groups(['read:item', 'write:Question'])]
    private $points;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    #[Groups(['read:item', 'put:Question', 'write:Question'])]
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity=Test::class, inversedBy="questions")
     */
    #[Groups(['read:item', 'put:Question'])]
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
