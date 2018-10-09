<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 */
class Student implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Result", mappedBy="student")
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExamResult", mappedBy="student")
     */
    private $examResults;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->examResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setStudent($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getStudent() === $this) {
                $result->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExamResult[]
     */
    public function getExamResults(): Collection
    {
        return $this->examResults;
    }

    public function addExamResult(ExamResult $examResult): self
    {
        if (!$this->examResults->contains($examResult)) {
            $this->examResults[] = $examResult;
            $examResult->setStudent($this);
        }

        return $this;
    }

    public function removeExamResult(ExamResult $examResult): self
    {
        if ($this->examResults->contains($examResult)) {
            $this->examResults->removeElement($examResult);
            // set the owning side to null (unless already changed)
            if ($examResult->getStudent() === $this) {
                $examResult->setStudent(null);
            }
        }

        return $this;
    }
}
