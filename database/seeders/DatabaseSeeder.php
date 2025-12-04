<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Center;
use App\Models\AssessmentCriteria;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Assessment Criteria (Thai)
        $criterias = [
            'ด้านความสะอาด' => [
                'ความสะอาดอาคาร',
                'อุปกรณ์เด็ก',
                'ห้องน้ำ',
                'พื้นที่อาหาร',
                'อัปโหลดรูปเพื่อยืนยัน',
            ],
            'ด้านการดูแลเด็ก' => [
                'สภาพร่างกายเด็ก',
                'ภาวะโภชนาการ',
                'การเข้าเรียนสม่ำเสมอ',
                'ความปลอดภัยในศูนย์',
            ],
            'ด้านเอกสารและการจัดการ' => [
                'ความครบถ้วนในการบันทึกข้อมูล',
                'การส่งข้อมูลภายในเวลาที่กำหนด',
            ],
            'ด้านนวัตกรรมการเรียนการสอน' => [
                'การจัดกิจกรรมการเรียนรู้',
                'ผลงาน/หลักฐานกิจกรรม',
            ],
        ];

        foreach ($criterias as $category => $topics) {
            foreach ($topics as $topic) {
                AssessmentCriteria::create([
                    'category' => $category,
                    'topic' => $topic,
                    'max_score' => 3,
                ]);
            }
        }

        // 2. Create Centers (10 Centers)
        $centersData = [
            ['name' => 'ศูนย์พัฒนาเด็กเล็กบ้านหนองปรือ', 'code' => 'NP001', 'district' => 'หนองปรือ', 'province' => 'ชลบุรี'],
            ['name' => 'ศูนย์พัฒนาเด็กเล็กวัดดอนหวาย', 'code' => 'DW002', 'district' => 'สามพราน', 'province' => 'นครปฐม'],
            ['name' => 'ศูนย์พัฒนาเด็กเล็กเทศบาลตำบลบางพระ', 'code' => 'BP003', 'district' => 'ศรีราชา', 'province' => 'ชลบุรี'],
            ['name' => 'ศูนย์พัฒนาเด็กเล็ก อบต.คลองสาม', 'code' => 'KS004', 'district' => 'คลองหลวง', 'province' => 'ปทุมธานี'],
            ['name' => 'ศูนย์พัฒนาเด็กเล็กบ้านท่าทราย', 'code' => 'TS005', 'district' => 'เมือง', 'province' => 'สมุทรสาคร'],
            ['name' => 'ศูนย์พัฒนาเด็กเล็กวัดโบสถ์', 'code' => 'WB006', 'district' => 'สามโคก', 'province' => 'ปทุมธานี'],
            ['name' => 'ศูนย์พัฒนาเด็กเล็กชุมชนบ้านครัว', 'code' => 'BK007', 'district' => 'ราชเทวี', 'province' => 'กรุงเทพมหานคร'],
            ['name' => 'ศูนย์พัฒนาเด็กเล็กเทศบาลเมืองปทุมธานี', 'code' => 'PT008', 'district' => 'เมือง', 'province' => 'ปทุมธานี'],
            ['name' => 'ศูนย์พัฒนาเด็กเล็กบ้านสวน', 'code' => 'BS009', 'district' => 'เมือง', 'province' => 'ชลบุรี'],
            ['name' => 'ศูนย์พัฒนาเด็กเล็กวัดไผ่ล้อม', 'code' => 'PL010', 'district' => 'เมือง', 'province' => 'นครปฐม'],
        ];

        $firstNamesMale = ['สมชาย', 'สมศักดิ์', 'วิชัย', 'ธีระ', 'ประเสริฐ', 'ธนพล', 'จิรายุ', 'อาทิตย์', 'กิตติ', 'ณัฐวุฒิ'];
        $firstNamesFemale = ['สมหญิง', 'มานี', 'สุดา', 'รัตนา', 'วิไล', 'กานดา', 'พรทิพย์', 'นารี', 'ศิริพร', 'วาสนา'];
        $lastNames = ['ใจดี', 'รักชาติ', 'มีสุข', 'เจริญพร', 'มั่นคง', 'ทองดี', 'ศรีสุข', 'วงศ์สวัสดิ์', 'พัฒนา', 'รุ่งเรือง'];

        foreach ($centersData as $index => $centerData) {
            $center = Center::create([
                'name' => $centerData['name'],
                'code' => $centerData['code'],
                'district' => $centerData['district'],
                'province' => $centerData['province'],
                'address' => 'ที่อยู่สมมติ ' . $centerData['district'] . ' ' . $centerData['province'],
                'status' => 'active',
            ]);

            // Create Manager for each center
            User::create([
                'name' => 'ผู้จัดการ ' . $centerData['name'],
                'email' => 'manager' . ($index + 1) . '@childinsight.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'center_id' => $center->id,
            ]);

            // Create Teacher for each center
            User::create([
                'name' => 'ครู ' . $centerData['name'],
                'email' => 'teacher' . ($index + 1) . '@childinsight.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'center_id' => $center->id,
            ]);

            // 3. Create Students (10 per center)
            for ($i = 0; $i < 10; $i++) {
                $gender = $i % 2 == 0 ? 'male' : 'female';
                $firstName = $gender == 'male' 
                    ? $firstNamesMale[array_rand($firstNamesMale)] 
                    : $firstNamesFemale[array_rand($firstNamesFemale)];
                $lastName = $lastNames[array_rand($lastNames)];

                Student::create([
                    'center_id' => $center->id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gender' => $gender,
                    'dob' => now()->subYears(rand(2, 5))->subDays(rand(0, 365)),
                    'parent_name' => 'ผู้ปกครอง ' . $firstName,
                    'parent_contact' => '08' . rand(10000000, 99999999),
                ]);
            }
        }

        // 4. Create Global Users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@childinsight.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Inspector User',
            'email' => 'inspector@childinsight.com',
            'password' => Hash::make('password'),
            'role' => 'inspector',
        ]);
    }
}
