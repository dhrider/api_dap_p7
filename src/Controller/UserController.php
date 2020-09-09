<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserController extends AbstractController
{
    /**
     * @Route("api/users/create", name="user_create", methods={"POST"})
     * @param Request $request
     * @param EncoderFactoryInterface $encoder
     * @param EntityManagerInterface $em
     * @param ClientRepository $clientRepository
     * @return JsonResponse
     */
    public function create(
        Request $request,
        EncoderFactoryInterface $encoder,
        EntityManagerInterface $em,
        ClientRepository $clientRepository): JsonResponse
    {
        $user = new User();

        $donnees = json_decode($request->getContent());

        $user->setFirstName($donnees->firstName);
        $user->setLastName($donnees->lastName);
        $user->setEmail($donnees->email);
        $user->setRoles(['ROLE_USER']);
        $hash = $encoder
            ->getEncoder($user)
            ->encodePassword($donnees->password, $user->getSalt())
        ;
        $user->setPassword($hash);
        $user->setClient($clientRepository->find(1));

        $em->persist($user);
        $em->flush();

        return new JsonResponse('L\'User a bien été crée !', 201);
    }

    /**
     * @Route("api/users", name="users_list", methods={"GET"})
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function list(UserRepository $userRepository)
    {
        return $this->json($userRepository->findAll(), 200,[], ['groups' => 'user:list']);
    }


}
