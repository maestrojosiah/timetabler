<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SchoolDays
 *
 * @ORM\Table(name="school_days")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SchoolDaysRepository")
 */
class SchoolDays
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
     * @ORM\Column(name="day", type="string", length=255)
     */
    private $day;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="schoolDays")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Timetable", inversedBy="schoolDays")
     * @ORM\JoinColumn(name="table_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $timetable;
    
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
     * Set day
     *
     * @param string $day
     *
     * @return SchoolDays
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return SchoolDays
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
     * @return SchoolDays
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
}
