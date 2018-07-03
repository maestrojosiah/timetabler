<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Timetable
 *
 * @ORM\Table(name="timetable")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TimetableRepository")
 */
class Timetable
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
     * @var datetime
     *
     * @ORM\Column(name="time", type="datetime", length=255)
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

	/**
	 * @ORM\OneToMany(targetEntity="ClassSubject", mappedBy="timetable")
	 */
	private $classSubjects;

	/**
	 * @ORM\OneToMany(targetEntity="SchoolDays", mappedBy="timetable")
	 */
	private $schoolDays;

	/**
	 * @ORM\OneToMany(targetEntity="Subject", mappedBy="timetable")
	 */
	private $subjects;

	/**
	 * @ORM\OneToMany(targetEntity="TableFormat", mappedBy="timetable")
	 */
	private $tableFormats;

	/**
	 * @ORM\OneToMany(targetEntity="Timetabler", mappedBy="timetable")
	 */
	private $timetablers;

	/**
	 * @ORM\OneToMany(targetEntity="Teacher", mappedBy="timetable")
	 */
	private $teachers;

    /**
     * @ORM\OneToMany(targetEntity="Classs", mappedBy="timetable")
     */
    private $classes;
    /**
     * @ORM\OneToMany(targetEntity="Download", mappedBy="timetable")
     */
    private $downloads;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="timetables")
     * @ORM\JoinColumn(name="table_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    public function __construct()
    {
   		$this->classSubjects = new ArrayCollection();
   		$this->schoolDays = new ArrayCollection();
   		$this->students = new ArrayCollection();
   		$this->subjects = new ArrayCollection();
   		$this->teachers = new ArrayCollection();
   		$this->tableFormats = new ArrayCollection();
        $this->timetablers = new ArrayCollection();
   		$this->classes = new ArrayCollection();
    }

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
     * Set time
     *
     * @param string $time
     *
     * @return Timetable
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set classSubject
     *
     * @param \AppBundle\Entity\ClassSubject $classSubject
     *
     * @return Timetable
     */
    public function setClassSubject(\AppBundle\Entity\ClassSubject $classSubject = null)
    {
        $this->classSubject = $classSubject;

        return $this;
    }

    /**
     * Get classSubject
     *
     * @return \AppBundle\Entity\ClassSubject
     */
    public function getClassSubject()
    {
        return $this->classSubject;
    }


    /**
     * Add classSubject
     *
     * @param \AppBundle\Entity\ClassSubject $classSubject
     *
     * @return Timetable
     */
    public function addClassSubject(\AppBundle\Entity\ClassSubject $classSubject)
    {
        $this->classSubjects[] = $classSubject;

        return $this;
    }

    /**
     * Remove classSubject
     *
     * @param \AppBundle\Entity\ClassSubject $classSubject
     */
    public function removeClassSubject(\AppBundle\Entity\ClassSubject $classSubject)
    {
        $this->classSubjects->removeElement($classSubject);
    }

    /**
     * Get classSubjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClassSubjects()
    {
        return $this->classSubjects;
    }

    /**
     * Add schoolDay
     *
     * @param \AppBundle\Entity\SchoolDays $schoolDay
     *
     * @return Timetable
     */
    public function addSchoolDay(\AppBundle\Entity\SchoolDays $schoolDay)
    {
        $this->schoolDays[] = $schoolDay;

        return $this;
    }

    /**
     * Remove schoolDay
     *
     * @param \AppBundle\Entity\SchoolDays $schoolDay
     */
    public function removeSchoolDay(\AppBundle\Entity\SchoolDays $schoolDay)
    {
        $this->schoolDays->removeElement($schoolDay);
    }

    /**
     * Get schoolDays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSchoolDays()
    {
        return $this->schoolDays;
    }

    /**
     * Add subject
     *
     * @param \AppBundle\Entity\Subject $subject
     *
     * @return Timetable
     */
    public function addSubject(\AppBundle\Entity\Subject $subject)
    {
        $this->subjects[] = $subject;

        return $this;
    }

    /**
     * Remove subject
     *
     * @param \AppBundle\Entity\Subject $subject
     */
    public function removeSubject(\AppBundle\Entity\Subject $subject)
    {
        $this->subjects->removeElement($subject);
    }

    /**
     * Get subjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Add tableFormat
     *
     * @param \AppBundle\Entity\TableFormat $tableFormat
     *
     * @return Timetable
     */
    public function addTableFormat(\AppBundle\Entity\TableFormat $tableFormat)
    {
        $this->tableFormats[] = $tableFormat;

        return $this;
    }

    /**
     * Remove tableFormat
     *
     * @param \AppBundle\Entity\TableFormat $tableFormat
     */
    public function removeTableFormat(\AppBundle\Entity\TableFormat $tableFormat)
    {
        $this->tableFormats->removeElement($tableFormat);
    }

    /**
     * Get tableFormats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTableFormats()
    {
        return $this->tableFormats;
    }

    /**
     * Add teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     *
     * @return Timetable
     */
    public function addTeacher(\AppBundle\Entity\Teacher $teacher)
    {
        $this->teachers[] = $teacher;

        return $this;
    }

    /**
     * Remove teacher
     *
     * @param \AppBundle\Entity\Teacher $teacher
     */
    public function removeTeacher(\AppBundle\Entity\Teacher $teacher)
    {
        $this->teachers->removeElement($teacher);
    }

    /**
     * Get teachers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeachers()
    {
        return $this->teachers;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Timetable
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add timetabler
     *
     * @param \AppBundle\Entity\Timetabler $timetabler
     *
     * @return Timetable
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

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Timetable
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
     * Add class
     *
     * @param \AppBundle\Entity\Classs $class
     *
     * @return Timetable
     */
    public function addClass(\AppBundle\Entity\Classs $class)
    {
        $this->classes[] = $class;

        return $this;
    }

    /**
     * Remove class
     *
     * @param \AppBundle\Entity\Classs $class
     */
    public function removeClass(\AppBundle\Entity\Classs $class)
    {
        $this->classes->removeElement($class);
    }

    /**
     * Get classes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Add download
     *
     * @param \AppBundle\Entity\Download $download
     *
     * @return Timetable
     */
    public function addDownload(\AppBundle\Entity\Download $download)
    {
        $this->downloads[] = $download;

        return $this;
    }

    /**
     * Remove download
     *
     * @param \AppBundle\Entity\Download $download
     */
    public function removeDownload(\AppBundle\Entity\Download $download)
    {
        $this->downloads->removeElement($download);
    }

    /**
     * Get downloads
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDownloads()
    {
        return $this->downloads;
    }
}
