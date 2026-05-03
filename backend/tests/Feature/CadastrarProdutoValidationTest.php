<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CadastrarProdutoValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_nao_deve_cadastrar_sem_nome(): void
    {
        $payload = ['preco_venda' => 100.00];

        $response = $this->postJson('/api/produtos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }

    public function test_nao_deve_cadastrar_com_nome_nao_string(): void
    {
        $payload = ['nome' => 12345, 'preco_venda' => 100.00];

        $response = $this->postJson('/api/produtos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }

    public function test_nao_deve_cadastrar_com_nome_curto(): void
    {
        $payload = ['nome' => 'ab', 'preco_venda' => 100.00];

        $response = $this->postJson('/api/produtos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }

    public function test_nao_deve_cadastrar_com_nome_maior_que_255_caracteres(): void
    {
        $payload = ['nome' => str_repeat('a', 256), 'preco_venda' => 100.00];

        $response = $this->postJson('/api/produtos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }

    public function test_nao_deve_cadastrar_sem_preco_venda(): void
    {
        $payload = ['nome' => 'Fone Ninja'];

        $response = $this->postJson('/api/produtos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['preco_venda']);
    }

    public function test_nao_deve_cadastrar_com_preco_nao_numerico(): void
    {
        $payload = ['nome' => 'Fone Ninja', 'preco_venda' => 'cem reais'];

        $response = $this->postJson('/api/produtos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['preco_venda']);
    }

    public function test_nao_deve_cadastrar_com_preco_negativo(): void
    {
        $payload = ['nome' => 'Fone Ninja', 'preco_venda' => -10.00];

        $response = $this->postJson('/api/produtos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['preco_venda']);
    }
}
