<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang thông tin cá nhân
     */
    public function show()
    {
        $user = Auth::user();
        return view('user.profile.show', compact('user'));
    }

    /**
     * Hiển thị form chỉnh sửa thông tin cá nhân
     */
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        // Kiểm tra nếu yêu cầu thay đổi mật khẩu
        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        User::where('id', $user->id)->update($validated);

        return redirect()->route('profile.show')->with('success', 'Cập nhật thông tin thành công!');
    }
}
