<?php
// src/Entity/Company.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Translation\TranslatableMessage;

use Symfony\Component\Validator\Context\ExecutionContextInterface;


#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Company
{


    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    protected string $task;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'author.name.not_blank',groups: ['main'])]
    #[Assert\Length(min: 7, groups: ['main'])]
    
    public $name;
    #[Assert\Callback(groups: ['main'], payload: 'maincompany')]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        // somehow you have an array of "fake names"
        $fakeNames = ['Test', 'Alex Ermakov'];
        
        // check if the name is actually a fake name
        if (in_array($this->getName(), $fakeNames, true)) {
            $context->buildViolation(new TranslatableMessage('author.name.fake', [], 'validators'))
                ->atPath('name')
                ->addViolation()
            ;
        }
    }
    
    #[Assert\Callback(groups: ['main'], payload: 'maincompany')]
    public function matchingCityAndZipCode(ExecutionContextInterface $context, mixed $payload): void
    {
        // check if the name is actually a fake name
           if ($this->getZipcode() != 617067) {
               $context->buildViolation(new TranslatableMessage('author.name.zipcode', [], 'validators'))
                   ->atPath('matchingCityAndZipCode')
                   ->addViolation()
               ;
           }
    }
    
    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(groups: ['main'])]
   /* #[Assert\Length(min: 7,groups: ['main'])] */
    protected $inn;

    #[ORM\Column(nullable: true)]
    protected string $description;

    #[ORM\Column(nullable: true)]
    protected ?\DateTimeImmutable $created;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\OneToMany(targetEntity: Tag::class, mappedBy: 'company',orphanRemoval: true, cascade: ['persist', 'remove'])]
    private $tags;

    private $etag;


    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'company', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    //#[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[ORM\Column(type: 'datetime', nullable: false)]
    protected ?\DateTime $updatedAt;

    
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 11, nullable: true)]
    private int $zipcode;

    #[ORM\Column(length: 255, nullable: true)]
    private string $city;

    #[ORM\Column(length: 255, nullable: true)]
    private string $country;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag) : void
    {
        $tag->setCompany($this);
        $this->tags->add($tag);
         
    }

    public function removeTag(Tag $tag): Collection
    {
            
        if (true === $this->getTags()->contains($tag)) {
            $this->tags->removeElement($tag);
        }
        
        return $this->tags;
    }


    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName($imageName): void
    {
        $this->imageName = $imageName;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }



    public function getInn(): string|int|null
    {
        return $this->inn;
    }

    public function setInn($inn): void
    {
        $this->inn = $inn;
    }


    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
        
    }


    public function getTask(): string
    {
        return $this->task;
    }

    public function setTask(string $task): void
    {
        $this->task = $task;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): void
    {
        $this->created = $created;
    }


    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;

        
        
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAtValue()
    {
        $this->setUpdatedAt(new \DateTime('now'));    
        
        
    }
    
    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getEtag(): ?string
    {
        $this->etag = md5($this->id . $this->name);

        return $this->etag;
    }
}