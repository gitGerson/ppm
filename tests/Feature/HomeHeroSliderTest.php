<?php

use App\Models\Slider;

test('home hero uses active slider records in configured order', function () {
    Slider::factory()->create([
        'title' => 'Second Active Slide',
        'image_path' => 'sliders/second.jpg',
        'alt_text' => 'Second active alt',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    Slider::factory()->create([
        'title' => 'First Active Slide',
        'image_path' => 'sliders/first.jpg',
        'alt_text' => 'First active alt',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    Slider::factory()->create([
        'title' => 'Inactive Slide',
        'image_path' => 'sliders/inactive.jpg',
        'alt_text' => 'Inactive alt',
        'sort_order' => 0,
        'is_active' => false,
    ]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSeeInOrder([
            'storage/sliders/first.jpg',
            'storage/sliders/second.jpg',
        ])
        ->assertSee('First active alt')
        ->assertSee('Second active alt')
        ->assertDontSee('Inactive alt')
        ->assertDontSee('storage/sliders/inactive.jpg');
});

test('home hero falls back to default static slides when no active slider exists', function () {
    Slider::factory()->create([
        'title' => 'Inactive Slide',
        'image_path' => 'sliders/inactive.jpg',
        'alt_text' => 'Inactive alt',
        'is_active' => false,
    ]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('images/assets/hero.png')
        ->assertSee('images/assets/hero2.png')
        ->assertDontSee('Inactive alt');
});
