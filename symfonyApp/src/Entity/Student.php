<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StudentRepository")
 */
class Student
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Result", mappedBy="student")
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExamResult", mappedBy="student")
     */
    private $examResults;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="student", cascade={"persist", "remove"})
     */
    private $user;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->examResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newStudent = $user === null ? null : $this;
        if ($newStudent !== $user->getStudent()) {
            $user->setStudent($newStudent);
        }

        return $this;
    }
}
