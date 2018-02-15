<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TableFormat
 *
 * @ORM\Table(name="table_format")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TableFormatRepository")
 */
class TableFormat
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
     * @ORM\Column(name="activity", type="string", length=255)
     */
    private $activity;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=255)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tableFormats")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Timetable", inversedBy="tableFormats")
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
     * Set tOrder
     *
     * @param string $tOrder
     *
     * @return TableFormat
     */
    public function setTOrder($tOrder)
    {
        $this->tOrder = $tOrder;

        return $this;
    }

    /**
     * Get tOrder
     *
     * @return string
     */
    public function getTOrder()
    {
        return $this->tOrder;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return TableFormat
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
     * @return TableFormat
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
     * Set duration
     *
     * @param string $duration
     *
     * @return TableFormat
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return TableFormat
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
     * Set activity
     *
     * @param string $activity
     *
     * @return TableFormat
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return string
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
