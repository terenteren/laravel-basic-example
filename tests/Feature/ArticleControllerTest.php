<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

use App\Models\User;
use App\Models\Article;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 로그인하지_않은_사용자는_글쓰기_화면을_볼_수_없다(): void
    {
        $this->get(route('articles.create'))
        ->assertStatus(302)
        ->assertRedirectToRoute('login');
    }

    /**
     * @test
     */
    public function 로그인_하지_않은_사용자는_글을_작성할_수_없다(): void
    {
        $testData = [
            'body' => 'test article'
        ];

        $this->post(route('articles.store'), $testData)
        ->assertRedirectToRoute('login');

        $this->assertDatabaseMissing('articles', $testData);
    }

    /**
     * @test
     */
    public function 글_목록을_확인할_수_있다(): void
    {
        $now = Carbon::now();
        $afterOneSecond = (clone $now)->addSecond();

        $article1 = Article::factory()->create(
            ['created_at' => $now]
        );
        $article2 = Article::factory()->create(
            ['created_at' => $afterOneSecond]
        );

        $this->get(route('articles.index'))
        ->assertSeeInOrder([
            $article2->body,
            $article1->body
        ]);
    }

    /**
     * @test
     */
    public function 개별_글을_조회할_수_있다(): void
    {
        $article = Article::factory()->create();

        $this->get(route('articles.show', ['article' => $article->id]))
        ->assertSuccessful()
        ->assertSee($article->body);
    }


    /**
     * @test
     */
    public function 로그인한_사용자는_글수정_화면을_볼_수_있다(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
        ->get(route('articles.edit', ['article' => $article->id]))
        ->assertStatus(200)
        ->assertSee($article->body);
        // ->assertSee('글 수정하기');
    }


    /**
     * @test
     */
    public function 로그인하지_않은_사용자는_글수정_화면을_볼_수_없다(): void
    {
        $article = Article::factory()->create();

        $this->get(route('articles.edit', ['article' => $article->id]))
        ->assertRedirectToRoute('login');
    }


    /**
     * @test
     */
    public function 로그인한_사용자는_글을_수정할_수_있다(): void
    {
        $user = User::factory()->create();

        $payload = ['body' => '수정된 글'];

        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
        ->patch(
            route('articles.update', ['article' => $article->id]),
            $payload
        )->assertRedirect(route('articles.index'));

        $this->assertDatabaseHas('articles', $payload);

        $this->assertEquals($payload['body'], $article->refresh()->body);
    }


    /**
     * @test
     */
    public function 로그인하지_않은_사용자는_글을_수정할_수_없다(): void
    {
        $payload = ['body' => '수정된 글'];

        $article = Article::factory()->create();

        $this->patch(
            route('articles.update', ['article' => $article->id]),
            $payload
        )->assertRedirectToRoute('login');

        $this->assertDatabaseMissing('articles', $payload);

        $this->assertNotEquals($payload['body'], $article->refresh()->body);
    }


    /**
     * @test
     */
    public function 로그인한_사용자는_글을_삭제할_수_있다(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
        ->delete(route('articles.destroy', ['article' => $article->id]))
        ->assertRedirect(route('articles.index'));

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }


    /**
     * @test
     */
    public function 로그인하지_않은_사용자는_글을_삭제할_수_없다(): void
    {
        $article = Article::factory()->create();

        $this->delete(route('articles.destroy', ['article' => $article->id]))
        ->assertRedirectToRoute('login');

        $this->assertDatabaseHas('articles', ['id' => $article->id]);
    }


}
