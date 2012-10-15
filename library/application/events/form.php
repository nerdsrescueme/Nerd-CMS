<?php

namespace Application;

return [

    'form.csrf' => function(\Nerd\Form $form, $state) {
        \Nerd\Session::instance()->set('application.csrf', $state);
    },
];
