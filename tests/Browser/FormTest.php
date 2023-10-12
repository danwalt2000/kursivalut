<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Http\Controllers\DBController;

class FormTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testForm(): void
    {
        
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Обмен валюты в Донецке и ДНР');
            
            // open form
            $browser->click("#form-open");
            $browser->assertVisible('#form-bg');

            // typing values
            $browser->type('#ad-currencies', 'Доллар');
            $browser->type('#rate', '100');
            $browser->type('#phone', '+712345678900');
            $browser->type('#sum', '3000');
            $browser->type('#city', 'Макеевка');

            // submiting
            $browser->click("#ad_form_submit");
            // assuming that congratulation message is shown
            $browser->assertVisible('.form-congrats');
            $browser->screenshot('shot');
            
            
            
        });
    }
}
