<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ApiResource(
 *     attributes={"security": "is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get": {"security": "is_granted('ROLE_USER') or is_granted('ROLE_ANON')", "security_message": "Sorry, but you are not the book owner."},
 *         "post": {"security": "is_granted('ROLE_USER')"}
 *     },
 *     itemOperations={
 *         "get": {"security": "is_granted('ROLE_USER') or is_granted('ROLE_ANON')", "security_message": "Sorry, but you are not the book owner."}
 *     }
 * )
 */
#[ApiResource(
    itemOperations: [
    'get' => [
        'normalization_context' => ['groups' => ['read:collection', 'read:item']]
    ]
],
    denormalizationContext: ['groups' => ['write:Question']],
    normalizationContext: ['groups' => ['read:collection']]
)]
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:collection', 'read:item', 'read:Question'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Regex(pattern="/^[a-zа-яA-ZА-Я]{2,30}$/gu", message="Название может содержать только кириллицу, латиницу, знак '-' и не больше 30 символов")
     */
    #[Groups(['read:collection', 'read:item', 'read:Question'])]
    private $tagName;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="tags")
     */
    private $question;

    public function __construct()
    {
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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }
}
