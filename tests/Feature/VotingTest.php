<?php

namespace Tests\Feature;

use App\Models\Ballot;
use App\Models\Election;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\Voter;
use App\Models\Token;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VotingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_voter_can_view_the_ballot_with_a_valid_token()
    {
        $election = Election::factory()->open()->create();
        $voter = Voter::factory()->invited()->create(['election_id' => $election->id]);
        $token = Token::factory()->create(['voter_id' => $voter->id, 'election_id' => $election->id]);

        $response = $this->get(route('vote.show', $token->token));

        $response->assertStatus(200);
        $response->assertViewIs('vote.ballot');
        $response->assertViewHas('election', $election);
    }

    /** @test */
    public function a_voter_cannot_view_the_ballot_with_an_invalid_token()
    {
        $response = $this->get(route('vote.show', 'invalid_token'));

        $response->assertStatus(404);
    }

    /** @test */
    public function a_voter_cannot_view_the_ballot_for_a_closed_election()
    {
        $election = Election::factory()->closed()->create();
        $voter = Voter::factory()->invited()->create(['election_id' => $election->id]);
        $token = Token::factory()->create(['voter_id' => $voter->id, 'election_id' => $election->id]);

        $response = $this->get(route('vote.show', $token->token));

        $response->assertStatus(200);
        $response->assertViewIs('vote.closed');
    }

    /** @test */
    public function a_voter_can_submit_a_valid_ballot()
    {
        $election = Election::factory()->open()->create();
        $voter = Voter::factory()->verified()->create(['election_id' => $election->id]);
        $token = Token::factory()->create(['voter_id' => $voter->id, 'election_id' => $election->id]);

        $position1 = Position::factory()->create(['election_id' => $election->id, 'max_votes' => 1]);
        $candidate1 = Candidate::factory()->create(['position_id' => $position1->id]);
        $selections = [
            $position1->id => [$candidate1->id]
        ];

        $response = $this->post(route('vote.submit', $token->token), ['selections' => $selections]);

        $response->assertRedirect(route('vote.receipt', Ballot::first()->ballot_uid));
        $this->assertDatabaseHas('ballots', [
            'voter_id' => $voter->id,
            'election_id' => $election->id,
        ]);
        $this->assertDatabaseHas('votes', [
            'ballot_id' => Ballot::first()->id,
            'candidate_id' => $candidate1->id,
        ]);
    }

    /** @test */
    public function a_voter_can_view_a_receipt_with_a_valid_ballot_uid()
    {
        $ballot = Ballot::factory()->create();

        $response = $this->get(route('vote.receipt', $ballot->ballot_uid));

        $response->assertStatus(200);
        $response->assertViewIs('vote.receipt');
        $response->assertViewHas('ballot', $ballot);
    }
}
