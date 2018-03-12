<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Timetabler
 *
 * @ORM\Table(name="timetabler")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TimetablerRepository")
 */
class Timetabler
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
     * @ORM\Column(name="time", type="string", length=255)
     */

    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=20)
     */

    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="teacher", type="string", length=200)
     */

    private $teacher;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=100)
     */

    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="table_format_column", type="string", length=100)
     */

    private $tableFormatColumn;

    /**
     * @var string
     *
     * @ORM\Column(name="day", type="string", length=20)
     */

    private $day;

    /**
     * @ORM\ManyToOne(targetEntity="Timetable", inversedBy="timetablers")
     * @ORM\JoinColumn(name="table_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $timetable;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="timetablers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="ClassSubject", inversedBy="timetablers")
     * @ORM\JoinColumn(name="class_sub_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $classSubject;

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
     * @return Timetabler
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
     * Set timetable
     *
     * @param \AppBundle\Entity\Timetable $timetable
     *
     * @return Timetabler
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Timetabler
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
     * Set classSubject
     *
     * @param \AppBundle\Entity\ClassSubject $classSubject
     *
     * @return Timetabler
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
     * Set day
     *
     * @param string $day
     *
     * @return Timetabler
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set class
     *
     * @param string $class
     *
     * @return Timetabler
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set teacher
     *
     * @param string $teacher
     *
     * @return Timetabler
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return string
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Timetabler
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set tableFormatColumn
     *
     * @param string $tableFormatColumn
     *
     * @return Timetabler
     */
    public function setTableFormatColumn($tableFormatColumn)
    {
        $this->tableFormatColumn = $tableFormatColumn;

        return $this;
    }

    /**
     * Get tableFormatColumn
     *
     * @return string
     */
    public function getTableFormatColumn()
    {
        return $this->tableFormatColumn;
    }
}
