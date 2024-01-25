<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MeanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         *INSERT INTO `means` (`id`, `word_id`, `word_type_id`, `means`, `description`, `example`, `status`, `created_at`, `updated_at`) VALUES (NULL, '12', '1', 'adada', 'daada', 'adada', '1', NULL, NULL); 
         */

        /**
         * ('1', '1', N'Tường lửa', N'Tường lửa là một phần mềm tiện ích (software utility) hoặc thiết bị phần cứng (hardware device) hoạt động như một bộ lọc dữ liệu vào hoặc ra khỏi mạng hoặc máy tính', 'Without a firewall, all your files could be instantly accessible to any competent hacker from anywhere in the world.')
         * ('2', '1', N'Nhà phân phối dịch vụ internet', N'Viết tắt của Internet service provider, ngoài ra còn được gọi là access provider hoặc network provider.', 'Some ISPs are free and give you as many email addresses as you want.')
         * ('3', '2', N'Tải xuống', N'Trên các mạng máy tính, download ( tải xuống ) có nghĩa là nhận dữ liệu từ một hệ thống từ xa', 'All of our products are available for download on our website.')
         * ('4', '1', N'Dịch vụ lưu trữ website', N'Dịch vụ lưu giữ và quản lý các trang web trên một máy chủ', 'The group supplies web-hosting services to blue-chip firms.')
         * ('5', '1', N'Biến', N'Biến (variable) là một vùng chứa (container) chứa một giá trị (value), chẳng hạn như một đoạn văn bản (text ) hoặc một số (number). Giá trị của nó có thể thay đổi đó là lý do tại sao nó được gọi là biến (variable)', 'int number = 20; // C#')
         * ('5', '3', N'Thay đổi', N'Variable khi sử dụng như tính từ mang nghĩa thay đổi (không cố định), nó thường nằm trước danh từ. Ví dụ trong SQL kiểu dữ liệu varchar có độ dài chuỗi ký tự thay đổi (variable length) nghĩa là kích thước không cố định như kiểu dữ liệu char.', 'city VARCHAR(50) -- SQL')
         * ('6','2',N'Tính toán',N'Phép tính là một quá trình toán học có chủ ý biến một hoặc nhiều đầu vào thành một hoặc nhiều đầu ra hoặc kết quả.','The computer will calculate your position with pinpoint accuracy.')
         * ('7','1',N'Hàm, chức năng',N'Hàm là một nhóm các câu lệnh cùng xử lý một nhiệm vụ cụ thể nào đó.','The website benefits from a highly-efficient search function.')
         * ('8','3',N'Ngang, đường ngang',N'Phương nằm ngang song song với mặt đất và vuông góc với phương thẳng đứng','The horizontal top and bottom bars indicate the lowest and highest values, excluding outliers.')
         * ('9','1',N'Hàng đợi',N' Hàng đợi (queue) là một cấu trúc dữ liệu hoạt động theo cơ chế FIFO (First In First Out), tạm dịch là “vào trước ra trước”.','The speed of the total process requires a large number of parts to be queued between stages.')
         * ('10','3',N'Ngẫu nhiên',N'Ngẫu nhiên là việc chọn một cách ngẫu nhiên một trong số các đối tượng,  trong đó mỗi đối tượng đều có cơ hội được lựa chọn','The winning entry will be the first correct answer drawn at random.')
         * ('11','1',N'Khôi phục',N'Khôi phục dữ liệu là quá trình sử dụng các thiết bị, phần mềm lấy lại các dữ liệu bị hư hỏng ','Use a recovery drive to restore or recover your PC')
         */
        DB::table('means')->insert(
            [
                [
                    'word_id' => '1',
                    'word_type_id' => '1',
                    'means' => 'Tường lửa',
                    'description' => 'Tường lửa là một phần mềm tiện ích (software utility) hoặc thiết bị phần cứng (hardware device) hoạt động như một bộ lọc dữ liệu vào hoặc ra khỏi mạng hoặc máy tính.',
                    'example' => 'Without a firewall, all your files could be instantly accessible to any competent hacker from anywhere in the world.',
                    'status' => 1,
                ],
                [
                    'word_id' => '2',
                    'word_type_id' => '1',
                    'means' => 'Nhà phân phối dịch vụ internet',
                    'description' => 'Viết tắt của Internet service provider, ngoài ra còn được gọi là access provider hoặc network provider.',
                    'example' => 'Some ISPs are free and give you as many email addresses as you want.',
                    'status' => 1,
                ],
                [
                    'word_id' => '3',
                    'word_type_id' => '2',
                    'means' => 'Tải xuống',
                    'description' => 'Trên các mạng máy tính, download (tải xuống) có nghĩa là nhận dữ liệu từ một hệ thống từ xa.',
                    'example' => 'All of our products are available for download on our website.',
                    'status' => 1,
                ],
                [
                    'word_id' => '4',
                    'word_type_id' => '1',
                    'means' => 'Dịch vụ lưu trữ website',
                    'description' => 'Dịch vụ lưu giữ và quản lý các trang web trên một máy chủ.',
                    'example' => 'The group supplies web-hosting services to blue-chip firms.',
                    'status' => 1,
                ],
                [
                    'word_id' => '5',
                    'word_type_id' => '1',
                    'means' => 'Biến',
                    'description' => 'Biến (variable) là một vùng chứa (container) chứa một giá trị (value), chẳng hạn như một đoạn văn bản (text ) hoặc một số (number). Giá trị của nó có thể thay đổi đó là lý do tại sao nó được gọi là biến (variable)',
                    'example' => 'int number = 20; // C#',
                    'status' => 1,
                ],
                [
                    'word_id' => '5',
                    'word_type_id' => '3',
                    'means' => 'Thay đổi',
                    'description' => 'Variable khi sử dụng như tính từ mang nghĩa thay đổi (không cố định), nó thường nằm trước danh từ. Ví dụ trong SQL kiểu dữ liệu varchar có độ dài chuỗi ký tự thay đổi (variable length) nghĩa là kích thước không cố định như kiểu dữ liệu char.',
                    'example' => 'city VARCHAR(50) -- SQL',
                    'status' => 1,
                ],
                [
                    'word_id' => '6',
                    'word_type_id' => '2',
                    'means' => 'Tính toán',
                    'description' => 'Phép tính là một quá trình toán học có chủ ý biến một hoặc nhiều đầu vào thành một hoặc nhiều đầu ra hoặc kết quả.',
                    'example' => 'The computer will calculate your position with pinpoint accuracy.',
                    'status' => 1,
                ],
                [
                    'word_id' => '7',
                    'word_type_id' => '1',
                    'means' => 'Hàm, chức năng',
                    'description' => 'Hàm là một nhóm các câu lệnh cùng xử lý một nhiệm vụ cụ thể nào đó.',
                    'example' => 'The website benefits from a highly-efficient search function.',
                    'status' => 1,
                ],
                [
                    'word_id' => '8',
                    'word_type_id' => '3',
                    'means' => 'Ngang, đường ngang',
                    'description' => 'Phương nằm ngang song song với mặt đất và vuông góc với phương thẳng đứng.',
                    'example' => 'The horizontal top and bottom bars indicate the lowest and highest values, excluding outliers.',
                    'status' => 1,
                ],
                [
                    'word_id' => '9',
                    'word_type_id' => '1',
                    'means' => 'Hàng đợi',
                    'description' => 'Hàng đợi (queue) là một cấu trúc dữ liệu hoạt động theo cơ chế FIFO (First In First Out), tạm dịch là "vào trước ra trước".',
                    'example' => 'The speed of the total process requires a large number of parts to be queued between stages.',
                    'status' => 1,
                ],
                [
                    'word_id' => '10',
                    'word_type_id' => '3',
                    'means' => 'Ngẫu nhiên',
                    'description' => 'Ngẫu nhiên là việc chọn một cách ngẫu nhiên một trong số các đối tượng, trong đó mỗi đối tượng đều có cơ hội được lựa chọn.',
                    'example' => 'The winning entry will be the first correct answer drawn at random.',
                    'status' => 1,
                ],
                [
                    'word_id' => '11',
                    'word_type_id' => '1',
                    'means' => 'Khôi phục',
                    'description' => 'Khôi phục dữ liệu là quá trình sử dụng các thiết bị, phần mềm lấy lại các dữ liệu bị hư hỏng.',
                    'example' => 'Use a recovery drive to restore or recover your PC.',
                    'status' => 1,
                ],
            ]
        );
    }
}
