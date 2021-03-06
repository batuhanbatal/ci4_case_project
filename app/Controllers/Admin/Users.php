<?php namespace App\Controllers\Admin;

use App\Models\UserModel;
use App\Controllers\BaseController;
use App\Models\CaseReceiptModel;

class Users extends BaseController
{
	public function index()
	{
		$user = new UserModel();

		$data['users'] = $user->findAll();

		return view('admin/users/index', $data);
	}

	public function create()
	{
		return view('admin/users/create');
	}

	public function store()
	{
		$rules = 
		[
			'name'       	   => 'required|min_length[3]|max_length[30]',
			'email'      	   => "required|valid_email|is_unique[users.email]",
			'password'   	   => 'required|min_length[7]|max_length[30]',
			'password_confirm' => 'required|matches[password]',
			'money_limit'      => 'required|numeric',
		];

		if($this->validate($rules))
		{
			$data = 
			[
				'name'        => $this->request->getPost('name'),
				'email'       => $this->request->getPost('email'),
				'money_limit' => $this->request->getPost('money_limit'),
				'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
				'created_at'  => date("Y-m-d H:i:s"),
				'updated_at'  => date("Y-m-d H:i:s"),
			];

			$user = new UserModel();
			$save = $user->save($data);
			
			if($save)
			{
				$alert = 
				[
					'text' => 'User Create Successful',
					'type' => 'success',
				];

				session()->setFlashdata('alert', $alert);
				return redirect()->back();
			}   
			else
			{
				$alert = 
				[
					'text' => 'User Create Failed',
					'type' => 'danger',
				];

				session()->setFlashdata('alert', $alert);
				return redirect()->back();
			}
		}
		else
		{
			session()->setFlashdata('validation_errors', $this->validator);
			return redirect()->back();
		}
	}

	public function edit($id = NULL)
	{
		$user = new UserModel();

		$data['user'] = $user->where('id', $id)->first();

		if($data['user'])
		{
			return view('admin/users/edit', $data);
		}
		else
		{
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
	}

	public function update($id = NULL)
	{
		$user = new UserModel();

		$data['user'] = $user->where('id', $id)->first();

		if($data['user'])
		{
			$rules = 
			[
				'name'       	   => 'required|min_length[3]|max_length[30]',
				'email'      	   => "required|valid_email|is_unique[users.email,id,$id]",
				'password'   	   => 'permit_empty|min_length[7]|max_length[30]',
				'password_confirm' => 'permit_empty|matches[password]',
				'money_limit'      => 'required|numeric',
			];

			if($this->validate($rules))
			{
				$data = 
				[
					'name'       => $this->request->getPost('name'),
					'email'      => $this->request->getPost('email'),
					'money_limit' => $this->request->getPost('money_limit'),
					'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
					'updated_at' => date("Y-m-d H:i:s"),
				];
				
				$save = $user->update($id, $data);
				
				if($save)
				{
					$alert = 
					[
						'text' => 'User Update Successful',
						'type' => 'success',
					];
				}   
				else
				{
					$alert = 
					[
						'text' => 'User Update Failed',
						'type' => 'danger',
					];
				}

				session()->setFlashdata('alert', $alert);
				return redirect()->back();
			}
			else
			{
				session()->setFlashdata('validation_errors', $this->validator);
				return redirect()->back();
			}
		}
		else
		{
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
	}

	public function delete($id = NULL)
	{
		$user = new UserModel();

		$delete = $user->where('id', $id)->delete();

		if($delete)
		{
			$alert = 
			[
				'text' => 'User Delete Successful',
				'type' => 'success',
			];
		}
		else
		{
			$alert = 
			[
				'text' => 'User Delete Failed',
				'type' => 'danger',
			];
		}

		session()->setFlashdata('alert', $alert);
		return redirect()->to('/admin/users');
	}

	public function caseDetail($id = NULL)
	{
		$user = new UserModel();
		$user_find = $user->where('id', $id)->first();

		$case = new CaseReceiptModel();
		$data['case'] = $case->where('customer_id', $id)->findAll();

		if($user_find)
		{
			$data['delimitation_view'] = delimitation_view($data['case'], $user_find['money_limit']);
			return view('admin/users/case-detail', $data);
		}
		else
		{
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}
	}
}
