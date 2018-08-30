<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CashboxRepository")
 */
class Cashbox
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Many Cashboxes have Many Beers.
     * @ORM\ManyToMany(targetEntity="Beer", cascade={"persist"})
     * @ORM\JoinTable(name="cashbox_beers",
     *      joinColumns={@ORM\JoinColumn(name="cashbox_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="beer_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $beers;

    /**
     * Many Cashboxes have Many Payments.
     * @ORM\ManyToMany(targetEntity="Payment", cascade={"persist"})
     * @ORM\JoinTable(name="cashbox_payments",
     *      joinColumns={@ORM\JoinColumn(name="cashbox_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="payment_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $payments;

    /**
     * Many User have Many Phonenumbers.
     * @ORM\ManyToMany(targetEntity="App\Entity\Player")
     * @ORM\JoinTable(name="cashbox_players",
     *      joinColumns={@ORM\JoinColumn(name="cashbox_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="player_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $players;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->beers = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Beer[]
     */
    public function getBeers(): Collection
    {
        return $this->beers;
    }

    public function addBeer(Beer $beer): self
    {
        if (!$this->beers->contains($beer)) {
            $this->beers[] = $beer;
        }

        return $this;
    }

    public function removeBeer(Beer $beer): self
    {
        if ($this->beers->contains($beer)) {
            $this->beers->removeElement($beer);
        }

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    /**
     * @return int
     */
    public function getBalance(): int
    {
        $balance = $this->getBeers()->count() * -1;

        /* @var Payment $payment*/
        foreach ($this->getPayments() as $payment) {
            $balance += $payment->getAmount();
        }

        return $balance;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->contains($payment)) {
            $this->payments->removeElement($payment);
        }

        return $this;
    }
}
