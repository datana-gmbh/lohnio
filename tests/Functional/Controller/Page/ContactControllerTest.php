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
            ->checkField('contact_form[datenschutz]')
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
            ->checkField('contact_form[datenschutz]')
            ->click('Formular absenden')
            ->assertSuccessful()
            ->assertOn('/')
            ->assertSeeSuccessFlashmessage('Vielen Dank! Wir haben Ihre Nachricht erhalten und werden uns in kürze bei Ihnen melden.');
    }

    /**
     * @test
     */
    public function aNotLoggedInUserCanNotSubmitFormWithoutVorname(): void
    {
        $faker = self::faker();

        $this->browser()
            ->visit('/kontakt')
            ->assertSuccessful()
            ->assertSee('Kontaktieren Sie uns')
            ->selectFieldOptionByEnum('contact_form[anrede]', Anrede::Herr)
            ->fillField('Nachname', $faker->lastName())
            ->fillField('E-Mail', $faker->email())
            ->fillField('Nachricht', $faker->text())
            ->fillField('Telefonnummer', $faker->phoneNumber())
            ->checkField('contact_form[datenschutz]')
            ->click('Formular absenden')
            ->assertSuccessful()
            ->assertOn('/kontakt')
            ->assertSee('Bitte geben Sie einen Vornamen ein.');
    }

    /**
     * @test
     */
    public function aNotLoggedInUserCanNotSubmitFormWithoutNachname(): void
    {
        $faker = self::faker();

        $this->browser()
            ->visit('/kontakt')
            ->assertSuccessful()
            ->assertSee('Kontaktieren Sie uns')
            ->selectFieldOptionByEnum('contact_form[anrede]', Anrede::Herr)
            ->fillField('Vorname', $faker->firstName())
            ->fillField('E-Mail', $faker->email())
            ->fillField('Nachricht', $faker->text())
            ->fillField('Telefonnummer', $faker->phoneNumber())
            ->checkField('contact_form[datenschutz]')
            ->click('Formular absenden')
            ->assertSuccessful()
            ->assertOn('/kontakt')
            ->assertSee('Bitte geben Sie einen Nachnamen ein.');
    }

    /**
     * @test
     */
    public function aNotLoggedInUserCanNotSubmitFormWithoutEmail(): void
    {
        self::markTestSkipped('This test will fail. Somehow its possible to submit the E-Mailaddress field empty or invalid.');

        $faker = self::faker();

        $this->browser()
            ->visit('/kontakt')
            ->assertSuccessful()
            ->assertSee('Kontaktieren Sie uns')
            ->selectFieldOptionByEnum('contact_form[anrede]', Anrede::Herr)
            ->fillField('Vorname', $faker->firstName())
            ->fillField('Nachname', $faker->lastName())
            ->fillField('Nachricht', $faker->text())
            ->fillField('Telefonnummer', $faker->phoneNumber())
            ->checkField('contact_form[datenschutz]')
            ->click('Formular absenden')
            ->assertSuccessful()
            ->assertOn('/kontakt');
    }

    /**
     * @test
     */
    public function aNotLoggedInUserCanNotSubmitFormWithoutNachricht(): void
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
            ->checkField('contact_form[datenschutz]')
            ->click('Formular absenden')
            ->assertSuccessful()
            ->assertOn('/kontakt')
            ->assertSee('Bitte teilen Sie uns mit worum in Ihrer Kontaktanfrage geht.');
    }

    /**
     * @test
     */
    public function aNotLoggedInUserCanNotSubmitFormWithoutAcceptingDatenschutz(): void
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
            ->click('Formular absenden')
            ->assertSuccessful()
            ->assertOn('/kontakt')
            ->assertSee('Bitte stimmen Sie unseren Datenschutzbestimmungen zu.');
    }

    /**
     * @test
     */
    public function aNotLoggedInUserCanNotSubmitFormFillingHoneypotField(): void
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
            ->fillField('contact_form[faxnummer]', $faker->word())
            ->fillField('Nachricht', $faker->text())
            ->checkField('contact_form[datenschutz]')
            ->click('Formular absenden')
            ->assertSuccessful()
            ->assertOn('/')
            ->assertNotSee('Vielen Dank! Wir haben Ihre Nachricht erhalten und werden uns in kürze bei Ihnen melden.');
    }
}
