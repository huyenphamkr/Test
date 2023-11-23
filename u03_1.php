
function saveInformation($data)
    {
        DB::beginTransaction();
        try {
            $id = $data['id'];
            $proItem = $this->getListCheckbox($data,'prod');
            $upOffi = $this->getListCheckbox($data,'up_offi');
            $dataToSave = [
                'date_join' => $data['txtDate_join'],
                'first_name' => $data['txtFirstName'],
                'last_name' => $data['txtLastName'],
                'first_furigana' => $data['txtFirstFurigana'],
                'last_furigana' => $data['txtLastFurigana'],
                'office_cd' => $data['cmbOffice_cd'],
                'belong_cd' => $data['cmbBelong_cd'],
                'position' => $data['txtPosition'],
                'work_time_flag' => $data['cmbWork_time_flag'],
                'procedure_items' => $proItem,
                'company_mobile_number' => $data['txtCompany_mobile_number'],
                'manager' => $data['manager'] ?? '',
                'manage_position' => $data['manage_position'] ?? '',
                'flg1' => $data['flg1'] ?? '',
                'manage_business' => $data['manage_business'] ?? '',
                'accountant' => $data['accountant'] ?? '',
                'company_car' => $data['company_car'] ?? '',
                'flg3' => $data['flg3'] ?? '',
                'flg4' => $data['flg4'] ?? '',
                'flg5' => $data['flg5'] ?? '',
                'flg6' => $data['flg6'] ?? '',
                'flg7' => $data['flg7'] ?? '',
                'flg8' => $data['flg8'] ?? '',
                'flg9' => $data['flg9'] ?? '',
                'sign_items' => $upOffi,  
                'email' => $data['email'],
                'confirm_email' => $data['confirm_email'],
                'note' => $data['txtNote'],
            ];
            if (!empty($data['mode'])) {
                switch ($data['mode']) {
                    case 'author':
                        $dataToSave['author_id'] =  Auth::id();
                        $dataToSave['author_date'] = now();
                        $dataToSave['status_user'] = CommonConst::STATUS_CREATOR;
                        break;
                    case 'admin1':
                        $dataToSave['admin1_id'] =  Auth::id();
                        $dataToSave['admin1_date'] = now();
                        $dataToSave['status_user'] = CommonConst::STATUS_APPROVAL_2;
                        break;
                    case 'admin2':
                        $dataToSave['admin2_id'] =  Auth::id();
                        $dataToSave['admin2_date'] = now();
                        $dataToSave['status_user'] = CommonConst::STATUS_APPROVAL_HD;
                        break;
                    case 'hd':
                        $dataToSave['hd_id'] =  Auth::id();
                        $dataToSave['hd_date'] = now();
                        $dataToSave['status_user'] = CommonConst::STATUS_COMPLETED;
                        break;
                    case ('admin1Reject' || 'admin2Reject' ||'hdReject'):
                        $dataToSave['status_user'] = CommonConst::STATUS_REJECT;
                        break;
                }
            }


            if (empty($id)) $id = -1;


            if ($id > 0) {
                // update
                $info = InfoUsers::find($id);
                $dataToSave['users_number'] = $data['users_number'];
                $dataToSave['revision'] = (int)$info->revision + 1;
                $dataToSave['updated_id'] = Auth::id();
            } else {
                //insert
                $dataToSave['created_id'] = Auth::id();


                //create users_number auto max + 1
                $userNumMax = $this->getUsersNumberMax();
                $userNumMax = str_pad((int)ltrim($userNumMax->users_number, '0') + 1, 7, '0', STR_PAD_LEFT);
                $dataToSave['users_number'] = (string)$userNumMax;
            }
            $newInfo = InfoUsers::updateOrCreate(['id' => $id], $dataToSave);
            // $newInfo->save();
            $id = $newInfo->id;
            if($newInfo)
            {
                if(!empty($data['mode']))
                {
                    switch ($data['mode']) {
                        case 'author':
                            $user = $this->getInfoUserByKey($id);
                            //send mail
                           // Mail::to($data['email'])->send(new SendMail($user,'creator1'));
                            //Send mail Manage
                           // Mail::to($data['email'])->send(new SendMail($user,'creator2'));
                            break;
                        case 'admin1':
                            $user = $this->getSecondApproval();
                            //send app 2 
                           // Mail::to($data['email_app2'])->send(new SendMail($user,'approval1'));
                            break;
                        case 'admin1Reject':
                            $user = $this->getInfoUserByKey($id);
                            //send author
                            // Mail::to($data['email'])->send(new SendMail($user,'reject1'));
                            break;
                        case 'admin2':
                            // $user = $this->getSecondApproval();
                            //send hd manager v.v
                            // Mail::to($data['email_app2'])->send(new SendMail($user,'app2'));
                            break;
                        case 'admin2Reject':
                            $user = $this->getSecondApproval();
                            //send author 
                            // Mail::to($data['email_app2'])->send(new SendMail($user,'reject2'));
                            break;
                        case 'hd':
                            //insert users
                            $result = $this->registerUser($data);
                            if($result)
                            {
                                $user = $this->getInfoUserByKey($id,true);
                                //send mail ----- chua lay thong tin users bang users
                                //Mail::to($data['email'])->send(new HDEmployProcedures($user,CommonConst::INITIAL_PASSWORD));
                            }
                            break;
                    }
                }
            }
            //file
           // $this->registerFile($data);
            DB::commit();
            //UploadFunc::delTmpFolder();
            // return $newInfo->id;
        } catch (\Exception $e) {
            DB::rollback();
           // UploadFunc::delTmpFolder();
            throw $e;
        }
    }


    function getListCheckbox($data, $name) {
        if(empty($data[$name])) return;
        $result = '';
        foreach($data[$name] as $item){
            if(!empty($data[$name.'_'.$item]) && $data[$name.'_'.$item] != 0)
                $result .= $item.',';
        }
        return rtrim($result, ",");
    }
   
    public function getInfoUserByKey($id,$isHd=false)
    {
        $query = DB::table('info_users as info')
            ->leftJoin('users as au', 'au.id', '=', 'info.author_id')
            ->leftJoin('mst_offices as o', 'o.office_cd', '=', 'info.office_cd')
            ->leftJoin('mst_belong as bl', 'bl.belong_cd', '=', 'info.belong_cd')
            ->where('info.id', $id);
        if($isHd)
        {
            $query->leftJoin('users as u', 'u.info_users_cd', '=', 'info.id')
                ->select('u.id as userId',
                    'info.*',
                    'au.name as name_author',
                    'o.office_name as name_office',
                    'bl.belong_name as name_belong',
            );
        }
        else
        {
            $query->select(
                'info.*',
                'au.name as name_author',
                'o.office_name as name_office',
                'bl.belong_name as name_belong',
            );
        }
        return $query->first();
    }

