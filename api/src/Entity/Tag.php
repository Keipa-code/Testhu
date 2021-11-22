<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post',
    ],
    itemOperations: [
        'get'
    ],
    denormalizationContext: ['groups' => ['tags:write']],
    normalizationContext: ['groups' => ['tags:read']]
)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['tags:write', 'tags:read', 'tests:read'])]
    private $id;

    #[ORM\Column(type: "string", length: 30)]
    #[Assert\Regex(pattern: "/^[a-zа-яA-ZА-Я]{2,30}$/u",
        message: "Название может содержать только кириллицу, латиницу, знак '-' и не больше 30 символов")]
    #[Groups(['tags:write', 'tags:read', 'tests:read'])]
    private $tagName;

    #[ORM\ManyToMany(targetEntity: Test::class, inversedBy: "tags")]
    private $tests;

    public function __construct()
    {
        $this->tests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagName(): ?string
    {
        return $this->tagName;
    }

    public function setTagName(string $tagName): self
    {
        $this->tagName = $tagName;

        return $this;
    }

    /**
     * @return Collection|Test[]
     */
    public function getTests(): Collection
    {
        return $this->tests;
    }

    public function addTest(Test $test): self
    {
        if (!$this->tests->contains($test)) {
            $this->tests[] = $test;
            $test->addTag($this);
        }

        return $this;
    }

    public function removeTest(Test $test): self
    {
        if ($this->tests->removeElement($test)) {
            $test->removeTag($this);
        }

        return $this;
    }
}
