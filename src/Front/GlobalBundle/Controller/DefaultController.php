<?php

namespace Front\GlobalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{

    public function RedirectAction()
    {
        return $this->redirect($this->generateUrl("front_global_homepage"));
    }

    public function indexAction()
    {
        return $this->render('FrontGlobalBundle:Accueil:accueil.html.twig');
    }

    public function contactAction()
    {
        $session=$this->getRequest()->getSession();
        $request=$this->getRequest();
        if($request->isMethod("POST"))
        {
            $body="Salut, vous avez réçus un message contact :<br>";
            $body.="<br> <strong> Nom & prénom </strong> :  ".$request->get("nom");
            $body.="<br> <strong> Email </strong> :  ".$request->get("Email");
            $body.="<br> <strong> Message </strong> :  <br>".$request->get("message");
            $body.="<br><br> Cordialement Equipe Wetla";
            $message=\Swift_Message::newInstance()
                    ->setSubject('Message Contact Wetla')
                    ->setFrom($request->get("Email"))
                    ->setTo('zied@netmarketing-tn.com')
                    ->setContentType("text/html")
                    ->setBody("ddddddd");
            $this->get('mailer')->send($message);
            $session->getFlashBag()->add('alert-success','your email has been sent successfully');
            return $this->redirect($this->generateUrl("contact"));
        }
        return $this->render('FrontGlobalBundle::contact.html.twig');
    }

}
