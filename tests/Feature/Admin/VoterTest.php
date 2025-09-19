<?php

namespace Tests\Feature\Admin;

use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VoterTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function an_admin_can_view_the_voters_list_for_an_election()
    {
        $election = Election::factory()->create();

        $response = $this->actingAs($this->adminUser)->get(route('admin.elections.voters.index', $election));

        $response->assertOk();
        $response->assertViewHas('election', $election);
    }

    /** @test */
    public function an_admin_can_view_the_voter_import_page()
    {
        $election = Election::factory()->create();

        $response = $this->actingAs($this->adminUser)->get(route('admin.elections.voters.import', $election));

        $response->assertOk();
        $response->assertViewHas('election', $election);
    }

    /** @test */
    public function an_admin_can_import_voters_from_a_csv_file()
    {
        Storage::fake('local');
        $election = Election::factory()->create();
        $csvContent = "full_name,email\nJohn Doe,john.doe@example.com\nJane Smith,jane.smith@example.com";
        $file = UploadedFile::fake()->createWithContent('voters.csv', $csvContent);

        $response = $this->actingAs($this->adminUser)->post(route('admin.elections.voters.processImport', $election), [
            'voters_file' => $file
        ]);

        $response->assertRedirect(route('admin.elections.voters.index', $election));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('voters', [
            'email' => 'john.doe@example.com',
            'election_id' => $election->id
        ]);
        $this->assertDatabaseHas('voters', [
            'email' => 'jane.smith@example.com',
            'election_id' => $election->id
        ]);
    }

    /** @test */
    public function an_admin_can_send_invitations_to_voters()
    {
        // Mock the service or a job dispatch if needed, for this test we'll just check if the redirect and session are correct.
        $election = Election::factory()->open()->create();
        $voters = \App\Models\Voter::factory(3)->invited()->create(['election_id' => $election->id]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.elections.voters.send-invitations', $election), [
            'voter_ids' => $voters->pluck('id')->toArray()
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Invitations sent to 3 voters.');
    }
}
