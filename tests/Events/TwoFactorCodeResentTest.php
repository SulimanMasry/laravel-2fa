<?php

namespace Junges\TwoFactorAuth\Tests\Events;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Junges\TwoFactorAuth\Events\TwoFactorCodeResent;
use Junges\TwoFactorAuth\Http\Controllers\TwoFactorAuthController;
use Junges\TwoFactorAuth\Tests\TestCase;

class TwoFactorCodeResentTest extends TestCase
{
    public function test_resent_code_dispacth_code_resent_event()
    {
        Event::fake();

        Auth::login($this->user);

        $code = Str::upper(Str::random(8));

        $this->user->two_factor_code = $code;
        $this->user->save();

        (new TwoFactorAuthController())->resend();

        Event::assertDispatched(TwoFactorCodeResent::class, function ($event) {
            return $this->user->id === $event->user->id;
        });
    }
}
