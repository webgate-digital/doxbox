<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CheckoutFlowTest extends DuskTestCase
{
    public function test()
    {
        // Visit homepage and click on the first product
        $this->browse(function (Browser $browser) {
            // Visit homepage
            $browser->visit('/');
            
            // Accept cookies
            $browser->click('.js-cookie-consent-agree');
            
            // Click the first product
            $browser->click('a.product-box:first-child');

            // Click the "Add to cart" button
            $browser->click('.add-to-cart-button');

            // Wait until the click "Go to cart" button is visible and click it
            $browser->waitFor('.proceed-to-cart-button');
            $browser->click('.proceed-to-cart-button');

            // Wait until cart loads
            $browser->waitFor('.cart-page');

            // There should be one product in the cart (.product-row is the class of the cart row)
            $browser->assertPresent('.product-row', 1);

            // Click the "Go to shipping" button
            $browser->click('.proceed-to-shipping-button');

            // Wait until shipping loads
            $browser->waitFor('.shipping-page');

            // Wait until 'Pickup' location is visible and click it
            $browser->waitFor('#SK_PERSONAL_0_99999_shipping');
            $browser->click('#SK_PERSONAL_0_99999_shipping');

            // Wait until 'Cash-On-Delivery' is visible and click it
            $browser->waitFor('#SK_CASH_0_20000_payment');
            $browser->click('#SK_CASH_0_20000_payment');

            // Wait until the Acceptance checkbox is visible and click it
            $browser->waitFor('#toc');
            $browser->click('#toc');

            // Click the "Go to checkout" button
            $browser->click('.proceed-to-checkout-button');

            // Insert user data
            $browser->type('name', 'John Doe');
            $browser->type('email', 'user@doxbox.sk');
            $browser->type('phone', '0987654321');
            $browser->type('street', 'Bottova');
            $browser->type('house_number', '2A');
            $browser->type('city', 'Bratislava');
            $browser->type('zip', '81109');

            // Click the "Place order" button
            $browser->click('.place-order-button');

            // There should be 'Thank you' page
            $browser->waitFor('.section--thank-you');
        });
    }
}
