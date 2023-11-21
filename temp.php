        $validate['users_number'] = 'required|unique:info_users,users_number,' . $this->id . ',id,office_cd,'.$this->input('cmbOffice_cd');
        $validate['txtDate_join'] = 'required|max:';
        $validate['txtName'] = 'required|max:';
        $validate['txtFurigana'] = 'required|max:';
        $validate['cmbOffice_cd'] = 'required|max:';
        $validate['cmbBelong_cd'] = 'required|max:';
        $validate['cmbWork_time_flag'] = 'required|max:';
        $validate['cmbWork_time_flag'] = 'required|max:';
        $validate['users_number'] = 'required|max:';
        $validate['email'] = 'required|max:';
        $validate['confirm_email'] = 'required|max:';
----------------------------------------------------------------------------------------------------------------------------
