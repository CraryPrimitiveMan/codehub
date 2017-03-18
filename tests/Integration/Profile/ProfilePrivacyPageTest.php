<?php

class ProfilePrivacyPageTest extends TestCase
{
    use InteractsWithDatabase, CreatesUser;

    /** @test */
    public function it_can_refresh_the_profile_privacy_page()
    {
        Auth::guard('canvas')->login($this->user);
        $this->actingAs($this->user)
            ->visit(route('canvas.admin.profile.privacy'))
            ->click('Refresh Profile');
        $this->assertSessionMissing('errors');
        $this->seePageIs(route('canvas.admin.profile.index'));
    }

    /** @test */
    public function it_validates_the_current_password()
    {
//        $this->actingAs($this->user)->post('/password', [
//            'password'                  => 'wrongPass',
//            'new_password'              => 'newPass',
//            'new_password_confirmation' => 'newPass',
//        ]);
//
//        $this->assertEquals(Session::get('errors')->first(), trans('canvas::auth.failed'));
    }

    /** @test */
    public function it_can_update_the_password()
    {
//        $this->actingAs($this->user)->post('/password', [
//            'password'                  => 'password',
//            'new_password'              => 'newPass',
//            'new_password_confirmation' => 'newPass',
//        ]);
//
//        $this->assertSessionMissing('errors');
//        $this->assertTrue(Auth::validate([
//            'email'    => $this->user->email,
//            'password' => 'newPass',
//        ]));
    }
}
