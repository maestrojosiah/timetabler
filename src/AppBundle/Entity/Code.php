<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Code
 *
 * @ORM\Table(name="code")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CodeRepository")
 */
class Code
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
     * @ORM\Column(name="random_code", type="string", length=255)
     */
    private $randomCode;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=7)
     */
    private $status;


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
     * Set randomCode
     *
     * @param string $randomCode
     *
     * @return Code
     */
    public function setRandomCode($randomCode)
    {
        $this->randomCode = $randomCode;

        return $this;
    }

    /**
     * Get randomCode
     *
     * @return string
     */
    public function getRandomCode()
    {
        return $this->randomCode;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Code
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
