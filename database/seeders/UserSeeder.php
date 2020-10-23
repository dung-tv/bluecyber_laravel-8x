<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456'),
                'created_at' => now(),
                'updated_at' => now(),
                'name' => 'Nguyen Van A',
                'sdt' => '0839348394',
                'gender' => 1
            ],
            [
                'username' => 'user',
                'email' => 'user@gmail.com',
                'password' => bcrypt('123456'),
                'created_at' => now(),
                'updated_at' => now(),
                'name' => 'Nguyễn Thị B',
                'sdt' => '0734858939',
                'gender' => 0
            ],
        ]);
        
        DB::table('permissions')->insert([
            [
                'name' => 'view_role',
                'display_name' => 'Danh sách vai trò',
            ],
            [
                'name' => 'add_role',
                'display_name' => 'Thêm vai trò',
            ],
            [
                'name' => 'edit_role',
                'display_name' => 'Sửa vai trò',
            ],
            [
                'name' => 'delete_role',
                'display_name' => 'Xoá vai trò',
            ],
            [
                'name' => 'view_user',
                'display_name' => 'Danh sách user',
            ],
            [
                'name' => 'add_user',
                'display_name' => 'Thêm user',
            ],
            [
                'name' => 'edit_user',
                'display_name' => 'Sửa user',
            ],
            [
                'name' => 'delete_user',
                'display_name' => 'Xoá user',
            ],
        ]);
        
        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'display_name' => 'Quản trị viên',
            ],
            [
                'name' => 'teacher',
                'display_name' => 'Giáo viên',
            ],
            [
                'name' => 'student',
                'display_name' => 'Học sinh',
            ],
        ]);
        DB::table('role_user')->insert([
            [
                'role_id' => 1,
                'user_id' => 1,
            ],
            [
                'role_id' => 2,
                'user_id' => 2,
            ],
        ]);
        DB::table('permission_role')->insert([
            ['permission_id' => 1, 'role_id' => 1],
            ['permission_id' => 2, 'role_id' => 1],
            ['permission_id' => 3, 'role_id' => 1],
            ['permission_id' => 4, 'role_id' => 1],
            ['permission_id' => 5, 'role_id' => 1],
            ['permission_id' => 6, 'role_id' => 1],
            ['permission_id' => 7, 'role_id' => 1],
            ['permission_id' => 8, 'role_id' => 1],
            ['permission_id' => 5, 'role_id' => 2],
            ['permission_id' => 6, 'role_id' => 2],
            ['permission_id' => 7, 'role_id' => 2],
            ['permission_id' => 8, 'role_id' => 2],
        ]);
    }
}
