<?php

namespace App\Controller;


use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'user_registration')]
    public function index(Request $request, UsersRepository $usersRepository): Response
    {
        $user = new Users();

        if ($request->isMethod("POST")) {

            try {
                $user->setName($request->request->get("userName"));
                $user->setSurname($request->request->get("userSurname"));
                $user->setScreenName($request->request->get("screenName"));
                $user->setPassword($request->request->get("password"));
                $user->setEmail($request->request->get("email"));
                $user->setAdress($request->request->get("adress"));
                $user->setCityCode($request->request->get("cityCode"));
                $user->setCity($request->request->get("city"));
                $user->setRole(3);
                $user->setCustomerPoints(0);

                $usersRepository->save($user, true);

                $this->addFlash('success', 'File uploaded successfully!');
                return $this->redirectToRoute('user_registration'); // ou autre route
            } catch (FileException $e) {
                $this->addFlash('error', 'Failed to upload file: ' . $e->getMessage());
                return $this->redirectToRoute('user_registration'); // ou autre route
            }

        }
        return $this->render('registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
        ]);
    }
}
