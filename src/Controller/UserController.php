<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    private $serializer;
    private $userRepository;
    private $entityManager;

    /**
     * UserController constructor.
     * @param SerializerInterface $serializer
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SerializerInterface $serializer, UserRepository  $userRepository, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->userRepository = $userRepository;
        $this->entityManager =$entityManager;
    }

    /**
     * @Route("api/users/create", name="user_create", methods={"POST"})
     * @param Request $request
     * @param EncoderFactoryInterface $encoder
     * @param ClientRepository $clientRepository
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function create(
        Request $request,
        EncoderFactoryInterface $encoder,
        ClientRepository $clientRepository,
        ValidatorInterface $validator): Response
    {
        $data = $request->getContent();
        $user = $this->serializer->deserialize($data, User::class, 'json');
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
            return new Response($this->serializer->serialize($violations, 'json'), 400, ['Content-Type' => 'application/json']);
        }

        $user->setRoles(['ROLE_USER']);
        $hash = $encoder
            ->getEncoder($user)
            ->encodePassword($donnees['password'], $user->getSalt())
        ;
        $user->setPassword($hash);
        $user->setClient($clientRepository->find(1));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse('L\'user a bien été crée !', 201);
    }

    /**
     * @Route("api/users", name="users_list", methods={"GET"})
     * @return JsonResponse
     */
    public function list() : JsonResponse
    {
        return $this->json($this->userRepository->findAll(), 200,[], ['groups' => ['user']]);
    }

    /**
     * @Route("api/users/{id}",name="users_show", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function show($id) : JsonResponse
    {
        return $this->json($this->userRepository->find($id), 200, [], ['groups' => ['user']]);
    }

    /**
     * @Route("api/users/{id}", name="users_update", methods={"PUT"})
     */
    public function update()
    {

    }

    /**
     * @Route("api/users/{id}", name="users_delete", methods={"DELETE"})
     * @param UserRepository $userRepository
     * @param $id
     * @return JsonResponse
     */
    public function delete(UserRepository $userRepository, $id) : JsonResponse
    {
        $this->entityManager->remove($userRepository->find($id));
        $this->entityManager->flush();

        return new JsonResponse('L\'user a bien été supprimé !', 201);
    }
}
