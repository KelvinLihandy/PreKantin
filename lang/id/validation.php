<?php

return [

    'accepted' => 'Kolom :attribute harus diterima.',
    'accepted_if' => 'Kolom :attribute harus diterima ketika :other bernilai :value.',
    'active_url' => 'Kolom :attribute harus berupa URL yang valid.',
    'after' => 'Kolom :attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => 'Kolom :attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => 'Kolom :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka.',
    'array' => 'Kolom :attribute harus berupa array.',
    'ascii' => 'Kolom :attribute hanya boleh berisi karakter alfanumerik byte tunggal dan simbol.',
    'before' => 'Kolom :attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => 'Kolom :attribute harus berupa tanggal sebelum atau sama dengan :date.',

    'between' => [
        'array' => 'Kolom :attribute harus memiliki antara :min sampai :max item.',
        'file' => 'Kolom :attribute harus berukuran antara :min sampai :max kilobyte.',
        'numeric' => 'Kolom :attribute harus bernilai antara :min sampai :max.',
        'string' => 'Kolom :attribute harus memiliki :min sampai :max karakter.',
    ],

    'boolean' => 'Kolom :attribute harus bernilai true atau false.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Password tidak sesuai.',
    'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
    'date_equals' => 'Kolom :attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => 'Kolom :attribute harus sesuai format :format.',
    'decimal' => 'Kolom :attribute harus memiliki :decimal angka desimal.',
    'different' => 'Kolom :attribute dan :other harus berbeda.',
    'digits' => 'Kolom :attribute harus berupa :digits digit.',
    'digits_between' => 'Kolom :attribute harus memiliki antara :min sampai :max digit.',
    'dimensions' => 'Dimensi gambar pada kolom :attribute tidak valid.',
    'distinct' => 'Kolom :attribute memiliki nilai duplikat.',

    'email' => 'Kolom :attribute harus berupa alamat email valid.',
    'ends_with' => 'Kolom :attribute harus diakhiri dengan salah satu dari: :values.',
    'enum' => 'Pilihan :attribute tidak valid.',
    'exists' => 'Pilihan :attribute tidak valid.',

    'file' => 'Kolom :attribute harus berupa file.',
    'filled' => 'Kolom :attribute harus memiliki nilai.',

    'gt' => [
        'array' => 'Kolom :attribute harus memiliki lebih dari :value item.',
        'file' => 'Kolom :attribute harus lebih besar dari :value kilobyte.',
        'numeric' => 'Kolom :attribute harus lebih besar dari :value.',
        'string' => 'Kolom :attribute harus lebih dari :value karakter.',
    ],

    'gte' => [
        'array' => 'Kolom :attribute harus memiliki minimal :value item.',
        'file' => 'Kolom :attribute harus lebih besar atau sama dengan :value kilobyte.',
        'numeric' => 'Kolom :attribute harus lebih besar atau sama dengan :value.',
        'string' => 'Kolom :attribute harus minimal :value karakter.',
    ],

    'image' => 'Kolom :attribute harus berupa gambar.',
    'in' => 'Pilihan :attribute tidak valid.',
    'in_array' => 'Kolom :attribute harus ada dalam :other.',
    'integer' => 'Kolom :attribute harus berupa angka.',
    'ip' => 'Kolom :attribute harus berupa IP address yang valid.',
    'ipv4' => 'Kolom :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => 'Kolom :attribute harus berupa alamat IPv6 yang valid.',
    'json' => 'Kolom :attribute harus berupa JSON yang valid.',

    'max' => [
        'array' => 'Kolom :attribute maksimal memiliki :max item.',
        'file' => 'Kolom :attribute maksimal berukuran :max kilobyte.',
        'numeric' => 'Kolom :attribute maksimal bernilai :max.',
        'string' => 'Kolom :attribute maksimal :max karakter.',
    ],

    'min' => [
        'array' => 'Kolom :attribute minimal memiliki :min item.',
        'file' => 'Kolom :attribute minimal berukuran :min kilobyte.',
        'numeric' => 'Kolom :attribute minimal bernilai :min.',
        'string' => 'Kolom :attribute minimal :min karakter.',
    ],

    'not_in' => 'Pilihan :attribute tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',

    'numeric' => 'Kolom :attribute harus berupa angka.',

    'password' => [
        'letters' => 'Kolom :attribute harus mengandung minimal satu huruf.',
        'numbers' => 'Kolom :attribute harus mengandung minimal satu angka.',
        'symbols' => 'Kolom :attribute harus mengandung minimal satu simbol.',
        'mixed' => 'Kolom :attribute harus memiliki huruf besar dan kecil.',
    ],

    'present' => 'Kolom :attribute harus ada.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => 'Kolom :attribute wajib diisi.',
    'required_if' => 'Kolom :attribute wajib diisi jika :other bernilai :value.',
    'same' => 'Kolom :attribute harus sama dengan :other.',

    'size' => [
        'array' => 'Kolom :attribute harus memiliki :size item.',
        'file' => 'Kolom :attribute harus berukuran :size kilobyte.',
        'numeric' => 'Kolom :attribute harus bernilai :size.',
        'string' => 'Kolom :attribute harus :size karakter.',
    ],

    'starts_with' => 'Kolom :attribute harus dimulai dengan salah satu dari: :values.',
    'string' => 'Kolom :attribute harus berupa teks.',
    'timezone' => 'Kolom :attribute harus berupa zona waktu yang valid.',
    'unique' => ':attribute sudah digunakan.',
    'uploaded' => 'Gagal mengunggah :attribute.',
    'uuid' => 'Kolom :attribute harus berupa UUID yang valid.',

    'attributes' => [],

    'custom' => [
        'role' => [
            'required' => 'Role wajib dipilih.',
            'string' => 'Role harus berupa string.',
            'in' => 'Role tidak valid.',
        ],
        'name' => [
            'required' => 'Nama wajib diisi.',
            'string' => 'Nama harus berupa string.',
            'min' => 'Nama minimal 5 karakter.',
            'max' => 'Nama maksimal 255 karakter.',
            'regex' => 'Nama tidak boleh mengandung simbol.',
        ],
        'email' => [
            'required' => 'Email wajib diisi.',
            'string' => 'Email harus berupa string.',
            'email' => 'Format email tidak valid.',
            'unique' => 'Email sudah digunakan.',
        ],
        'password' => [
            'required' => 'Password wajib diisi.',
            'min' => 'Password minimal 8 karakter.',
            'regex' => 'Password harus ada minimal 1 huruf kecil, 1 angka, dan simbol !@#$%',
        ],
        'confirmation' => [
            'required' => 'Password konfirmasi wajib diisi.',
            'same' => 'Password konfirmasi tidak cocok.',
        ],
    ],

];
