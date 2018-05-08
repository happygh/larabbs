<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;


class UsersController extends Controller
{
    //显示个人信息
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    //编辑个人信息页面
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    //处理个人信息修改 自定义UserRequest
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        $data = $request->all();
        if ($request->avatar)
        {
            $rst = $uploader->save($request->avatar, 'avatar', $user->id, 362);
            if ($rst)
            {
                $data['avatar'] = $rst['path'];
            }
        }
        //dd($request->file('avatar'));
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人信息修改成功');
    }
}
