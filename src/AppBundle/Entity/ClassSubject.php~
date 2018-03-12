<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClassSubject
 *
 * @ORM\Table(name="class_subject")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClassSubjectRepository")
 */
class ClassSubject
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Classs", inversedBy="classSubjects")
     * @ORM\JoinColumn(name="c_class", referencedColumnName="id", onDelete="CASCADE")
     */
    private $cClass;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="classSubjects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Teacher", inversedBy="classSubjects")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="classSubjects")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="Timetable", inversedBy="classSubjects")
     * @ORM\JoinColumn(name="table_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $timetable;

    /**
	 * @ORM\OneToMany(targetEntity="Timetabler", mappedBy="classSubject")
	 */
	private $timetablers;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cClass
     *
     * @param integer $cClass
     *
     * @return ClassSubject
     */
    public function setCClass($cClass)
    {
        $this->cClass = $cClass;

        return $this;
    }

    /**
     * Get cClass
     *
     * @return int
     */
    public function getCClass()
    {
        return $this->cClass;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->timetables = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return ClassSubject
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     *
     * @return ClassSubject
     */
    public function setTeacher(\AppBundle\Entity\Teacher $teacher = null)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return \AppBundle\Entity\Teacher
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set subject
     *
     * @param \AppBundle\Entity\Subject $subject
     *
     * @return ClassSubject
     */
    public function setSubject(\AppBundle\Entity\Subject $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return \AppBundle\Entity\Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set timetable
     *
     * @param \AppBundle\Entity\Timetable $timetable
     *
     * @return ClassSubject
     */
    public function setTimetable(\AppBundle\Entity\Timetable $timetable = null)
    {
        $this->timetable = $timetable;

        return $this;
    }

    /**
     * Get timetable
     *
     * @return \AppBundle\Entity\Timetable
     */
    public function getTimetable()
    {
        return $this->timetable;
    }

    /**
     * Add timetable
     *
     * @param \AppBundle\Entity\TimeTable $timetable
     *
     * @return ClassSubject
     */
    public function addTimetable(\AppBundle\Entity\TimeTable $timetable)
    {
        $this->timetables[] = $timetable;

        return $this;
    }

    /**
     * Remove timetable
     *
     * @param \AppBundle\Entity\TimeTable $timetable
     */
    public function removeTimetable(\AppBundle\Entity\TimeTable $timetable)
    {
        $this->timetables->removeElement($timetable);
    }

    /**
     * Get timetables
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTimetables()
    {
        return $this->timetables;
    }

    /**
     * Add timetabler
     *
     * @param \AppBundle\Entity\Timetabler $timetabler
     *
     * @return ClassSubject
     */
    public function addTimetabler(\AppBundle\Entity\Timetabler $timetabler)
    {
        $this->timetablers[] = $timetabler;

        return $this;
    }

    /**
     * Remove timetabler
     *
     * @param \AppBundle\Entity\Timetabler $timetabler
     */
    public function removeTimetabler(\AppBundle\Entity\Timetabler $timetabler)
    {
        $this->timetablers->removeElement($timetabler);
    }

    /**
     * Get timetablers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTimetablers()
    {
        return $this->timetablers;
    }
}
