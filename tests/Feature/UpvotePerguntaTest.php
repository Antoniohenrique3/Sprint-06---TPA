<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\Pergunta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpvotePerguntaTest extends TestCase
{
    use RefreshDatabase;

    private function criarPergunta(User $autor): Pergunta
    {
        $evento = Evento::create(['titulo' => 'Evento teste', 'user_id' => $autor->id]);
        return Pergunta::create([
            'evento_id' => $evento->id,
            'user_id' => $autor->id,
            'texto' => 'Pergunta de teste para votação',
            'status' => 'pendente',
        ]);
    }

    public function test_anonymous_users_cannot_vote(): void
    {
        $pergunta = $this->criarPergunta(User::factory()->create());
        $this->post(route('perguntas.votar', $pergunta))->assertRedirect();
        $this->assertDatabaseCount('pergunta_user', 0);
    }

    public function test_vote_can_be_added_and_removed(): void
    {
        $user = User::factory()->create();
        $pergunta = $this->criarPergunta($user);
        $this->actingAs($user)->post(route('perguntas.votar', $pergunta))->assertRedirect();
        $this->assertDatabaseHas('pergunta_user', ['user_id' => $user->id, 'pergunta_id' => $pergunta->id]);
        $this->actingAs($user)->post(route('perguntas.votar', $pergunta))->assertRedirect();
        $this->assertDatabaseMissing('pergunta_user', ['user_id' => $user->id, 'pergunta_id' => $pergunta->id]);
    }

    public function test_show_ranks_only_event_questions_by_votes(): void
    {
        $author = User::factory()->create();
        $event = Evento::create(['titulo' => 'Evento ranking', 'user_id' => $author->id]);
        $first = $event->perguntas()->create(['texto' => 'Pergunta sem votos', 'user_id' => $author->id, 'status' => 'pendente']);
        $second = $event->perguntas()->create(['texto' => 'Pergunta popular', 'user_id' => $author->id, 'status' => 'pendente']);
        $second->votos()->attach([User::factory()->create()->id, User::factory()->create()->id]);
        $this->get(route('eventos.show', $event->id))
            ->assertOk()
            ->assertSeeInOrder(['Pergunta popular', 'Pergunta sem votos']);
    }
}
