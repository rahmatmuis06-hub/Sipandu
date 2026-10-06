<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class LogoutTest extends TestCase {
 use RefreshDatabase;
 public function test_logout_clears_session_and_redirects_on_same_origin(): void {
  $this->actingAs(User::factory()->create(['role'=>'adminpersediaan','is_active'=>true]));
  $this->withSession(['private_marker'=>'old'])->post('/logout')->assertRedirect('/login')->assertSessionMissing('private_marker');
  $this->assertGuest();
  $this->get('/adminpersediaan/dashboard')->assertRedirect();
 }
}
