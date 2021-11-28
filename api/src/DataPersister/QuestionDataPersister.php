<?php

declare(strict_types=1);


namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

class QuestionDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Question;
    }

    /**
     * @param Question $data
     */
    public function persist($data, array $context = [])
    {
        if (($context['item_operation_name'] ?? null) === 'put') {
            if ($data->getTest()->isSubmitted()) {
                throw new DomainException('Вопросы опубликованных тестов изменить нельзя');
            }
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
    }
}