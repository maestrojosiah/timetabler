<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="f_name", type="string", length=255)
     */
    private $fName;

    /**
     * @var string
     *
     * @ORM\Column(name="l_name", type="string", length=255)
     */
    private $lName;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string", length=255)
     */
    private $active;
    
	/**
	 * @ORM\OneToMany(targetEntity="ClassSubject", mappedBy="user")
	 */
	private $classSubjects;

	/**
	 * @ORM\OneToMany(targetEntity="SchoolDays", mappedBy="user")
	 */
	private $schoolDays;

	/**
	 * @ORM\OneToMany(targetEntity="Subject", mappedBy="user")
	 */
	private $subjects;

	/**
	 * @ORM\OneToMany(targetEntity="TableFormat", mappedBy="user")
	 */
	private $tableFormats;
	
	/**
	 * @ORM\OneToMany(targetEntity="Timetabler", mappedBy="user")
	 */
	private $timetablers;
		
    /**
     * @ORM\OneToMany(targetEntity="Teacher", mappedBy="user")
     */
    private $teachers;
    
	/**
	 * @ORM\OneToMany(targetEntity="Classs", mappedBy="user")
	 */
	private $classes;
	
    /**
     * @ORM\OneToMany(targetEntity="Timetable", mappedBy="user")
     */
    private $timetables;    
    
	/**
	 * @ORM\OneToMany(targetEntity="Config", mappedBy="user")
	 */
	private $configs;	
	
    public function __construct()
    {
        $this->active = true;
   		$this->classSubjects = new ArrayCollection();
   		$this->schoolDays = new ArrayCollection();
   		$this->students = new ArrayCollection();
   		$this->subjects = new ArrayCollection();
   		$this->teachers = new ArrayCollection();
   		$this->tableFormats = new ArrayCollection();   	
        $this->timetablers = new ArrayCollection();         
   		$this->classes = new ArrayCollection();   		
    }
    
    public function __toString() {
        return $this->fName;
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
     * Set fName
     *
     * @param string $fName
     *
     * @return User
     */
    public function setFName($fName)
    {
        $this->fName = $fName;

        return $this;
    }

    /**
     * Get fName
     *
     * @return string
     */
    public function getFName()
    {
        return $this->fName;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->active
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->active
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    /**
     * Set lName
     *
     * @param string $lName
     *
     * @return User
     */
    public function setLName($lName)
    {
        $this->lName = $lName;

        return $this;
    }
    
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Get lName
     *
     * @return string
     */
    public function getLName()
    {
        return $this->lName;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set active
     *
     * @param string $active
     *
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->active;
    }    

    /**
     * Add classSubject
     *
     * @param \AppBundle\Entity\ClassSubject $classSubject
     *
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * @return User
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
     * Add timetabler
     *
     * @param \AppBundle\Entity\Timetabler $timetabler
     *
     * @return User
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
     * Add timetable
     *
     * @param \AppBundle\Entity\Timetable $timetable
     *
     * @return User
     */
    public function addTimetable(\AppBundle\Entity\Timetable $timetable)
    {
        $this->timetables[] = $timetable;

        return $this;
    }

    /**
     * Remove timetable
     *
     * @param \AppBundle\Entity\Timetable $timetable
     */
    public function removeTimetable(\AppBundle\Entity\Timetable $timetable)
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
     * Add class
     *
     * @param \AppBundle\Entity\Classs $class
     *
     * @return User
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
     * Add config
     *
     * @param \AppBundle\Entity\Config $config
     *
     * @return User
     */
    public function addConfig(\AppBundle\Entity\Config $config)
    {
        $this->configs[] = $config;

        return $this;
    }

    /**
     * Remove config
     *
     * @param \AppBundle\Entity\Config $config
     */
    public function removeConfig(\AppBundle\Entity\Config $config)
    {
        $this->configs->removeElement($config);
    }

    /**
     * Get configs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConfigs()
    {
        return $this->configs;
    }
}
