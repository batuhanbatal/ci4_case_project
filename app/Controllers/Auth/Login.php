<?php namespace App\Controllers\Auth;

use App\Models\UserModel;
use App\Controllers\BaseController; 

class Login extends BaseController
{
    public function __construct()
    {
        helper(['form']);
    }

	public function index()
	{
        session()->destroy();
		return view('auth/login.php');
    }
    
    public function action()
    {
        $rules = 
        [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if($this->validate($rules))
        {
            $email    = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = new UserModel();
            $user = $user->where('email', $email)->first();
            
            $verify = password_verify($password, $user['password']);

            if($verify)
            {
                $data = 
                [
                    'id'         => $user['id'],
                    'name'       => $user['name'],
                    'email'      => $user['email'],
                    'created_at' => $user['created_at'],
                    'updated_at' => $user['updated_at'],
                    'login'      => 1,
                ];

                session()->set('user', $data);
                return redirect()->to('/admin');
            }   
            else
            {
                $alert = 
                [
                    'text' => 'Email or Password did not match',
                    'type' => 'danger',
                ];
            
                session()->setFlashdata('alert', $alert);
                return redirect()->to('/login');
            }
        }
        else
        {
            session()->setFlashdata('validation_errors', $this->validator);
            return redirect()->back();
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}