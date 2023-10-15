<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\storeRequest;
use App\Http\Requests\Admin\users\updateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class usersController extends Controller
{
    public function all()
    {
        $users = User::paginate(5);
        return view('admin.users.all', compact('users'));
    }

    public function create()
    {
        return view('admin.users.add');
    }

    public function store(storeRequest $request)
    {
        $validatedData = $request->validated();

        $createdUser = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile'],
            'role' => $validatedData['role']
        ]);

        if(!$createdUser)
        {
            return back()->with('failed', 'کاربر ایجاد نشد');
        }

        return back()->with('success', 'کاربر ایجاد شد');
    }

    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);

        return view('admin.users.edit', compact('user'));
    }

    public function delete($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->delete();
        return back()->with('success', 'کاربر با موفقیت حذف شد');
    }

    public function update(updateRequest $request, $user_id)
    {
        $validatedData = $request->validated();

        $user = User::findOrFail($user_id);

        $updatedData = $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile'],
            'role' => $validatedData['role'],
        ]);

        if(!$updatedData)
        {
            return back()->with('failed', 'کاربر ویرایش نشد');
        }
        return back()->with('success', 'کاربر با موفقیت بروزرسانی شد');
    }
}
