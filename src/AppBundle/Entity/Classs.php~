<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class
 *
 * @ORM\Table(name="class")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClasssRepository")
 */
class Classs
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
     * @ORM\Column(name="c_title", type="string", length=100)
     */
    private $cTitle;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="classes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Timetable", inversedBy="classes")
     * @ORM\JoinColumn(name="table_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $timetable;

    /**
     * @ORM\OneToMany(targetEntity="ClassSubject", mappedBy="cClass")
     */
    private $classSubjects;

    public function __construct()
    {
        $this->classSubjects = new ArrayCollection();
    }

    public function __toString() {
        return $this->cTitle;
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
     * Set cTitle
     *
     * @param string $cTitle
     *
     * @return Class
     */
    public function setCTitle($cTitle)
    {
        $this->cTitle = $cTitle;

        return $this;
    }

    /**
     * Get cTitle
     *
     * @return string
     */
    public function getCTitle()
    {
        return $this->cTitle;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Classs
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
     * @return Classs
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
     * @return Classs
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
}
