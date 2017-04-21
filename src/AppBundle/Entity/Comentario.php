<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Comentario
 *
 * @ORM\Table(name="comentario")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ComentarioRepository")
 */
class Comentario
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
     * @ORM\Column(name="comentarios", type="string", length=255)
     */
    private $comentarios;

    /**
    * @var int
    *
    * @ORM\Column(name="likes", type="integer", nullable=true)
    */
    private $likes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Report", inversedBy="report")
     */
    private $report;

    /**
     * @ORM\ManyToOne(targetEntity="Trascastro\UserBundle\Entity\User", inversedBy="comentariosCreados")
     */
    private $creador;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = $this->createdAt;
    }


    /**
     * @return mixed
     */
    public function getCreador()
    {
        return $this->creador;
    }

    /**
     * @param mixed $creador
     */
    public function setCreador($creador)
    {
        $this->creador = $creador;
    }

    /**
     * Get report
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReport()
    {
        return $this->report;
    }
    /**
     * Set report
     *
     * @param \AppBundle\Entity\Report $report
     *
     * @return Comentario
     */
    public function setReport(\AppBundle\Entity\Report $report = null)
    {
        $this->report = $report;
        return $this;
    }

    /**
     * Add report
     *
     * @param \AppBundle\Entity\Report $report
     *
     * @return Comentario
     */
    public function addReport(\AppBundle\Entity\Report $report)
    {
        $this->report[] = $report;
        return $this;
    }
    /**
     * Remove report
     *
     * @param \AppBundle\Entity\Report $report
     */
    public function removeReport(\AppBundle\Entity\Report $report)
    {
        $this->report->removeElement($report);
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
     * Get likes
     *
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set likes
     *
     * @param int $likes
     *
     *  @return Comentario
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
        return $this;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return Comentario
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comentario
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Comentario
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


}

