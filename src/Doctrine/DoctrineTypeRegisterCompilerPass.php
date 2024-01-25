<?php

declare(strict_types=1);

namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use League\ConstructFinder\ConstructFinder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final readonly class DoctrineTypeRegisterCompilerPass implements CompilerPassInterface
{
    private const string TYPE_DEFINITION_PARAMETER = 'doctrine.dbal.connection_factory.types';
    private const string TYPE_NAME_METHOD_NAME = 'getTypeName';

    public function __construct(
        private string $projectDir,
    ) {
    }

    public function process(ContainerBuilder $container): void
    {
        /** @var array<string, array{class: class-string}> $typeDefinitions */
        $typeDefinitions = $container->getParameter(self::TYPE_DEFINITION_PARAMETER);

        $pathToDoctrineTypeDirectory = sprintf('%s/%s', $this->projectDir, 'src/Doctrine');

        $types = $this->findTypesInDirectory($pathToDoctrineTypeDirectory);

        foreach ($types as $type) {
            $name = $type['name'];
            $class = $type['class'];

            if (array_key_exists($name, $typeDefinitions)) {
                continue;
            }

            $typeDefinitions[$name] = ['class' => $class];
        }

        $container->setParameter(self::TYPE_DEFINITION_PARAMETER, $typeDefinitions);
    }

    /** @return \Generator<int, array{class: class-string, name: string}> */
    private function findTypesInDirectory(string $pathToDoctrineTypeDirectory): iterable
    {
        $classNames = ConstructFinder::locatedIn($pathToDoctrineTypeDirectory)->findClassNames();

        foreach ($classNames as $className) {
            $reflection = new \ReflectionClass($className);
            if (!$reflection->isSubclassOf(Type::class)) {
                continue;
            }

            // Don't register parent types
            if ($reflection->isAbstract()) {
                continue;
            }

            // Only register types that have the relevant method
            if (!$reflection->hasMethod(self::TYPE_NAME_METHOD_NAME)) {
                continue;
            }

            $typeName = call_user_func([$className, self::TYPE_NAME_METHOD_NAME]);

            yield [
                'class' => $reflection->getName(),
                'name' => $typeName,
            ];
        }
    }
}
