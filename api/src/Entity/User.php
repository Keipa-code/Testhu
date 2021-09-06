<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ApiResource(
 *     attributes={"security": "is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get": {"security": "is_granted('ROLE_USER') or is_granted('ROLE_ANON')", "security_message": "Sorry, but you are not the book owner."},
 *         "post": {"security": "is_granted('ROLE_ANON')"}
 *     },
 *     itemOperations={
 *         "get": {
 *             "security": "is_granted('ROLE_USER') or is_granted('ROLE_ANON')",
 *             "security_message": "Sorry, but you are not the book owner.",
 *             "normalization_context": {"groups"={"read:item", "read:User"}
 *         },
 *         "put": {
 *             "security": "is_granted('ROLE_ADMIN') or object == user",
 *             "denormalization_context": {"groups"={"put:User"}
 *         }
 *     },
 *     denormalizationContext={"groups"={"write:User"}},
 *     forceEager=false
 * )
 * @ApiFilter(SearchFilter::class, properties={"username": "exact", "email": "exact"})
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(min=2, minMessage="Имя пользователя не должно быть короче 2 символов")
     * @Assert\Regex(pattern="/^[a-zA-Z0-9]{2,30}$/u",
     * message="Имя пользователя может содержать только латинские символы и цифры")
     * @Groups({"read:item", "write:User"})
     */
    private $username;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @Assert\Regex(
     *     pattern="/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",
     *     message="Пароль не должен быть короче 8 символов и должен содержать хотя бы 1 большую и 1 маленькую букву алфавита, а также хотя бы 1 цифру"
     * )
     * @Groups({"put:User", "write:User"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"read:item", "write:User"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=50, nullable=true, unique=true)
     * @Assert\Email(mode="loose", message="Веденные вами данные не являются email адресом")
     * @Groups({"read:item", "write:User"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passwordResetToken;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Email(mode="loose", message="Веденные вами данные не являются email адресом")
     * @Groups({"put:User"})
     */
    private $newEmail;

    /**
     * @ORM\OneToMany(targetEntity=Network::class, mappedBy="user_id")
     */
    private $network;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="user_id")
     * @Groups({"put:User", "read:item", "write:User"})
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity=Test::class, mappedBy="user_id")
     * @Groups({"put:User", "read:item"})
     */
    private $tests;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    public function __construct()
    {
        $this->network = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->tests = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(?string $roles): self
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string|null
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken(?string $passwordResetToken): self
    {
        $this->passwordResetToken = $passwordResetToken;

        return $this;
    }

    public function getNewEmail(): ?string
    {
        return $this->newEmail;
    }

    public function setNewEmail(?string $newEmail): self
    {
        $this->newEmail = $newEmail;

        return $this;
    }

    /**
     * @return Collection|Network[]
     */
    public function getNetwork(): Collection
    {
        return $this->network;
    }

    public function addNetwork(?Network $network): self
    {
        if (!$this->network->contains($network)) {
            $this->network[] = $network;
            $network->setUserId($this);
        }

        return $this;
    }

    public function removeNetwork(Network $network): self
    {
        if ($this->network->removeElement($network)) {
            // set the owning side to null (unless already changed)
            if ($network->getUserId() === $this) {
                $network->setUserId(null);
            }
        }

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
            $result->setUserId($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getUserId() === $this) {
                $result->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Test[]
     */
    public function getTests(): Collection
    {
        return $this->tests;
    }

    public function addTest(?Test $test): self
    {
        if (!$this->tests->contains($test)) {
            $this->tests[] = $test;
            $test->setUserId($this);
        }

        return $this;
    }

    public function removeTest(Test $test): self
    {
        if ($this->tests->removeElement($test)) {
            // set the owning side to null (unless already changed)
            if ($test->getUserId() === $this) {
                $test->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
