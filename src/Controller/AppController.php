<?php
// src/Controller/AppController.php
namespace App\Controller;

use App\CommandBus;
use App\Reservation\BookingId;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AppController extends AbstractController
{

    
	#[Route('/lucky/number')]
    public function number(CommandBus $cb): Response
    {

        $cb->handle('handlers');
        
        $number = random_int(0, 100);

         return $this->render('number.html.twig', [
            'number' => $number,
        ]);
    }


}