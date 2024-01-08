<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Page;

use App\Domain\Enum\Anrede;
use App\Tests\Functional\FunctionalTestCase;

final class ContactControllerTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function aNotLoggedInUserCanVisitThePage(): void
    {
        $this->browser()
            ->visit('/kontakt')
            ->assertSuccessful()
            ->assertSee('Kontaktieren Sie uns');
    }

    /**
     * @test
     */
    public function aNotLoggedInUserCanSubmitFormWithoutTelefonnummer(): void
    {
        $faker = self::faker();

        $this->browser()
            ->visit('/kontakt')
            ->assertSuccessful()
            ->assertSee('Kontaktieren Sie uns')
            ->selectFieldOptionByEnum('contact_form[anrede]', Anrede::Herr)
            ->fillField('Vorname', $faker->firstName())
            ->fillField('Nachname', $faker->lastName())
            ->fillField('E-Mail', $faker->email())
            ->fillField('Nachricht', $faker->text())
            ->checkField('contact_form[agb]')
            ->click('Formular absenden')
            ->assertSuccessful()
            ->assertOn('/')
            ->assertSeeSuccessFlashmessage('Vielen Dank! Wir haben Ihre Nachricht erhalten und werden uns in kürze bei Ihnen melden.');
    }

    /**
     * @test
     */
    public function aNotLoggedInUserCanSubmitFormWithTelefonnummer(): void
    {
        $faker = self::faker();

        $this->browser()
            ->visit('/kontakt')
            ->assertSuccessful()
            ->assertSee('Kontaktieren Sie uns')
            ->selectFieldOptionByEnum('contact_form[anrede]', Anrede::Herr)
            ->fillField('Vorname', $faker->firstName())
            ->fillField('Nachname', $faker->lastName())
            ->fillField('E-Mail', $faker->email())
            ->fillField('Nachricht', $faker->text())
            ->fillField('Telefonnummer', $faker->phoneNumber())
            ->checkField('contact_form[agb]')
            ->click('Formular absenden')
            ->assertSuccessful()
            ->assertOn('/')
            ->assertSeeSuccessFlashmessage('Vielen Dank! Wir haben Ihre Nachricht erhalten und werden uns in kürze bei Ihnen melden.');
    }
}
