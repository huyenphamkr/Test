public function handl($rows) {
        $data = [];
        foreach ($rows as $key => $row) {
            $infoUser = InfoUsers::where('users_number', str_pad($row[0], 7, '0', STR_PAD_LEFT))->first();
            $gender = MstClass::where("item_type", CommonConst::GENDER)->where('item_name', $row[3].'性')->value('item_cd');
            if (empty($infoUser)) {
                continue;
            }
            $data[] = [
                        'info_users_cd' => $infoUser->id,
                        'ver_his' => CommonConst::VER_HIS,
                        'name' => $row[1] ?? '',
                        'furigana' => $row[2] ?? '',
                        'gender' => $gender ?? 3,
                        'relationship' => $row[4] ?? '',
                        'birthday' => $row[5],
                        'career' => $row[6] ?? '',
                        'my_number' => $row[7] ?? ''
                    ];
                    $infoUser->dependent_number = (int) $infoUser->dependent_number + 1;
                    $infoUser->save();
            
        }
       DependentUsers::insert($data);
    }