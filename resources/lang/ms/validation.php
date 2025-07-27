<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Ruangan :attribute mesti diterima.',
    'accepted_if' => 'Ruangan :attribute mesti diterima apabila :other adalah :value.',
    'active_url' => 'Ruangan :attribute mesti URL yang sah.',
    'after' => 'Ruangan :attribute mesti tarikh selepas :date.',
    'after_or_equal' => 'Ruangan :attribute mesti tarikh selepas atau sama dengan :date.',
    'alpha' => 'Ruangan :attribute hanya boleh mengandungi huruf.',
    'alpha_dash' => 'Ruangan :attribute hanya boleh mengandungi huruf, nombor, sengkang dan garis bawah.',
    'alpha_num' => 'Ruangan :attribute hanya boleh mengandungi huruf dan nombor.',
    'array' => 'Ruangan :attribute mesti array.',
    'ascii' => 'Ruangan :attribute hanya boleh mengandungi aksara alfanumerik dan simbol byte tunggal.',
    'before' => 'Ruangan :attribute mesti tarikh sebelum :date.',
    'before_or_equal' => 'Ruangan :attribute mesti tarikh sebelum atau sama dengan :date.',
    'between' => [
        'array' => 'Ruangan :attribute mesti mempunyai antara :min dan :max item.',
        'file' => 'Ruangan :attribute mesti antara :min dan :max kilobait.',
        'numeric' => 'Ruangan :attribute mesti antara :min dan :max.',
        'string' => 'Ruangan :attribute mesti antara :min dan :max aksara.',
    ],
    'boolean' => 'Ruangan :attribute mesti benar atau salah.',
    'can' => 'Ruangan :attribute mengandungi nilai yang tidak dibenarkan.',
    'confirmed' => 'Pengesahan ruangan :attribute tidak sepadan.',
    'contains' => 'Ruangan :attribute kehilangan nilai yang diperlukan.',
    'current_password' => 'Kata laluan tidak betul.',
    'date' => 'Ruangan :attribute mesti tarikh yang sah.',
    'date_equals' => 'Ruangan :attribute mesti tarikh yang sama dengan :date.',
    'date_format' => 'Ruangan :attribute mesti sepadan dengan format :format.',
    'decimal' => 'Ruangan :attribute mesti mempunyai :decimal tempat perpuluhan.',
    'declined' => 'Ruangan :attribute mesti ditolak.',
    'declined_if' => 'Ruangan :attribute mesti ditolak apabila :other adalah :value.',
    'different' => 'Ruangan :attribute dan :other mesti berbeza.',
    'digits' => 'Ruangan :attribute mesti :digits digit.',
    'digits_between' => 'Ruangan :attribute mesti antara :min dan :max digit.',
    'dimensions' => 'Ruangan :attribute mempunyai dimensi imej yang tidak sah.',
    'distinct' => 'Ruangan :attribute mempunyai nilai pendua.',
    'doesnt_end_with' => 'Ruangan :attribute tidak boleh berakhir dengan salah satu daripada yang berikut: :values.',
    'doesnt_start_with' => 'Ruangan :attribute tidak boleh bermula dengan salah satu daripada yang berikut: :values.',
    'email' => 'Ruangan :attribute mesti alamat emel yang sah.',
    'ends_with' => 'Ruangan :attribute mesti berakhir dengan salah satu daripada yang berikut: :values.',
    'enum' => ':attribute yang dipilih tidak sah.',
    'exists' => ':attribute yang dipilih tidak sah.',
    'extensions' => 'Ruangan :attribute mesti mempunyai salah satu sambungan berikut: :values.',
    'file' => 'Ruangan :attribute mesti fail.',
    'filled' => 'Ruangan :attribute mesti mempunyai nilai.',
    'gt' => [
        'array' => 'Ruangan :attribute mesti mempunyai lebih daripada :value item.',
        'file' => 'Ruangan :attribute mesti lebih besar daripada :value kilobait.',
        'numeric' => 'Ruangan :attribute mesti lebih besar daripada :value.',
        'string' => 'Ruangan :attribute mesti lebih besar daripada :value aksara.',
    ],
    'gte' => [
        'array' => 'Ruangan :attribute mesti mempunyai :value item atau lebih.',
        'file' => 'Ruangan :attribute mesti lebih besar atau sama dengan :value kilobait.',
        'numeric' => 'Ruangan :attribute mesti lebih besar atau sama dengan :value.',
        'string' => 'Ruangan :attribute mesti lebih besar atau sama dengan :value aksara.',
    ],
    'hex_color' => 'Ruangan :attribute mesti warna heksadesimal yang sah.',
    'image' => 'Ruangan :attribute mesti imej.',
    'in' => ':attribute yang dipilih tidak sah.',
    'in_array' => 'Ruangan :attribute mesti wujud dalam :other.',
    'integer' => 'Ruangan :attribute mesti integer.',
    'ip' => 'Ruangan :attribute mesti alamat IP yang sah.',
    'ipv4' => 'Ruangan :attribute mesti alamat IPv4 yang sah.',
    'ipv6' => 'Ruangan :attribute mesti alamat IPv6 yang sah.',
    'json' => 'Ruangan :attribute mesti string JSON yang sah.',
    'list' => 'Ruangan :attribute mesti senarai.',
    'lowercase' => 'Ruangan :attribute mesti huruf kecil.',
    'lt' => [
        'array' => 'Ruangan :attribute mesti mempunyai kurang daripada :value item.',
        'file' => 'Ruangan :attribute mesti kurang daripada :value kilobait.',
        'numeric' => 'Ruangan :attribute mesti kurang daripada :value.',
        'string' => 'Ruangan :attribute mesti kurang daripada :value aksara.',
    ],
    'lte' => [
        'array' => 'Ruangan :attribute tidak boleh mempunyai lebih daripada :value item.',
        'file' => 'Ruangan :attribute mesti kurang atau sama dengan :value kilobait.',
        'numeric' => 'Ruangan :attribute mesti kurang atau sama dengan :value.',
        'string' => 'Ruangan :attribute mesti kurang atau sama dengan :value aksara.',
    ],
    'mac_address' => 'Ruangan :attribute mesti alamat MAC yang sah.',
    'max' => [
        'array' => 'Ruangan :attribute tidak boleh mempunyai lebih daripada :max item.',
        'file' => 'Ruangan :attribute tidak boleh lebih besar daripada :max kilobait.',
        'numeric' => 'Ruangan :attribute tidak boleh lebih besar daripada :max.',
        'string' => 'Ruangan :attribute tidak boleh lebih besar daripada :max aksara.',
    ],
    'max_digits' => 'Ruangan :attribute tidak boleh mempunyai lebih daripada :max digit.',
    'mimes' => 'Ruangan :attribute mesti fail jenis: :values.',
    'mimetypes' => 'Ruangan :attribute mesti fail jenis: :values.',
    'min' => [
        'array' => 'Ruangan :attribute mesti mempunyai sekurang-kurangnya :min item.',
        'file' => 'Ruangan :attribute mesti sekurang-kurangnya :min kilobait.',
        'numeric' => 'Ruangan :attribute mesti sekurang-kurangnya :min.',
        'string' => 'Ruangan :attribute mesti sekurang-kurangnya :min aksara.',
    ],
    'min_digits' => 'Ruangan :attribute mesti mempunyai sekurang-kurangnya :min digit.',
    'missing' => 'Ruangan :attribute mesti hilang.',
    'missing_if' => 'Ruangan :attribute mesti hilang apabila :other adalah :value.',
    'missing_unless' => 'Ruangan :attribute mesti hilang melainkan :other adalah :value.',
    'missing_with' => 'Ruangan :attribute mesti hilang apabila :values hadir.',
    'missing_with_all' => 'Ruangan :attribute mesti hilang apabila :values hadir.',
    'multiple_of' => 'Ruangan :attribute mesti gandaan :value.',
    'not_in' => ':attribute yang dipilih tidak sah.',
    'not_regex' => 'Format ruangan :attribute tidak sah.',
    'numeric' => 'Ruangan :attribute mesti nombor.',
    'password' => [
        'letters' => 'Ruangan :attribute mesti mengandungi sekurang-kurangnya satu huruf.',
        'mixed' => 'Ruangan :attribute mesti mengandungi sekurang-kurangnya satu huruf besar dan satu huruf kecil.',
        'numbers' => 'Ruangan :attribute mesti mengandungi sekurang-kurangnya satu nombor.',
        'symbols' => 'Ruangan :attribute mesti mengandungi sekurang-kurangnya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Sila pilih :attribute yang berbeza.',
    ],
    'present' => 'Ruangan :attribute mesti hadir.',
    'present_if' => 'Ruangan :attribute mesti hadir apabila :other adalah :value.',
    'present_unless' => 'Ruangan :attribute mesti hadir melainkan :other adalah :value.',
    'present_with' => 'Ruangan :attribute mesti hadir apabila :values hadir.',
    'present_with_all' => 'Ruangan :attribute mesti hadir apabila :values hadir.',
    'prohibited' => 'Ruangan :attribute dilarang.',
    'prohibited_if' => 'Ruangan :attribute dilarang apabila :other adalah :value.',
    'prohibited_unless' => 'Ruangan :attribute dilarang melainkan :other ada dalam :values.',
    'prohibits' => 'Ruangan :attribute melarang :other daripada hadir.',
    'regex' => 'Format ruangan :attribute tidak sah.',
    'required' => 'Ruangan :attribute diperlukan.',
    'required_array_keys' => 'Ruangan :attribute mesti mengandungi entri untuk: :values.',
    'required_if' => 'Ruangan :attribute diperlukan apabila :other adalah :value.',
    'required_if_accepted' => 'Ruangan :attribute diperlukan apabila :other diterima.',
    'required_if_declined' => 'Ruangan :attribute diperlukan apabila :other ditolak.',
    'required_unless' => 'Ruangan :attribute diperlukan melainkan :other ada dalam :values.',
    'required_with' => 'Ruangan :attribute diperlukan apabila :values hadir.',
    'required_with_all' => 'Ruangan :attribute diperlukan apabila :values hadir.',
    'required_without' => 'Ruangan :attribute diperlukan apabila :values tidak hadir.',
    'required_without_all' => 'Ruangan :attribute diperlukan apabila tiada :values hadir.',
    'same' => 'Ruangan :attribute mesti sepadan dengan :other.',
    'size' => [
        'array' => 'Ruangan :attribute mesti mengandungi :size item.',
        'file' => 'Ruangan :attribute mesti :size kilobait.',
        'numeric' => 'Ruangan :attribute mesti :size.',
        'string' => 'Ruangan :attribute mesti :size aksara.',
    ],
    'starts_with' => 'Ruangan :attribute mesti bermula dengan salah satu daripada yang berikut: :values.',
    'string' => 'Ruangan :attribute mesti string.',
    'timezone' => 'Ruangan :attribute mesti zon masa yang sah.',
    'unique' => ':attribute telah diambil.',
    'uploaded' => ':attribute gagal dimuat naik.',
    'uppercase' => 'Ruangan :attribute mesti huruf besar.',
    'url' => 'Ruangan :attribute mesti URL yang sah.',
    'ulid' => 'Ruangan :attribute mesti ULID yang sah.',
    'uuid' => 'Ruangan :attribute mesti UUID yang sah.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'nama',
        'username' => 'nama pengguna',
        'email' => 'alamat emel',
        'password' => 'kata laluan',
        'password_confirmation' => 'pengesahan kata laluan',
        'phone' => 'nombor telefon',
        'address' => 'alamat',
        'date' => 'tarikh',
        'time' => 'masa',
        'message' => 'mesej',
        'description' => 'penerangan',
    ],

];
