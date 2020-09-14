<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("api/users/create", name="user_create", methods={"POST"})
     * @param Request $request
     * @param EncoderFactoryInterface $encoder
     * @param EntityManagerInterface $em
     * @param ClientRepository $clientRepository
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function create(
        Request $request,
        EncoderFactoryInterface $encoder,
        EntityManagerInterface $em,
        ClientRepository $clientRepository,
        ValidatorInterface $validator,
        SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $user = $serializer->deserialize($data, User::class, 'json');
        $violations = $validator->validate($user);

        $donnees = json_decode($request->getContent(), true);
        $client = $clientRepository->find($donnees['client_id']);

        if($client === null /*|| $this->getUser()->getClient()->getId() !== $client->getId()*/) {
            $violations->add(
                new ConstraintViolation(
                    'Client does not exist !',
                    null,[],
                    'user',
                    'client_id',
                    $donnees['client_id']
                )
            );
        }

        if(count($violations) > 0) {
            return new Response($serializer->serialize($violations, 'json'), 400, ['Content-Type' => 'application/json']);
        }

        $user->setRoles(['ROLE_USER']);
        $hash = $encoder
            ->getEncoder($user)
            ->encodePassword($donnees['password'], $user->getSalt())
        ;
        $user->setPassword($hash);
        $user->setClient($clientRepository->find(1));

        $em->persist($user);
        $em->flush();

        return new JsonResponse('L\'user a bien été crée !', 201);
    }

    /**
     * @Route("api/users", name="users_list", methods={"GET"})
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function list(UserRepository $userRepository) : JsonResponse
    {
        return $this->json($userRepository->findAll(), 200,[], ['groups' => ['user']]);
    }

    /**
     * @Route("api/users/{id}",name="users_show", methods={"GET"})
     * @param UserRepository $userRepository
     * @param $id
     * @return JsonResponse
     */
    public function show(UserRepository $userRepository, $id) : JsonResponse
    {
        return $this->json($userRepository->find($id), 200, [], ['groups' => ['user']]);
    }
}
