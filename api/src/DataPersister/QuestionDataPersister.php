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
        if (($context['item_operation_name'] ?? null) !== 'put') {
            return $data;
        }

        $test = $data->getTest();

        if ($test->isSubmitted()) {
            throw new DomainException('Вопросы опубликованных тестов изменить нельзя');
        } elseif (!$test->isSubmitted()) {
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }

    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
    }
}