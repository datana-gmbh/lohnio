<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Page;

use App\Tests\Functional\FunctionalTestCase;

final class ImpressumControllerTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function aNotLoggedInUserCanVisitThePage(): void
    {
        $this->browser()
            ->visit('/impressum')
            ->assertSuccessful()
            ->assertSee('Impressum');
    }
}
