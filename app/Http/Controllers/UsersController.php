<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'show']);
    }

    //显示个人信息
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    //编辑个人信息页面
    public function edit(User $user)
    {
        //运行UserPolicy授权策略
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    //处理个人信息修改 自定义UserRequest
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        //运行UserPolicy授权策略
        $this->authorize('update', $user);
        $data = $request->all();
        if ($request->avatar)
        {
            //图片上传  数据,目录名,Id,图片限宽
            $rst = $uploader->save($request->avatar, 'avatar', $user->id, 362);
            if ($rst)
            {
                //如果用户上传了新头像则删除原来的图片
                $oldimg = $request->input('oldavatarimg');
                if ( ! empty($oldimg))
                {
                    //处理文件路径unlink不能删除带域名的
                    $path = parse_url($oldimg);
                    $oldpath = public_path().$path['path'];
                    if (file_exists($oldpath))
                    {
                        unlink($oldpath);
                    }
                }
                $data['avatar'] = $rst['path'];
            }
        }
        //dd($request->file('avatar'));
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人信息修改成功');
    }
}
