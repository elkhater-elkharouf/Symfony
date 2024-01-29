<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Notifications;
use App\Repository\NotificationsRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use App\Form\UpdateType;
use App\Form\LoginType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;




class UserController extends AbstractController
{

    private $session;


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
     /**
     * @Route("/user/hotelier" , name="listhot")
     */
    public function listh(): Response
    {
        if($this->session->get('role'))
        {
            $rr = $this->session->get('role');
            if($rr == '1' || $rr == '3')
            {

                $rep=$this->getDoctrine()->getRepository(User::class);
                $users=$rep->findAll();
                return $this->render('back-office/user/listh.html.twig', [
                    'user' => $users,
                ]);

            }
            else return $this->redirectToRoute('loginc');
        }
        else return $this->redirectToRoute('loginc');
    
    }

        /**
     * @Route("/user/client" , name="listc")
     */
    public function listc(): Response
    {
        if($this->session->get('role'))
        {
            $rr = $this->session->get('role');
            if($rr == '1' || $rr == '3')
            {

                $rep=$this->getDoctrine()->getRepository(User::class);
                $users=$rep->findAll();
                return $this->render('back-office/user/listc.html.twig', [
                    'user' => $users,
                ]);

            }
            else return $this->redirectToRoute('loginc');
        }
        else return $this->redirectToRoute('loginc');
    
    }



/**
    * @Route("/user/delete/{id}", name="deleteu")
    */
    public function delete($id): Response
    {
        $rep = $this->getDoctrine()->getRepository(User::class);
        $em = $this->getDoctrine()->getManager();
        $user = $rep->find($id);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('listhot');
    
    }
     /**
    * @Route("/user/add", name="addu")
    */
   public function add(MailerInterface $mailer, Request $request): Response
   {
       $user=new User();
       $form=$this->createForm(UserType::class,$user);
       $form=$form->handleRequest($request);
       
       if ($form->isSubmitted())
       {
       $user=$form->getData();
       $user->setRole(1);
       $pass = $user->getMdp();
       $hash_pass = md5($pass);
       $user->setMdp($hash_pass);
       $em=$this->getDoctrine()->getManager();
       $em->persist($user);
        $em->flush();


        $email = (new Email())
                ->from(Address::create('GETAWAY <hayfa.boughoufa@esprit.tn>'))                
                ->to($user->getMail())
                ->subject('Your Credentials')
                ->html('
                <center><h1>Hello '.$user->getPrenom().',</h1></center>
                <p>These are your credentials</p>
                <ul>
                <li>Email: '.$user->getMail().'</li>
                <li>Password: '.$pass.'</li>
                </ul>
                
                ');
            $mailer->send($email);

        return $this->redirectToRoute('listhot');
        
       }
       return $this->render('back-office/user/add.html.twig', [
           'formA' => $form->createView(),
           
       ]);
   }
   /**
    * @Route("/user/update/{id}", name="updateu")
    */
    public function update(Request $request,$id): Response
    {
     $rep=$this->getDoctrine()->getRepository(User::class);
     
     $user=$rep->find($id);
     $form=$this->createForm(UserType::class,$user);
     $form=$form->handleRequest($request);
     if ($form->isSubmitted())
     {
    
     $em=$this->getDoctrine()->getManager();
     $em->flush();
      return $this->redirectToRoute('listh');
      
     }
 
        return $this->render('back-office/user/update.html.twig', [
            'formA' => $form->createView(),
        ]);
    }





    
    /**
     * @Route("/user/profil", name="profil")
     */
   /* public function profil(): Response
    {
        return $this->render('back-office/user/profil.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
*/






        /**
     * @Route("/user/auth", name="auth")
     */
  /*  public function auth(Request $request): Response
    {
        $user=new User();
        $form=$this->createForm(UserType::class,$user);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {
        $user=$form->getData();
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
         $em->flush();
         return $this->redirectToRoute('listu');
         
        }
        return $this->render('back-office/user/auth.html.twig', [
            'formA' => $form->createView(),
            
        ]);
    }*/


  public function reCaptcha($recaptcha){
        $secret = "6LeMgLEeAAAAAJzrfBKHahekewph0RaBREkujcsX";
        $ip = $_SERVER['REMOTE_ADDR'];
      
        $postvars = array("secret"=>$secret, "response"=>$recaptcha, "remoteip"=>$ip);
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        $data = curl_exec($ch);
        curl_close($ch);
      
        return json_decode($data, true);
      }

         /**
     * @Route("/user/login", name="login")
     */
    public function login(Request $request,UserRepository $repository)
    {
      
     
        if($this->session->get('role'))
        {
            $rr = $this->session->get('role');
            if($rr == '3')
            return $this->redirectToRoute('index');


        }


        $user = new user();
        $form=$this->createForm(LoginType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userCheck = $repository->findOneBy(['mail' => $user->getMail(), 'mdp' => $user->getMdp()]);
            $recaptcha = $_POST['g-recaptcha-response'];
            $res = $this->reCaptcha($recaptcha);


            if($userCheck && $res['success'])
            {
                if($userCheck->getRole())
                {

                    $this->session->set('id',$userCheck->getId());
                    $this->session->set('nom',$userCheck->getNom());
                    $this->session->set('prenom',$userCheck->getPrenom());
                    $this->session->set('mail',$userCheck->getMail());
                    $this->session->set('role',$userCheck->getRole());
                    $rr = $this->session->get('role');
                    if($rr == '3')
                    return $this->redirectToRoute('index');

                }
                else {
                    return $this->render('front-office/user/login.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
        }
    }
        return $this->render('front-office/user/login.html.twig', [
            'form' => $form->createView(),
        ]);

    }




        /**
     * @Route("/user/authc", name="authc")
     */
    public function authc(Request $request): Response
    {
        $user=new User();
        $notifications = new Notifications();
        $form=$this->createForm(UserType::class,$user);
        $form=$form->handleRequest($request);
        if ($form->isSubmitted())
        {
        $user=$form->getData();
        $user->setRole(2);
        $this->session->set('role', '2');
        $pass = $user->getMdp();
        $hash_pass = md5($pass);
        $user->setMdp($hash_pass);

        $name = $user->getPrenom().' '.$user->getNom();

        $notifications->setTitle($name.' has registred.');
        $notifications->setLink('/user/client');
        $date = date('d/m/Y - H:i');
        $notifications->setDate($date);
        $notifications->setToShow(1);

        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
         $em->flush();

         $em->persist($notifications);
         $em->flush();
         return $this->redirectToRoute('test');
         
        }
        return $this->render('front-office/user/auth.html.twig', [
            'formB' => $form->createView(),
            
        ]);
    }


















    /**
     * @Route("/user/loginc", name="loginc")
     */
    public function loginc(Request $request,UserRepository $repository)
    {
     
        if($this->session->get('role'))
        {
            $rr = $this->session->get('role');
            if($rr == '2')
            return $this->redirectToRoute('test');
            else if($rr == '1')
            return $this->redirectToRoute('hotel');


        }


        $user = new user();
        $form=$this->createForm(LoginType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pass = $user->getMdp();
            $hash_pass = md5($pass);
            $userCheck = $repository->findOneBy(['mail' => $user->getMail(), 'mdp' => $hash_pass, 'enable' => NULL]);
            $recaptcha = $_POST['g-recaptcha-response'];
            $res = $this->reCaptcha($recaptcha);

            if($userCheck && $res['success'])
            {
                if($userCheck->getRole())
                {

                    $this->session->set('id',$userCheck->getId());
                    $this->session->set('nom',$userCheck->getNom());
                    $this->session->set('prenom',$userCheck->getPrenom());
                    $this->session->set('mail',$userCheck->getMail());
                    $this->session->set('role',$userCheck->getRole());
                    $rr = $this->session->get('role');
                    if($rr == '2')
                    return $this->redirectToRoute('test');
                    else if($rr == '1')
                    return $this->redirectToRoute('listh');

                }
                else {
                    return $this->render('front-office/user/login.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
        }
    }
        return $this->render('front-office/user/login.html.twig', [
            'form' => $form->createView(),
        ]);

    }

        /**
     * @Route("/logout", name="logout")
     */
    
    public function logout(Request $request,UserRepository $repository)
{
   $rr = $this->session->get('role');
   $this->session->clear();

   if($rr == '1' || $rr == '2')
   return $this->redirectToRoute('test');
   else return $this->redirectToRoute('login');

}


     /**
     * @Route("/user/update" , name="updatePro")
     */
    public function updatePro(Request $request,UserRepository $repository)
    {
        $rr = $this->session->get('id');


        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($rr);
        $prenom = $user->getPrenom();
        $nom = $user->getNom();
        $date_naissance = $user->getDateNaissance();
        $telephone = $user->getTelephone();
        $mail = $user->getMail();

        $form=$this->createForm(UpdateType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $user=$form->getData();
            $rep->flush();
            return $this->redirectToRoute('updatePro');


    




        }
        else return $this->render('back-office/user/updatePro.html.twig', [
                'formA' => $form->createView(),
                ]);

                

    
    }





    
     /**
     * @Route("/enable/{id}", name="enable")
     */
    public function enable(MailerInterface $mailer, Request $request,$id): Response
    {
        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($id);

        $user->setEnable(NULL);
        $role = $user->getRole();
        $rep->flush();

        $email = (new Email())
        ->from(Address::create('GETAWAY <hayfa.boughoufa@esprit.tn>'))                
        ->to($user->getMail())
        ->subject('Your Account Has Been Restored')
        ->html('
        <center><h1>Hello '.$user->getPrenom().',</h1></center>
        <p>Your account has been activated</p>
  
        
        ');
    $mailer->send($email);

        if($role == 1)
        return $this->redirectToRoute('listhot');
        else if($role == 2)
        return $this->redirectToRoute('listc');


    }
 /**
     * @Route("/disable/{t}/{id}", name="disable")
     */
    public function disable(MailerInterface $mailer, Request $request,$id,$t): Response
    {
        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($id);
        if($t == 't')
        {
        $user->setEnable(1);
        $email = (new Email())
        ->from(Address::create('GETAWAY <hayfa.boughoufa@esprit.tn>'))                
        ->to($user->getMail())
        ->subject('Your Account Has Been Temporarly Disabled')
        ->html('
        <center><h1>Hello '.$user->getPrenom().',</h1></center>
        <p>Your account has been temporarly disabled by your administrator</p>
  
        
        ');
    $mailer->send($email);
        }

        else if($t == 'p')
        {
        $user->setEnable(2);
        $email = (new Email())
        ->from(Address::create('GETAWAY <hayfa.boughoufa@esprit.tn>'))                
        ->to($user->getMail())
        ->subject('Your Account Has Been Disabled')
        ->html('
        <center><h1>Hello '.$user->getPrenom().',</h1></center>
        <p>Your account has been disabled by your administrator</p>
  
        
        ');
    $mailer->send($email);
        }

        $role = $user->getRole();
        
        $rep->flush();
        if($role == 1)
        return $this->redirectToRoute('listhot');
        else if($role == 2)
        return $this->redirectToRoute('listc');


    }
 










}