//get all email and name App 2 
    public function getSecondApproval(){
        $query = DB::table('info_users as info')
            ->leftJoin('mst_structure as s', function ($join) {
                $join->on('s.belong_cd', '=', 'info.belong_cd')
                        ->on('s.office_cd', '=', 'info.office_cd');
            })
            ->leftJoin('mst_employment_structure as emply', 's.id', '=', 'emply.structure_id')
            ->leftJoin('users as u', 'u.id', '=', 'emply.staff_id')
            ->where('emply.type','=','2')
            ->where('info.office_cd','=','1')->where('info.belong_cd','=','1')
            ->select(
                // 'info.*',
                // 'info.first_name',
                // 'info.last_name',
                'u.email as email_app2',
                'u.name as name_app2',
                )
            ->distinct();
        return $query->get();
    }

    function registerUser($data){
        $dataToSave = [
            'name' => $data['txtFirstName'].' '.$data['txtLastName'],
            'info_users_cd' => $data['id'],
            'email' => $data['email'],
            'password' => bcrypt(CommonConst::INITIAL_PASSWORD),
            'authority' => '3',
            'created_at' => now(),
            // 'created_id' => Auth::id(),
            'updated_at' => now(),
            // 'updated_id' => Auth::id(),
        ];
        $result = User::updateOrCreate($dataToSave);
        return $result->id;
    }
