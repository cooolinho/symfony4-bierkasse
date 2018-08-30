<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    private $paymentAmount = 0;
    private $beerAmount = 0;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->getFullname();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrename(): ?string
    {
        return $this->prename;
    }

    public function setPrename(string $prename): self
    {
        $this->prename = $prename;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFullname()
    {
        return $this->prename . ' ' . $this->surname;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->paymentAmount - $this->beerAmount;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPaymentAmount(): int
    {
        return $this->paymentAmount;
    }

    public function addPayment(int $amount = 1): self
    {
        $this->paymentAmount += $amount;

        return $this;
    }

    public function removePayment(int $amount = 1): self
    {
        $this->paymentAmount -= $amount;

        return $this;
    }

    public function getBeerAmount(): int
    {
        return $this->beerAmount;
    }

    public function addBeer(int $amount = 1): self
    {
        $this->beerAmount += $amount;

        return $this;
    }

    public function removeBeer(int $amount = 1): self
    {
        $this->beerAmount -= $amount;

        return $this;
    }
}
