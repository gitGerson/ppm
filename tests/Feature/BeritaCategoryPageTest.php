<?php

use App\Models\Berita;

test('home shows berita category groups without listing article content', function () {
    Berita::factory()->create([
        'title' => 'Older Kajian Title',
        'category' => Berita::CategoryPengajian,
        'image_url' => 'berita/older-kajian.jpg',
        'date' => now()->subDays(2)->toDateString(),
        'visible' => true,
    ]);

    Berita::factory()->create([
        'title' => 'Kajian Visible Title',
        'category' => Berita::CategoryPengajian,
        'image_url' => 'berita/latest-kajian.jpg',
        'date' => now()->toDateString(),
        'visible' => true,
    ]);

    Berita::factory()->create([
        'title' => 'Praktek Hidden Title',
        'category' => Berita::CategoryPraktek,
        'visible' => false,
    ]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee(Berita::CategoryPengajian)
        ->assertSee('2 berita')
        ->assertSee('storage/berita/latest-kajian.jpg')
        ->assertDontSee('storage/berita/older-kajian.jpg')
        ->assertSee(route('berita.category', Berita::categorySlug(Berita::CategoryPengajian)), false)
        ->assertDontSee('Kajian Visible Title')
        ->assertDontSee(Berita::CategoryPraktek);
});

test('berita category page shows only visible articles from selected category', function () {
    Berita::factory()->create([
        'title' => 'Kajian Category Article',
        'category' => Berita::CategoryPengajian,
        'visible' => true,
    ]);

    Berita::factory()->create([
        'title' => 'Praktek Category Article',
        'category' => Berita::CategoryPraktek,
        'visible' => true,
    ]);

    Berita::factory()->create([
        'title' => 'Hidden Kajian Article',
        'category' => Berita::CategoryPengajian,
        'visible' => false,
    ]);

    $this->get(route('berita.category', Berita::categorySlug(Berita::CategoryPengajian)))
        ->assertSuccessful()
        ->assertSee(Berita::CategoryPengajian)
        ->assertSee('Kajian Category Article')
        ->assertDontSee('Praktek Category Article')
        ->assertDontSee('Hidden Kajian Article');
});

test('berita category page returns not found for unknown category', function () {
    $this->get(route('berita.category', 'unknown-category'))
        ->assertNotFound();
});
