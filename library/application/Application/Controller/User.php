<?php

namespace Application\Controller;

use Nerd\Input
  , Auth\Auth;

class User extends Base
{
    public function actionLogin()
    {
        $app = $this->application;
        var_dump($app->session->get('admin'));

        Auth::logout();

        if (Input::$method === 'post') {
            $email    = Input::post('login.email');
            $password = Input::post('login.password');

            if (Auth::login($email, $password)) {
                $app->session->flash->set('success', 'Successfully logged in');
                //$app->redirect('/');
            } else {
                $app->session->flash->set('error', 'Unable to login');
            }
        }

        return $this->template->partial('content', 'user/login', [
            'email' => isset($email) ? $email : '',
        ]);
    }

    public function actionLogout()
    {
        Auth::logout();
        $this->application->redirect('login');
    }
}