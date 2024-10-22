<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Массив с данными контактов
        $contacts = [
            [
                "region_code" => "spb",
                "region_name" => "Санкт-Петербург",
                "address" => "ш. Революции, 65",
                "phone_number" => "+7 (969) 217-98-98",
                "work_time" => "Ежедневно, 09:00 – 21:00",
                "coordinates" => "59.960667, 30.460553",
                "social_links" => [
                    [
                        "telegram" => "https://t.me/rechipspb",
                        "whatsapp" => "https://api.whatsapp.com/qr/TMOLYMXAWM4ZA1?autoload=1&app_absent=0",
                        "telegram_channel" => "",
                        "youtube" => "https://www.youtube.com/@ReChip/shorts",
                        "drive2" => "",
                        "avito" => ""
                    ]
                ],
                "legal_info" => [
                    [
                        "inn" => "9726011190",
                        "kpp" => "772601001",
                        "ogrn" => "1187746343234 от 26 марта 2018 г.",
                        "legal_address" => "117405, Москва, вн.тер. Муниципальный Округ Чертаново Южное, ш Варшавское, д. 158, к. 1",
                        "phisical_address" => "117405, Москва, Варшавское шоссе, д. 152А",
                        "general_director" => "Абоба Зверь Максимович",
                        "footer_tiny_text" => "2024 © ReChip — чип-тюнинг ателье. ООО «РЕЧИП», ИНН: 9726011190, ОГРН: 1227700203785, КПП: 772601001. Юр. адрес: 117405, Москва, вн. тер. Муниципальный округ Чертаново Южное, ш Варшавское, д. 158 к. 1, этаж 1, пом. I, комн. 6, оф. 3. Физ. адрес: Москва, Варшавское шоссе, д. 152А"
                    ]
                ],
                "url" => "spb.rechip-tuning.ru"
            ],
            [
                "region_code" => "msk",
                "region_name" => "Москва",
                "address" => "г. Мытищи, ул. Мира с32/2",
                "phone_number" => "+7 (969) 217-98-98",
                "work_time" => "Ежедневно, 09:00 – 21:00",
                "coordinates" => "55.921183, 37.719405",
                "social_links" => [
                    [
                        "telegram" => "https://t.me/rechipspb",
                        "whatsapp" => "https://api.whatsapp.com/qr/TMOLYMXAWM4ZA1?autoload=1&app_absent=0",
                        "telegram_channel" => "",
                        "youtube" => "https://www.youtube.com/@ReChip/shorts",
                        "drive2" => "",
                        "avito" => ""
                    ]
                ],
                "legal_info" => [
                    [
                        "inn" => "9726011190",
                        "kpp" => "772601001",
                        "ogrn" => "1187746343234 от 26 марта 2018 г.",
                        "legal_address" => "117405, Москва, вн.тер. Муниципальный Округ Чертаново Южное, ш Варшавское, д. 158, к. 1",
                        "phisical_address" => "117405, Москва, Варшавское шоссе, д. 152А",
                        "general_director" => "Абоба Зверь Максимович",
                        "footer_tiny_text" => "2024 © ReChip — чип-тюнинг ателье. ООО «РЕЧИП», ИНН: 9726011190, ОГРН: 1227700203785, КПП: 772601001. Юр. адрес: 117405, Москва, вн. тер. Муниципальный округ Чертаново Южное, ш Варшавское, д. 158 к. 1, этаж 1, пом. I, комн. 6, оф. 3. Физ. адрес: Москва, Варшавское шоссе, д. 152А"
                    ]
                ],
                "url" => "msk.rechip-tuning.ru"
            ]
        ];

        // Вставка данных в таблицу contacts
        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}
