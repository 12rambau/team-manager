<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class SecurityController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function logout()
    {
        return $this->redirectToRoute('blog');
    }

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        //TODO variable check before register
        if ($request->isMethod('POST'))
        {
            $user = new User();
            $user->setUserName($request->request->get('username'));
            $user->setLastName('rien');
            $user->setFirstName('rien');
            $user->setEmail($request->request->get('email'));
            $user->setPassword($passwordEncoder->encodePassword($user,$request->request->get('password')));
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('blog');
        }
        return $this->render('security/register.html.twig');
    }

    public function forgottenPassword( Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($request->isMethod('POST'))
        {
            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);

            if ($user === null)
            {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('blog');
            }
            $token = $tokenGenerator->generateToken();
            
            try
            {
                $user->setResetToken($token);
                $entityManager->flush();
            } 
            catch (\Exception $e)
            {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('blog');
            }

            $url = $this->generateUrl('reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = new \Swift_Message('Forgot password');
            $message->setFrom('maGueul@prout.com')
                    ->setTo($user->getEmail())
                    ->setBody( "Voici le token : " . $url, 'text/html');
            
            $mailer->send($message);

            $this->addFlash('notice', 'message send');

            return $this->redirectToRoute('blog');
        }

        return $this->render('security/forgotten_password.html.twig',[]);
    }

    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST'))
        {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);

            if ($user === null)
            {
                $this->addFlash('danger', 'Invalid Token');
                return $this->redirectToRoute('blog');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

            $entityManager->flush();

            $this->addFlash('notice', 'Updated password');

            return $this->redirectToRoute('blog');
        }
        
        return $this->render('security/reset_password.html.twig', ['token'=>$token]);
    }
}
