<?php

declare(strict_types=1);

namespace App\Controller\Page;

use App\Routing\Routes;
use OskarStark\Symfony\Http\Responder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: Routes::INDEX)]
final class IndexController
{
    public function __construct(
        private Responder $responder,
    ) {
    }

    public function __invoke(): Response
    {
        return $this->responder->render('page/index.html.twig');
    }
}
