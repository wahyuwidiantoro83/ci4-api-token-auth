<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use \Codeigniter\Shield\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    public function user_login()
    {
        $cradential = [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ];

        if (auth()->loggedIn()) {
            auth()->logout();
        }

        $loginAttempt = auth()->attempt($cradential);
        if (!$loginAttempt->isOK()) {
            return $this->response->setStatusCode(400)->setBody('invalid login');
        } else {
            $user = new UserModel();
            $userData = $user->find(auth()->id());
            $token = $userData->generateAccessToken('thisismytoken');
            $auth_token = $token->raw_token;
            return $this->response->setJSON(
                [
                    'token' => $auth_token,
                    'status' => true
                ]
            );
        }
    }

    public function user_logout()
    {
        return response()->setJSON([
            'message' => 'Login dulu'
        ]);
    }
}
