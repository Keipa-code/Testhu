<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

final class GetMeAction extends AbstractController
{
    /**
     * @Route(
     *     name="get_me",
     *     path="getme",
     *     methods={"GET"},
     *     defaults={
     *         "_api_resource_class": User::class,
     *         "_api_collection_operation_name": "get_me"
     *     }
     * )
     */
    public function __invoke(Request $request): UserInterface
    {
        return $this->getUser();
    }
}
