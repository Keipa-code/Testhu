<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ResultRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
#[ApiResource(
    collectionOperations: [
    'get',
    'post',
],
    itemOperations: [
    'get'
],
)]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['users:read'])]
    private $id;

    #[Orm\Column(type: "datetime_immutable", nullable: true)]
    private $date;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Test", inversedBy: "results")]
    #[ORM\JoinColumn(name: "test_id", referencedColumnName: "id", nullable: true)]
    private $test;

    #[ORM\Column(type: "integer", nullable: true)]
    private $correctAnswersCount;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Assert\Url(message: "Эта строка должна содержать ссылку на интернет ресурс. Например: https://ya.ru")]
    #[Groups(['users:read'])]
    private $link;

    #[ORM\Column(type: "boolean")]
    private $viewed = false;

    #[ORM\Column(type: "json", nullable: true)]
    private $testResults = [];

    #[ORM\ManyToOne(targetEntity: "App\Entity\User", inversedBy: "results")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: true)]
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

    /**
     * @return mixed
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * @param mixed $viewed
     */
    public function setViewed($viewed): void
    {
        $this->viewed = $viewed;
    }

    /**
     * @return array
     */
    public function getTestResults(): array
    {
        return $this->testResults;
    }

    /**
     * @param array $testResults
     */
    public function setTestResults(array $testResults): void
    {
        $this->testResults = $testResults;
    }
}
