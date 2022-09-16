<?php

namespace App\Entity;

use App\Repository\EmpDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=EmpDetailsRepository::class)
 */
class EmpDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Enter jobtitle.")
     * 
     */
    private $jobTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jobDes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userImg;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $linkdinLink;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jobExp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $companyName;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="empDetails", cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getJobDes(): ?string
    {
        return $this->jobDes;
    }

    public function setJobDes(string $jobDes): self
    {
        $this->jobDes = $jobDes;

        return $this;
    }

    public function getUserImg(): ?string
    {
        return $this->userImg;
    }

    public function setUserImg(string $userImg): self
    {
        $this->userImg = $userImg;

        return $this;
    }

    public function getLinkdinLink(): ?string
    {
        return $this->linkdinLink;
    }

    public function setLinkdinLink(string $linkdinLink): self
    {
        $this->linkdinLink = $linkdinLink;

        return $this;
    }

    public function getJobExp(): ?string
    {
        return $this->jobExp;
    }

    public function setJobExp(string $jobExp): self
    {
        $this->jobExp = $jobExp;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
