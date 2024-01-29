<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notifications;
use App\Repository\NotificationsRepository;

class NotificationsController extends AbstractController
{
    /**
     * @Route("/showNotifications/{showTo}", name="notifications")
     */
    public function list($showTo): Response
    {
        $rep=$this->getDoctrine()->getRepository(Notifications::class);
        $notifications=$rep->findBy(
            ['toShow' => $showTo]
        );
        return $this->json($notifications);
        
    }
}
