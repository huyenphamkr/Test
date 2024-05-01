1①作成者送信

<div style="width:700px;">
    <div style="text-align:left;">
        <p>{{$user->first_name}} {{$user->last_name}}様</p>
        <p>{{$user->name_office}}　{{$user->name_author}}からユーザー登録のご依頼がありました。</p>
        <p>下記のＵＲＬにアクセスしてユーザー登録をお願いいたします。</p>
        <div style="text-align:center; margin-bottom: 25px">
            <a style="
        text-decoration: none;
        background-color: #01008a;
        color: white;
        padding: 10px 20px 10px 20px;
        border : 1px;
        border-color: #01008a;
        border-radius: 8px;
        font-weight: bold" href="{{ $url }}">開く画面</a>
        </div>
        <p>依頼主名称：株式会社ｉ＆ｆホールディングス<br>
            ※本メールはシステムによる自動配信です。<br>
            ※Fromのメールアドレスには返信できません。</p>
    </div>
</div>
-------------------------------------------------------------------------
2 ②HD所属の管理職かつ労務担当に対して以下のメールを送ってください。

title: 新しい入職手続きがあります。{{$user->name_office}}　{{$user->name_belong}} 　入職者：{{$user->first_name}} {{$user->last_name}}
body:
<div style="width:700px;">
    <div style="text-align:left;">
      <p>新しい入職手続きが作成されました。</p>
      <p>以下のボタンから内容の確認をお願いします。</p>
      <br>
      <div style="text-align:center; margin-bottom: 25px">
            <a style="
        text-decoration: none;
        background-color: #01008a;
        color: white;
        padding: 10px 20px 10px 20px;
        border : 1px;
        border-color: #01008a;
        border-radius: 8px;
        font-weight: bold" href="{{ $url-u03-1 }}">開く画面</a>
        </div>
      <br>
      <p>事業所名：{{$user->name_office}}<br>					
			   所属名：{{$user->name_belong}}<br>															
				 入職者氏名：{{$user->first_name}} {{$user->last_name}}	<br>												
				 コメント：{{$user->note}}<br>																											
					<br>																													
				 よろしくお願いいたします。</p>
      </div>
</div>

-------------------------------------------------------------------------
①2次承認者に以下のメールを送ってください	

title:
入職手続きの2次承認依頼　入職者：{{$user->first_name}} {{$user->last_name}}
body:

-------------------------------------------------------------------------
-------------------------------------------------------------------------
-------------------------------------------------------------------------
-------------------------------------------------------------------------

