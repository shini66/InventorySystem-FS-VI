<?php

it('redirige la raiz al dashboad', function () {
    $this->get('/')->assertRedirect('/dashboard');
});

it('manda a login a un invitado que va al dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

it('expone el health check del contenedor', function () {
    $this->get('/up')->assertOk();
});
