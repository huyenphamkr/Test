<?php


namespace App\Mail;


use App\Common\CommonConst;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class SendMailRetire extends Mailable
{
    use Queueable, SerializesModels;


    /**
     *
     * @var App\Models\InfoRetireUsers
     */
    public $tanto;
    public $staff;
    public $mode;
    public $url;
    public $hd;
    public $comment;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tanto,$staff,$mode,$url,$comment,$hd=null)
    {
        $this->tanto = $tanto;
        $this->staff = $staff;
        $this->mode = $mode;
        $this->url = $url;
        $this->comment = $comment;
        $this->hd = $hd;
    }
   
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->mode) {
            case '作成者To申請者':
                return $this->view('mails.retireTantoToStaff', ['tanto' => $this->tanto,'staff' => $this->staff,'url' => $this->url,'comment' => $this->comment])
                ->subject('退職手続きのお願い。')
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case '作成者ToHD':
                return $this->view('mails.retireTantoToHD', ['tanto' => $this->tanto,'staff' => $this->staff,'url' => $this->url,'comment' => $this->comment,'hd' => $this->hd])
                ->subject('新しい入職手続きがあります。'.$this->staff->name_office.'　'.$this->staff->name_belong.'　退職者：'.$this->staff->staff_name)
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;  
            case '1次To2次':
                return $this->view('mails.retireT1ToT2', ['tanto' => $this->tanto,'staff' => $this->staff,'url' => $this->url,'comment' => $this->comment,'second' => $this->hd])
                ->subject('退職手続きの2次承認依頼'.'　退職者：'.$this->staff->staff_name)
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case '1次To作成者':
                return $this->view('mails.retireT1ToAuthor', ['tanto' => $this->tanto,'staff' => $this->staff,'url' => $this->url,'comment' => $this->comment])
                ->subject('退職手続き1次承認：否認'.'　退職者：'.$this->staff->staff_name)
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case '2次ToHD':
                return $this->view('mails.retireT2ToHD', ['tanto' => $this->tanto,'staff' => $this->staff,'url' => $this->url,'comment' => $this->comment,'hd' => $this->hd])
                ->subject('退職手続きのHD承認依頼'.'　退職者：'.$this->staff->staff_name)
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case '2次To作成者':
                return $this->view('mails.retireT2ToAuthor', ['tanto' => $this->tanto,'staff' => $this->staff,'url' => $this->url,'comment' => $this->comment])
                ->subject('退職手続き2次承認：否認'.'　退職者：'.$this->staff->staff_name)
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case 'HDTo作成者':
                return $this->view('mails.retireHDToAuthor', ['tanto' => $this->tanto,'staff' => $this->staff,'url' => $this->url,'comment' => $this->comment])
                ->subject('退職手続き2次承認：否認'.'　退職者：'.$this->staff->staff_name)
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case 'HDAccept':
                return $this->view('mails.retireHDAccept', ['tanto' => $this->tanto,'staff' => $this->staff,'url' => $this->url,'comment' => $this->comment])
                ->subject('退職手続きHD承認：承認'.'　退職者：'.$this->staff->staff_name)
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;
            case '申請者To1次':
                return $this->view('mails.retireStaffToT1', ['tanto' => $this->tanto,'staff' => $this->staff,'url' => $this->url,'comment' => $this->comment,'one' => $this->hd])
                ->subject('退職手続きの1次承認依頼'.'　退職者：'.$this->staff->staff_name)
                ->bcc(env('MAIL_FROM_ADDRESS'));
                break;                        
        }                                                                                              
    }
}
