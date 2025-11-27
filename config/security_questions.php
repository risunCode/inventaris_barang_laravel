<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Daftar Pertanyaan Keamanan
    |--------------------------------------------------------------------------
    |
    | Pertanyaan keamanan yang dapat dipilih user untuk reset password.
    | User bisa memilih dari daftar atau membuat pertanyaan custom (value 0).
    |
    */

    'questions' => [
        1 => 'Apa nama ibu kandung Anda?',
        2 => 'Di kota mana Anda lahir?',
        3 => 'Apa nama sekolah dasar Anda?',
        4 => 'Apa makanan favorit Anda?',
        5 => 'Apa nama hewan peliharaan pertama Anda?',
        6 => 'Siapa nama guru favorit Anda?',
        7 => 'Apa warna favorit Anda?',
        8 => 'Apa nama jalan tempat Anda dibesarkan?',
        9 => 'Apa nomor telepon pertama yang Anda ingat?',
        10 => 'Apa merek kendaraan pertama Anda?',
    ],

    /*
    |--------------------------------------------------------------------------
    | Jumlah Pertanyaan yang Harus Dijawab
    |--------------------------------------------------------------------------
    */

    'required_questions' => 1,

    /*
    |--------------------------------------------------------------------------
    | Session Reset Password Timeout (menit)
    |--------------------------------------------------------------------------
    */

    'reset_timeout' => 15,
];
