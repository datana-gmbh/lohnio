<?php

declare(strict_types=1);

namespace App\Controller\Page;

use App\Api\Zendesk\ZendeskApiInterface;
use App\Bridge\Zendesk\Domain\Value\Ticket;
use App\Domain\Enum\Flashmessage;
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
        private readonly ZendeskApiInterface $zendeskApi,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(ContactFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $honeypotValue = $form->get('faxnummer')->getData();

            if (null !== $honeypotValue && '' !== $honeypotValue) {
                return $this->responder->route(Routes::INDEX);
            }

            //            try {
            $this->zendeskApi->createTicket(Ticket::fromArray($form->getData()));

            $request->getSession()->getFlashbag()->add(
                Flashmessage::SUCCESS->value,
                'Vielen Dank! Wir haben Ihre Nachricht erhalten und werden uns in kürze bei Ihnen melden.',
            );
            //            } catch (\Throwable) {
            //                $request->getSession()->getFlashbag()->add(
            //                    Flashmessage::ERROR->value,
            //                    'Entschuldigen Sie bitte! Es ist ein Fehler aufgetreten. Bitte versuchen Sie es später erneut.',
            //                );
            //
            //                return $this->responder->route(Routes::CONTACT);
            //            }

            return $this->responder->route(Routes::INDEX);
        }

        return $this->responder->render('page/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
