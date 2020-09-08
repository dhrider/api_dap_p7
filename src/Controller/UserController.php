<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("api/users/create", name="user_create", methods={"POST"})
     * @param User $user
     * @return JsonResponse
     */
    public function create(User $user): JsonResponse
    {

    }
}
