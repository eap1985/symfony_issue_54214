<?php
// src/Form/Type/TaskType.php
namespace App\Form\Type;

use App\Entity\Company;
use App\Form\Type\TagType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\Tag;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Doctrine\Persistence\ObjectManager;

class CompanyType extends AbstractType
{
    private $request;

    public function __construct(private ObjectManager $em, RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
        
    }

    public function buildForm(FormBuilderInterface $builder, $options): void
    {
        $builder
        ->add('name', TextType::class)
        ->add('created', DateType::class)
        ->add('inn', TextType::class)
        ->add('description', TextareaType::class)
        ->add('imageFile', VichImageType ::class)
        ->add('addPerson', ChoiceType ::class,  [
            'attr' => ['class'=>'addPerson'],
            'mapped'=>false,
            'choices'  => [
                'Select...' => null,
                'Yes' => true,
                'No' => false,
            ]])
        ->add('save', SubmitType::class, ['label' => 'Update task'])
        ;
        
        $builder->add('foo', LocationType::class, [
            'data_class' => Company::class,
        ]);

        //dd($this->request);
       /*
        if( !empty($this->request->get('ajax')) || !empty($this->request->get('company')['tags']) ) {        
            $builder->add('tags', CollectionType::class, [
                'entry_type' => TagType::class,
                'entry_options' =>  array('label'=>'Тэги'),
                'block_prefix' => 'wrapped_tags',
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
            ]);
        }
        */
        $formModifier = function (FormInterface $form, $tag = null): void {
                //$positions = null === $sport ? [] : $sport->getAvailablePositions();
                $addPerson = isset($tag) && empty($tag->getValues()) ? 2 : 1;
                if($this->request->get('company')) {
                    $addPerson = $this->request->get('company')['addPerson'];
                } 
                
                    if($addPerson != 2 || (!empty($tag->getValues()) && $addPerson == 1 ) ) {

                        
                        $form->add('tags', CollectionType::class, [
                            'entry_type' => TagType::class,
                            'entry_options' =>  array('label'=>'Тэги'),
                            'block_prefix' => 'wrapped_tags',
                            'allow_add'     => true,
                            'allow_delete'  => true,
                            'by_reference'  => false,
                        ]);
                    }
                
        };
    
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier): void {
                    // this would be your entity, i.e. SportMeetup
                    $data = $event->getData();
                    
                    $formModifier($event->getForm(), $data->getTags());
            }
        );

        $builder->get('addPerson')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier): void {
                
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $sport = $event->getForm()->getData();
                //dump($this->request->get('company')['addPerson']);
                //dd($event->getForm()->getParent());
                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback function!
                $formModifier($event->getForm()->getParent(), $sport);
            }
        );
        
        $builder->setAction($options['action']);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            'validation_groups' => ['main'],
            'allow_extra_fields' => true,
            'error_mapping' => [
                'matchingCityAndZipCode' => 'foo.city',
            ],
        ]);
    }
}