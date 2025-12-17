<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function getUsers(?string $search = null) {
        $users = User::when($search, function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });

        return $users->get();
    }

    public function createUser($name, $email, $password) {
        $created = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return $created;
    }

    public function getUser($id) {
        return User::findorFail($id);
    }

    public function updateUser($id, $params) {
        $user = $this->getUser($id);

        $user->update($params);

        return $user;
    }

    public function deleteUser($id) {
        $user = $this->getUser($id);

        $user->delete();

        return $user;
    }
}
