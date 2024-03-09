<?php
// src/Controller/TaskController.php
namespace App\Controller;

use App\Entity\Company;
use App\Entity\Tag;
use App\Form\Type\CompanyType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;


class TaskController extends AbstractController
{

    #[Route('/newcompany')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        // creates a task object and initializes some data for this example
        $company = new Company();
        $company->setName('Write a blog post');
        $company->setInn('5948022406');
        $company->setCreated(new \DateTimeImmutable('today'));
        

        $form = $this->createForm(CompanyType::class, $company);
        // ...
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $company = $form->getData();
            
            // ... perform some action, such as saving the task to the database
            $entityManager->persist($company);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
            return $this->redirectToRoute('task_success');
        }  else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Комментарий не добавлен из-за ошибок в форме! Заполните поля формы и отправьте форму повторно.');
        }


        return $this->render('company/new.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route('/task/edit/{id}/{_locale}', name:"taskedit", methods: ['GET', 'POST'], defaults: ['_locale'=>'ru'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager, UploaderHelper $helper, $id): Response
    {

   
        $path = $helper->asset($company, 'imageFile');
        $form = $this->createForm(CompanyType::class, $company, 
             ['action' => $this->generateUrl('taskedit', ['id'=>$id]) ]);
        // ...
        
        //if(!$request->get('ajax')) {
          //  dump($request->get('ajax'));
            $form->handleRequest($request);
        //}
        if ($form->isSubmitted() && $form->isValid()) {
            
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $company = $form->getData();
            
            // ... perform some action, such as saving the task to the database
            $entityManager->persist($company);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
           
            if($request->get('company')) {
                $addPerson = $request->get('company')['addPerson'];
                if($addPerson == 2) {
                    
                }
            } 

            return $this->redirectToRoute('taskedit',['id'=>$company->getId()]);
        } else if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', ' Форма не изменена из-за ошибок в форме! Заполните поля формы и отправьте форму повторно.');
        }


        return $this->render('company/edit.html.twig', [
            'form' => $form,
            'path' => $path
        ]);

    }


    #[Route('/task/success', name: "task_success")]
    public function success(): Response
    {

        
        
        $number = random_int(0, 100);

         return $this->render('number.html.twig', [
            'number' => $number,
        ]);
    }


    #[Route('/company/list/', name:"list", methods: ['GET'])]
    public function list(Request $request, EntityManagerInterface $entityManager, UploaderHelper $helper): Response
    {

        

        $companies = $entityManager->getRepository(Company::class)->findAll();

        // create a Response with an ETag and/or a Last-Modified header
        
        

        $response = $this->render('company/list.html.twig', [
            'companies' => $companies,
        ]);

        return $response;
        

        
    }

    #[Route('/share/{mypath}/{token}', name: 'share', requirements: ['token' => '.+'])]
    public function share($mypath = null, $token): Response
    {
        dump( $mypath, $token );

        $number = random_int(0, 100);

        return $this->render('number.html.twig', [
           'number' => $number,
       ]);
        
    }

    #[Route('/company/list/{id}', name:"show", methods: ['GET'])]
    public function show(Request $request,  Company $company, EntityManagerInterface $entityManager, UploaderHelper $helper, $id): Response
    {

        
        // create a Response with an ETag and/or a Last-Modified header
        $response = new Response();
       // $response->setEtag($company->getEtag());
        //$response->setLastModified($company->getUpdatedAt());
        // Set response as public. Otherwise it will be private by default.
        //$response->setPublic();

        // Check that the Response is not modified for the given Request
        if ($response->isNotModified($request)) {
            // return the 304 Response immediately
           // return $response;
        }
        
        //$companies = $entityManager->getRepository(Company::class)->findAll();

        // create a Response with an ETag and/or a Last-Modified header
        
        

        return  $this->render('company/show.html.twig', [
            'company' => $company,
        ], $response);
        
    
               

        
    }
}