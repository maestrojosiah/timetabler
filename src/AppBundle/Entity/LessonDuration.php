<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LessonDuration
 *
 * @ORM\Table(name="lesson_duration")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LessonDurationRepository")
 */
class LessonDuration
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
     * @ORM\Column(name="lesson", type="string", length=255)
     */
    private $lesson;

    /**
     * @var string
     *
     * @ORM\Column(name="short_break", type="string", length=255)
     */
    private $shortBreak;

    /**
     * @var string
     *
     * @ORM\Column(name="long_break", type="string", length=255)
     */
    private $longBreak;

    /**
     * @var string
     *
     * @ORM\Column(name="lunch", type="string", length=255)
     */
    private $lunch;

    /**
     * @var string
     *
     * @ORM\Column(name="games", type="string", length=255)
     */
    private $games;

    /**
     * @var string
     *
     * @ORM\Column(name="activity", type="string", length=255)
     */
    private $activity;

    /**
     * @var string
     *
     * @ORM\Column(name="start_time", type="string", length=255)
     */
    private $startTime;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="lessonDurations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Timetable", inversedBy="lessonDurations")
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
     * Set lesson
     *
     * @param string $lesson
     *
     * @return LessonDuration
     */
    public function setLesson($lesson)
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get lesson
     *
     * @return string
     */
    public function getLesson()
    {
        return $this->lesson;
    }

    /**
     * Set shortBreak
     *
     * @param string $shortBreak
     *
     * @return LessonDuration
     */
    public function setShortBreak($shortBreak)
    {
        $this->shortBreak = $shortBreak;

        return $this;
    }

    /**
     * Get shortBreak
     *
     * @return string
     */
    public function getShortBreak()
    {
        return $this->shortBreak;
    }

    /**
     * Set longBreak
     *
     * @param string $longBreak
     *
     * @return LessonDuration
     */
    public function setLongBreak($longBreak)
    {
        $this->longBreak = $longBreak;

        return $this;
    }

    /**
     * Get longBreak
     *
     * @return string
     */
    public function getLongBreak()
    {
        return $this->longBreak;
    }

    /**
     * Set lunch
     *
     * @param string $lunch
     *
     * @return LessonDuration
     */
    public function setLunch($lunch)
    {
        $this->lunch = $lunch;

        return $this;
    }

    /**
     * Get lunch
     *
     * @return string
     */
    public function getLunch()
    {
        return $this->lunch;
    }

    /**
     * Set games
     *
     * @param string $games
     *
     * @return LessonDuration
     */
    public function setGames($games)
    {
        $this->games = $games;

        return $this;
    }

    /**
     * Get games
     *
     * @return string
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Set activity
     *
     * @param string $activity
     *
     * @return LessonDuration
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

    /**
     * Set startTime
     *
     * @param string $startTime
     *
     * @return LessonDuration
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return LessonDuration
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
     * @return LessonDuration
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
