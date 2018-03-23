<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table(name="subject")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubjectRepository")
 */
class Subject
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
     * @var string
     *
     * @ORM\Column(name="s_title", type="string", length=255)
     */
    private $sTitle;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="subjects")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Timetable", inversedBy="subjects")
     * @ORM\JoinColumn(name="table_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $timetable;
    
    /**
     * @ORM\OneToMany(targetEntity="ClassSubject", mappedBy="subject")
     */
    private $classSubjects;
    
	/**
	 * @ORM\OneToMany(targetEntity="Timetabler", mappedBy="subject")
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
     * Set sTitle
     *
     * @param string $sTitle
     *
     * @return Subject
     */
    public function setSTitle($sTitle)
    {
        $this->sTitle = $sTitle;

        return $this;
    }

    /**
     * Get sTitle
     *
     * @return string
     */
    public function getSTitle()
    {
        return $this->sTitle;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->classSubjects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->sTitle;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Subject
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
     * Set timetable
     *
     * @param \AppBundle\Entity\Timetable $timetable
     *
     * @return Subject
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
     * Add classSubject
     *
     * @param \AppBundle\Entity\ClassSubject $classSubject
     *
     * @return Subject
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
     * Add timetabler
     *
     * @param \AppBundle\Entity\Timetabler $timetabler
     *
     * @return Subject
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
