<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Company;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[Assert\Callback('validate')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'tag')]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $tagcreated;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy:'tags')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private $company;


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function getCreated()
    {
        return $this->tagcreated;
    }

    #[ORM\PrePersist]
    public function setCreated()
    {
        $this->tagcreated = new \DateTime();
    }
    
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }


}