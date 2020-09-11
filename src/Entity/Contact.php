<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Contact")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $firstName;

    /**
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $lastName;

    /**
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "validators.length.max"
     * )
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="text", length=65535)
     * @Assert\Length(
     *      max = 65535,
     *      maxMessage = "validators.length.max"
     * )
     * 
     * @var string
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=ContactTemplate::class, inversedBy="contact")
     */
    private $contactTemplate;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    public function getContactTemplate(): ?ContactTemplate
    {
        return $this->contactTemplate;
    }

    public function setContactTemplate(?ContactTemplate $contactTemplate): self
    {
        $this->contactTemplate = $contactTemplate;

        return $this;
    }
}
