<?php

declare(strict_types=1);

namespace App\Controller\Page;

use App\Form\ContactFormType;
use App\Routing\Routes;
use OskarStark\Symfony\Http\Responder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/kontakt', name: Routes::CONTACT)]
final class ContactController
{
    public function __construct(
        private readonly Responder $responder,
        private readonly FormFactoryInterface $formFactory,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(ContactFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
            // @TODO: Post Ticket to Zendesk API
        }

        return $this->responder->render('page/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
