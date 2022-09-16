<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message = "Enter Email Id.")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * 
     */
    private $roles = [];

    
    /**
     * @var string The hashed password
     * @ORM\Column(type="string") @Assert\Regex(pattern="/^(?=.*[a-z])(?=.*\d).{6,}$/i", message="New password is required to be minimum 6 chars in length and to include at least one letter and one number.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message = "Enter First name.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-z]*$/",
     *     message="Only alphabets are allowed."
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Enter Last name.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-z]*$/",
     *     message="Only alphabets are allowed."
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Select Date of Birth.")
     * 
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\NotBlank(message = "Enter Mobile Number.")
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      minMessage = "Your number must be at least {{ limit }} Digits long",
     *      maxMessage = "Your number cannot be longer than {{ limit }} Digits"
     * )
     * @Assert\Regex(
     *     pattern="/^[0-9]*$/",
     *     message="Enter valid mobile"
     * )
     */
    private $mobileNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Enter Address.")
     * 
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\NotBlank(message = "Enter Pincode.")
     *  @Assert\Length(
     *      min = 6,
     *      max = 6,
     *      minMessage = "Pincode must be at least {{ limit }} Digits long",
     *      maxMessage = "Pincode cannot be longer than {{ limit }} Digits"
     * )
     * @Assert\Regex(
     *     pattern="/^[0-9]*$/",
     *     message="Pincode is invalid(Only Integers)"
     * )
     */
    private $pincode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $agreeTerms;

    /**
     * @ORM\Column(type="smallint")
     */
    private $deleteData;

    /**
     * @ORM\OneToOne(targetEntity=EmpDetails::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $empDetails;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="users")
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="users")
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="users")
     */
    private $city;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
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
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getMobileNumber(): ?int
    {
        return $this->mobileNumber;
    }

    public function setMobileNumber(int $mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPincode(): ?int
    {
        return $this->pincode;
    }

    public function setPincode(int $pincode): self
    {
        $this->pincode = $pincode;

        return $this;
    }

    public function getAgreeTerms(): ?bool
    {
        return $this->agreeTerms;
    }

    public function setAgreeTerms(bool $agreeTerms): self
    {
        $this->agreeTerms = $agreeTerms;

        return $this;
    }

    public function getDeleteData(): ?int
    {
        return $this->deleteData;
    }

    public function setDeleteData(int $deleteData): self
    {
        $this->deleteData = $deleteData;

        return $this;
    }

    public function getEmpDetails(): ?EmpDetails
    {
        return $this->empDetails;
    }

    public function setEmpDetails(?EmpDetails $empDetails): self
    {
        // unset the owning side of the relation if necessary
        if ($empDetails === null && $this->empDetails !== null) {
            $this->empDetails->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($empDetails !== null && $empDetails->getUser() !== $this) {
            $empDetails->setUser($this);
        }

        $this->empDetails = $empDetails;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
