<?php

namespace App;

use App\Doctrine\DoctrineTypeRegisterCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        /** @var string $projectDir */
        $projectDir = $container->getParameter('kernel.project_dir');

        $container->addCompilerPass(
            new DoctrineTypeRegisterCompilerPass($projectDir),
        );
    }
}
