<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Tag;
use App\Tests\DatabaseDependantTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

/**
 * @internal
 */
final class TagTest extends DatabaseDependantTestCase
{
    public function testTagAddedInDB(): void
    {
        $tagRepository = $this->entityManager->getRepository(Tag::class);
        /** @var Tag $tagRecord */
        $tagRecord = $tagRepository->findOneBy(['tagName' => 'Математика']);

        self::assertEquals('Математика', $tagRecord->getTagName());
    }
}
