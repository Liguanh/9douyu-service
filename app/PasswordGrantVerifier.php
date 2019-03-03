<?php
namespace App;

use Illuminate\Support\Facades\Auth;

class PasswordGrantVerifier
{
  public function verify($username, $password)
  {
      $credentials = [
        'email'    => $username,
        'password' => $password,
      ];

      $user = User::where('email', $username)->whereOr('name', $username)->first();
      if ($user && app()['hash']->check($password, $user->password)) {
          return $user->id;
      }

      return false;
  }
}