<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConfigRepository")
 */
class Config
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
     * @ORM\Column(name="school_title", type="string", length=255)
     */
    private $schoolTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="school_address", type="string", length=255)
     */
    private $schoolAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="entries_per_page", type="string", length=255)
     */
    private $entriesPerPage;

    /**
     * @var string
     *
     * @ORM\Column(name="footer_message", type="string", length=255)
     */
    private $footerMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="sidebar", type="string", length=255)
     */
    private $sidebar;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="configs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;



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
     * Set schoolTitle
     *
     * @param string $schoolTitle
     *
     * @return Config
     */
    public function setSchoolTitle($schoolTitle)
    {
        $this->schoolTitle = $schoolTitle;

        return $this;
    }

    /**
     * Get schoolTitle
     *
     * @return string
     */
    public function getSchoolTitle()
    {
        return $this->schoolTitle;
    }

    /**
     * Set schoolAddress
     *
     * @param string $schoolAddress
     *
     * @return Config
     */
    public function setSchoolAddress($schoolAddress)
    {
        $this->schoolAddress = $schoolAddress;

        return $this;
    }

    /**
     * Get schoolAddress
     *
     * @return string
     */
    public function getSchoolAddress()
    {
        return $this->schoolAddress;
    }

    /**
     * Set entriesPerPage
     *
     * @param string $entriesPerPage
     *
     * @return Config
     */
    public function setEntriesPerPage($entriesPerPage)
    {
        $this->entriesPerPage = $entriesPerPage;

        return $this;
    }

    /**
     * Get entriesPerPage
     *
     * @return string
     */
    public function getEntriesPerPage()
    {
        return $this->entriesPerPage;
    }

    /**
     * Set footerMessage
     *
     * @param string $footerMessage
     *
     * @return Config
     */
    public function setFooterMessage($footerMessage)
    {
        $this->footerMessage = $footerMessage;

        return $this;
    }

    /**
     * Get footerMessage
     *
     * @return string
     */
    public function getFooterMessage()
    {
        return $this->footerMessage;
    }

    /**
     * Set sidebar
     *
     * @param string $sidebar
     *
     * @return Config
     */
    public function setSidebar($sidebar)
    {
        $this->sidebar = $sidebar;

        return $this;
    }

    /**
     * Get sidebar
     *
     * @return string
     */
    public function getSidebar()
    {
        return $this->sidebar;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Config
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
}
