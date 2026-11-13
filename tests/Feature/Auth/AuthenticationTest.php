<?php

namespace Tests\Feature\Auth;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private array $ana = [
        'name' => 'Ana', 'email' => 'ana@example.com',
        'password' => 'secret123', 'password_confirmation' => 'secret123',
    ];

    public function test_a_visitor_can_register_and_is_logged_in(): void
    {
        Mail::fake(); // don't really send mail, just record what would be sent

        $this->post('/register', $this->ana)->assertRedirect(route('workouts.index'));

        $this->assertAuthenticated();
        Mail::assertQueued(WelcomeMail::class, fn ($mail) => $mail->hasTo('ana@example.com'));
    }

    public function test_registration_is_validated(): void
    {
        User::factory()->create(['email' => 'ana@example.com']);

        $this->post('/register', ['name' => '', 'email' => 'ana@example.com', 'password' => 'short', 'password_confirmation' => 'other'])
            ->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertGuest();
    }

    public function test_registration_still_works_when_the_mail_server_fails(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->post('/register', $this->ana)
            ->assertRedirect(route('workouts.index'))
            ->assertSessionHas('status', fn ($message) => str_contains($message, 'could not send'));

        $this->assertAuthenticated();
    }

    public function test_login_and_logout(): void
    {
        User::factory()->create(['email' => 'ana@example.com']); // factory password = "password"

        $this->post('/login', ['email' => 'ana@example.com', 'password' => 'password'])
            ->assertRedirect(route('workouts.index'));
        $this->assertAuthenticated();

        $this->post('/logout')->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_wrong_password_shows_an_error(): void
    {
        User::factory()->create(['email' => 'ana@example.com']);

        $this->post('/login', ['email' => 'ana@example.com', 'password' => 'wrong'])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        User::factory()->create(['email' => 'ana@example.com']);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'ana@example.com', 'password' => 'wrong']);
        }

        $this->post('/login', ['email' => 'ana@example.com', 'password' => 'wrong'])
            ->assertStatus(429)
            ->assertSee('Easy, champ'); // our custom 429 page
    }

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get(route('workouts.index'))->assertRedirect(route('login'));
        $this->get(route('tags.index'))->assertRedirect(route('login'));
        $this->get(route('exercises.create'))->assertRedirect(route('login'));
    }
}
