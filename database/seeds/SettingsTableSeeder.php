<?php

// namespace Database\Seeds;

use App\Setting;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings')->insert([
            'id' => 29,
            'created_on' => '2017-01-08 04:30:43',
            'updated_on' => '2020-05-02 01:47:50',
            'created_by' => -1,
            'updated_by' => -1,
            'type' => 'UNIVERSITY',
            'email' => 'registrar@juniv.edu',
            'notify_internet_complain' => '',
            'notify_internet_connection' => '',
            'notify_teacher' => 'webs@juniv.edu',
            'notify_email' => 'webs@juniv.edu',
            'phone' => '02224491045-51',
            'fax' => '02224491052',
            'address' => 'Jahangirnagar University, Savar, Dhaka-1342, Bangladesh.',
            'logo_url' => null,
            'header_text' => 'Phone: 880-2-7791040 (Off.) E-mail: registr@juniv.edu',
            'footer_text' => 'Jahangirnagar University, Savar, Dhaka-1342, Bangladesh. Telephone: PABX:02224491045-51 , Fax:02224491052',
            'copyright_text' => '© 2024 Jahangirnagar University',
            'owner_image_url' => '/upload/settings/university/Prof-Dr-FARZANA-ISLAM-a0481b.jpg',
            'owner_msg' => null,
            'contact_us_link' => '/custom/slug/contact-us',
            'jobs_link' => '/discussion?event_id=5',
            'max_profile_image_size_in_kb' => 200,
            'webmail_link' => 'https://mail.google.com/a/juniv.edu/',
            'hall_enabled' => 1,
            'featured_news_enabled' => 1,
            'max_discussion_image_size_in_kb' => 500,
            'about_us_link' => '/custom/slug/about-ju',
            'mission_and_vission_link' => '/page/mission-vision',
            'animate_header_admission_link' => 1,
            'banner_image_limit' => 7,
            'facebook_link' => 'https://www.facebook.com/edu.juniv',
            'twitter_link' => null,
            'linkedin_link' => null,
            'top_contact_menu' => '<a href="https://juniv.edu/login" class="d-inline-block">Login</a> |
                                    <a href="https://juniv.edu/discussion?event_id=5" class="d-inline-block">Career</a> |
                                    <a href="https://juniv.edu/office/iqac" class="d-inline-block">IQAC</a> | 
                                    <a href="https://juniv.edu/office/ictcell" class="d-inline-block">ICT Cell</a> | 
                                    <a href="https://juniv.edu/useful-links/apa" class="d-inline-block">APA</a> 
                                    <a href="https://juniv.edu/discussion/14821/file/14651" class="d-inline-block btn btn-success last">Citizen\'s Charter</a>',
            'welcome_message' => 'Jahangirnagar University is a prominent public university of Bangladesh under the act of 1973. The university provides multidiscipline education since 1970. Now we have 35 departments under different faculties and institutes. Jahangirnagar University is a prominent public university of Bangladesh under the act of 1973. The university provides multidiscipline education since 1970. Now we have 35 departments under different faculties and institutes. Jahangirnagar University is a prominent public university of Bangladesh under the act of 1973. The university provides multidiscipline education since 1970. Now we have 35 departments under different faculties and institutes.',
            'custom_css' => '.last { margin: 0 0 4px 0 !important; color: #fff !important; } 
                            #researchAndPublication p { margin: 0 0 4px 0 !important; }
                            #profile { border-top: 2px solid #035abc; border-bottom: 2px solid #035abc; border-left: 1px solid #f4f4f4; 
                            border-right: 1px solid #f4f4f4; border-radius: 4px; padding: 5px; margin-bottom: 15px; }',
            'custom_js' => '["4","10"]',
            'home_first_section_event' => '["1","2"]',
            'home_second_section_event' => '["6","3"]',
            'home_third_section_event' => '["4","1","2"]',
            'department_event' => '123456',
            'default_password_new_user' => 20,
            'frontend_pagination_number' => 40,
            'backend_pagination_number' => 5,
            'spotlight_number' => 5,
        ]);
    }
}