-----------------------------------------------------------------------------------------------
public function build()
    {
        switch ($this->mode) {
            case 'creator1':
                return $this->view('mails.creator', ['user' => $this->user])
                ->subject(CommonConst::MAIL_SUBJ_EMP_PROD.'（['.$this->user->name_office.']　['.$this->user->name_author.'])')
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case 'creator2':
                return $this->view('mails.manage', ['user' => $this->user])
                ->subject('新しい入職手続きがあります。['.$this->user->name_office.']　['.$this->user->name_belong.']　入職者：['.$this->user->first_name.'　'.$this->user->last_name.']')
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case 'app1':
                return $this->view('mails.approval1', ['user' => $this->user])
                ->subject('入職手続きの2次承認依頼　入職者：['.$this->user->first_name.'　'.$this->user->last_name.']')
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case 'app2':
                return $this->view('mails.approval2', ['user' => $this->user])
                ->subject('入職手続きのHD承認依頼　入職者：['.$this->user->first_name.'　'.$this->user->last_name.']')
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case 'appHD':
                return $this->view('mails.approvalHD', ['user' => $this->user])
                ->subject('入職手続きのHD承認依頼　入職者：['.$this->user->first_name.'　'.$this->user->last_name.']')
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case 'reject1':
                return $this->view('mails.reject1', ['user' => $this->user])
                ->subject('入職手続き1次承認：否認　入職者:['.$this->user->first_name.'　'.$this->user->last_name.']')
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case 'reject2':
                return $this->view('mails.reject2', ['user' => $this->user])
                ->subject('入職手続きのお願い（['.$this->user->name_office.'　'.$this->user->name_author.']')
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case 'rejectHD':
                return $this->view('mails.rejectHD', ['user' => $this->user])
                ->subject('入職手続きHD承認：否認　入職者：['.$this->user->first_name.'　'.$this->user->last_name.']')
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
        }
       
    }
------------------------------------------------------reject1-------------------------------------------------------------------
<div style="width:700px;">
    <div style="text-align:left;">
        <p>{{$user->first_name_author}} {{$user->last_name_author}}様</p>
        <p>{{$user->first_name}} {{$user->last_name}}さんの入職手続きが1次承認者に否認されました。</p>
        <p>以下のボタンから内容の確認と再申請をお願いします。</p>
        <div style="text-align:center; margin-bottom: 25px">
            <a style="
        text-decoration: none;
        background-color: #01008a;
        color: white;
        padding: 10px 20px 10px 20px;
        border : 1px;
        border-color: #01008a;
        border-radius: 8px;
        font-weight: bold" href="{{route('u03.showInfo')}}">開く画面</a>
        </div>
        <p>コメント：{{$user->note}}<br>																								
            よろしくお願いいたします。</p>
    </div>
</div>
--------------------------------------------------request-------------------------------------------------------------------
if($this->has('txtLastName')){
            $validate['users_number'] = 'nullable|max:7|unique:info_users,users_number,' . $this->id . ',id,office_cd,'.$this->input('cmbOffice_cd');
            if($this->input('hidStatus') != CommonConst::STATUS_CREATOR && $this->input('hidStatus') != '')
            {
                $validate['txtConditions'] = 'required';
                $validate['txtCollectionFees'] = 'required';
                $validate['txtContract'] = 'required';
            }
            $validate['txtDate_join'] = 'required';
            $validate['txtFirstName'] = 'required|max:30';
            $validate['txtLastName'] = 'required|max:30';
            $validate['txtFirstFurigana'] = 'required|max:30';
            $validate['txtLastFurigana'] = 'required|max:30';
            $validate['cmbOffice_cd'] = 'required';
            $validate['cmbBelong_cd'] = 'required';
            $validate['txtPosition'] = 'max:30';
            $validate['cmbWork_time_flag'] = 'required';
            $validate['txtResume'] = 'required';
            $validate['email'] = 'required|email|required_with:confirm_email|same:confirm_email|max:25';
            $validate['confirm_email'] = 'required|email|max:25';
            $validate['txtNote'] = 'required|max:500';

        }
-----------------------------------------------------------------------------------------------------------------------------------------------------
