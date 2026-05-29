<?php

test('guidebook page renders embedded flipbook', function () {
    $this->get(route('guidebook'))
        ->assertSuccessful()
        ->assertSee('Guidebook')
        ->assertSee('https://heyzine.com/flip-book/afd2bf2064.html', false);
});

test('navbar links to guidebook page', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Guidebook')
        ->assertSee(route('guidebook'), false);
});
