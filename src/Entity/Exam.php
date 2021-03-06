<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExamRepository")
 */
class Exam
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $questionIds;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Teacher")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $examTitle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Result", mappedBy="exam")
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExamResult", mappedBy="exam")
     */
    private $examResults;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $availableStudents;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->examResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getQuestionIds(): ?string
    {
        return $this->questionIds;
    }

    public function setQuestionIds(string $questionIds): self
    {
        $this->questionIds = $questionIds;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getExamTitle(): ?string
    {
        return $this->examTitle;
    }

    public function setExamTitle(string $examTitle): self
    {
        $this->examTitle = $examTitle;

        return $this;
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
            $result->setExam($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getExam() === $this) {
                $result->setExam(null);
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
            $examResult->setExam($this);
        }

        return $this;
    }

    public function removeExamResult(ExamResult $examResult): self
    {
        if ($this->examResults->contains($examResult)) {
            $this->examResults->removeElement($examResult);
            // set the owning side to null (unless already changed)
            if ($examResult->getExam() === $this) {
                $examResult->setExam(null);
            }
        }

        return $this;
    }

    public function getAvailableStudents(): ?string
    {
        return $this->availableStudents;
    }

    public function setAvailableStudents(string $availableStudents): self
    {
        $this->availableStudents = $availableStudents;

        return $this;
    }
}
