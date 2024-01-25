<?php

declare(strict_types=1);

namespace App\Domain\Products\WriteSide\CreateProduct;

use App\Domain\Products\WriteSide\CreateProduct\Exception\ProductWithSameNameExists;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Emails\Emails;
use App\Service\Emails\EmailTemplate;
use App\ValueObject\EmailAddress;
use App\ValueObject\ProductId;
use DigitalCraftsman\CQRS\Command\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Emails $emails,
        private EmailTemplate $emailTemplate,
        private ProductRepository $productRepository,
    ) {
    }

    public function __invoke(CreateProductCommand $command): void
    {
        // -- Validate
        $this->guardAgainstProductWithSameName($command->productName);

        // -- Apply
        $this->createProduct(
            $command->productName,
            $command->executedAt,
        );

        $this->informAdminByEmail($command->productName);
    }

    private function guardAgainstProductWithSameName(string $productName): void
    {
        $projectWithSameName = $this->productRepository->findOneByName($productName);

        if ($projectWithSameName !== null) {
            throw new ProductWithSameNameExists($productName);
        }
    }

    private function createProduct(
        string $name,
        \DateTimeImmutable $commandExecutedAt,
    ): void {
        $product = new Product(
            ProductId::generateRandom(),
            $name,
            $commandExecutedAt,
        );

        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    private function informAdminByEmail(string $productName): void
    {
        $this->emails->sendEmailFromDefault(
            new EmailAddress('admin@newdigitals-challenge.dev'),
            'Admin',
            'New product created',
            $this->emailTemplate->renderBody('email/product_created.html.twig', [
                'productName' => $productName,
            ]),
        );
    }
}
