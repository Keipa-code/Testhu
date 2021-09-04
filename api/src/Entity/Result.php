<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ResultRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 * @ApiResource(
 *     attributes={"security": "is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get": {"security": "is_granted('ROLE_USER') or is_granted('ROLE_ANON')", "security_message": "Sorry, but you are not the book owner."},
 *         "post": {"security": "is_granted('ROLE_ANON')"}
 *     },
 *     itemOperations={
 *         "get": {"security": "is_granted('ROLE_USER') or is_granted('ROLE_ANON')", "security_message": "Sorry, but you are not the book owner."},
 *         "put": {"security": "is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * Добавить метод для сравнения позиции вопросов. Не должно быть одинаковых.
 */

class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:User', 'read:item'])]
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[Groups(['write:Result', 'read:item'])]
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Test", inversedBy="results")
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id", nullable=true)
     */
    #[Groups(['read:item'])]
    private $test;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    #[Groups(['write:Result', 'read:item'])]
    private $correctAnswersCount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message="Эта строка должна содержать ссылку на интернет ресурс. Например: https://ya.ru")
     */
    #[Groups(['read:User', 'write:Result', 'read:item'])]
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="results")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    #[Groups(['read:item'])]
    private $user_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

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

    public function getCorrectAnswersCount(): ?int
    {
        return $this->correctAnswersCount;
    }

    public function setCorrectAnswersCount(int $correctAnswersCount): self
    {
        $this->correctAnswersCount = $correctAnswersCount;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
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
