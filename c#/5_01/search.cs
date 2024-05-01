        public static DataTable F_HaitokinTsuchishoShokaiCond_SearchData(SqlWrapper dbCnn, SqlWrapper.Parameters paramsQuery)
        {
            //report chua xong cac cot trung va GUI report chua format, Check relationships of tables!
            StringBuilder query = new StringBuilder();
            query.AppendLine("SELECT DISTINCT MT.年度");
            query.AppendLine("     , MT.事業所コード");
            query.AppendLine("     , KM.住所コード");
            query.AppendLine("     , AD.住所名");
            query.AppendLine("     , MT.コースコード");
            query.AppendLine("     , KM.組合員カナ名");
            query.AppendLine("     , JS.事業所名");
            query.AppendLine("     , MT.組合員コード");
            query.AppendLine("     , KM.住所名1");
            query.AppendLine("     , KM.組合員名");  -> MT.組合員名
            query.AppendLine("     , KM.住所名2");
           // query.AppendLine("     , KMHM.組合員カナ名 AS KMHM組合員カナ名"); -> MT.班名
            query.AppendLine("     , AD.郵便番号");
            query.AppendLine("     , CASE ");
            query.AppendLine("         WHEN AD.郵便番号 IS NOT NULL AND substring(AD.郵便番号, (4), (7)) IS NOT NULL ");
            query.AppendLine("             THEN substring(AD.郵便番号, (1), (3)) +'-' + substring(AD.郵便番号, (4), (7)) ");
            query.AppendLine("         WHEN substring(AD.郵便番号, (4),(7)) IS NULL ");
            query.AppendLine("             THEN substring(AD.郵便番号, (1),(3)) ");
            query.AppendLine("      END AS 郵便番号 ");
            query.AppendLine("     , MT.出資金額1");
           // query.AppendLine("     , ADHM.住所名 AS  ADHM住所名");
            query.AppendLine("     , MT.配当率");
            query.AppendLine("     , KM.班コード");
            query.AppendLine("     , MT.配当金額");
            //query.AppendLine("     , '☆' AS 'M8-MARK'");
            query.AppendLine("     , CASE WHEN KM.班コード <> '999999' THEN '☆'+ MT.班名 ELSE MT.班名 END AS 班名");
            query.AppendLine("     , MT.税金");
            query.AppendLine("     , KM.電話番号");
            query.AppendLine("     , MT.差引額");
            query.AppendLine("     , MT.前年預り金");
            query.AppendLine("     , MT.出資金繰入金");
            query.AppendLine("     , MT.加入年月日");
            query.AppendLine("     , MT.今年度預り金");
            query.AppendLine("     , 0 AS お支払金額");
            query.AppendLine("     , KM.出資残_出資口数");
            query.AppendLine("     , '☆' AS 'M15-MARK'");

           // query.AppendLine("     , KMJS.組合員カナ名 AS KMJS組合員カナ名");

            query.AppendLine("     , KM.出資残_出資口数 * 100 AS 'M15-SYUSSI'");
            query.AppendLine("     , CASE WHEN KM.班コード <> '999999' ");
            query.AppendLine("         THEN HM.コースコード1 +'-' + HM.便区分1 + '-' + HM.配達順1 ");
            query.AppendLine("         ELSE '' END AS コース ");
            query.AppendLine("     , JS.事業所名");
            query.AppendLine("     , KM.出資残_預り金");
            query.AppendLine("     , MT.銀行名");
            query.AppendLine("     , MT.支店名");
            query.AppendLine("     , MT.種別");
            query.AppendLine("     , substring(MT.口座番号,(4),(4)) AS 口座番号"); 
            query.AppendLine("     , '**********' AS 通帳記号");
            query.AppendLine("     , '**********' AS 通帳番号");
            query.AppendLine("     , '**************' AS 通帳口座名");

            query.AppendLine("  FROM ").Append(ConstSSS.NameMaster.HaitokinTsuchishoShokai).Append(" MT");
            query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.KumiaiinMaster).Append(" KM");
            query.AppendLine("         ON MT.組合員コード = KM.組合員コード");

            query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.JigyoshoMaster).Append(" JS");
            query.AppendLine("         ON MT.事業所コード = JS.事業所コード");

            query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.JushoMaster).Append(" AD");
            query.AppendLine("         ON KM.住所コード = AD.住所コード");

            query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.HanMaster).Append(" HM");
            query.AppendLine("         ON MT.班コード = HM.班コード");

            query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.KosuMaster).Append(" KS");
            query.AppendLine("         ON MT.コースコード = KS.コースコード");

            //query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.JushoMaster).Append(" ADHM");
           // query.AppendLine("         ON HM.住所コード = ADHM.住所コード");

//sai -> xoa
           // query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.KumiaiinMaster).Append(" KMHM");
           // query.AppendLine("         ON HM.班コード = KMHM.班コード");

//đúng tên thứ 3
           // query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.KumiaiinMaster).Append(" KMJS");
           // query.AppendLine("         ON JS.事業所コード = KMJS.事業所コード");

            query.AppendLine(" WHERE 1 = 1"); 

            if (paramsQuery.ContainsKey(Const_HaitokinTsuchishoShokai_Params.組合員コード) && Parser.ToString(paramsQuery[Const_HaitokinTsuchishoShokai_Params.組合員コード].Value) != "")
                query.AppendLine("   AND MT.組合員コード = ").Append(Const_HaitokinTsuchishoShokai_Params.組合員コード);

            if (paramsQuery.ContainsKey(Const_HaitokinTsuchishoShokai_Params.年度) && Parser.ToString(paramsQuery[Const_HaitokinTsuchishoShokai_Params.年度].Value) != "")
                query.AppendLine("   AND MT.年度 = ").Append(Const_HaitokinTsuchishoShokai_Params.年度);

            if (paramsQuery.ContainsKey(Const_HaitokinTsuchishoShokai_Params.組合員名) && Parser.ToString(paramsQuery[Const_HaitokinTsuchishoShokai_Params.組合員名].Value).Trim('%') != "")
                query.AppendLine("   AND MT.組合員名 LIKE ").Append(Const_HaitokinTsuchishoShokai_Params.組合員名);

            if (paramsQuery.ContainsKey(Const_HaitokinTsuchishoShokai_Params.組合員カナ名) && Parser.ToString(paramsQuery[Const_HaitokinTsuchishoShokai_Params.組合員カナ名].Value).Trim('%') != "")
                query.AppendLine("   AND KM.組合員カナ名 LIKE ").Append(Const_HaitokinTsuchishoShokai_Params.組合員カナ名);

            if (paramsQuery.ContainsKey(Const_HaitokinTsuchishoShokai_Params.電話番号) && Parser.ToString(paramsQuery[Const_HaitokinTsuchishoShokai_Params.電話番号].Value).Trim('%') != "")
                query.AppendLine("   AND MT.電話番号 LIKE ").Append(Const_HaitokinTsuchishoShokai_Params.電話番号);

            if (paramsQuery.ContainsKey(Const_HaitokinTsuchishoShokai_Params.住所コード) && Parser.ToString(paramsQuery[Const_HaitokinTsuchishoShokai_Params.住所コード].Value) != "")
                query.AppendLine("   AND KM.住所コード = ").Append(Const_HaitokinTsuchishoShokai_Params.住所コード);

            if (paramsQuery.ContainsKey(Const_HaitokinTsuchishoShokai_Params.住所名) && Parser.ToString(paramsQuery[Const_HaitokinTsuchishoShokai_Params.住所名].Value).Trim('%') != "")
                query.AppendLine("   AND (KM.住所名1 + KM.住所名2) LIKE ").Append(Const_HaitokinTsuchishoShokai_Params.住所名);

            if (dbCnn != null)
            {
                DataTable dtb = new DataTable();
                dbCnn.ExecuteAdapterFill(query, paramsQuery, dtb);
                return dtb;
            }
            else
            {
                return SqlWrapper.ReadTable(query, paramsQuery);
            }
        }

        #endregion
/*

--------------------------------
tôi có các script tạo bảng như sau:
bảng chính: 配当金通知ハガキデータ
các bảng phụ: 組合員マスタ, 事業所マスタ, 住所マスタ, 班マスタ
tiềm các mối quan hệ giữa các bảng này ?

CREATE TABLE [dbo].[配当金通知ハガキデータ](
	[年度] [nvarchar](4) NOT NULL,
	[事業所コード] [nvarchar](2) NOT NULL,
	[組合員コード] [nvarchar](7) NOT NULL,
	[組合員名] [nvarchar](17) NOT NULL,
	[電話番号] [nvarchar](14) NULL,
	[住所1] [nvarchar](18) NULL,
	[住所2] [nvarchar](18) NULL,
	[住所3] [nvarchar](18) NULL,
	[郵便番号] [nvarchar](8) NULL,
	[班コード] [nvarchar](6) NULL,
	[班名] [nvarchar](10) NULL,
	[コース種別] [nvarchar](1) NULL,
	[コースコード] [nvarchar](3) NULL,
	[便区分] [nvarchar](1) NULL,
	[配達順] [nvarchar](3) NULL,
	[扱い] [nvarchar](10) NULL,
	[銀行名] [nvarchar](14) NULL,
	[支店名] [nvarchar](14) NULL,
	[種別] [nvarchar](14) NULL,
	[口座番号] [nvarchar](7) NULL,
	[口座名] [nvarchar](20) NULL,
	[通帳記号] [nvarchar](5) NULL,通帳記号
	[通帳番号] [nvarchar](8) NULL,
	[通帳口座名] [nvarchar](20) NULL,
	[出資金額1] [float] NULL,
	[配当率] [float] NULL,
	[配当金額] [float] NULL,
	[税金] [float] NULL,
	[差引額] [float] NULL,
	[前年預り金] [float] NULL,
	[出資金繰入金] [float] NULL,
	[今年度預り金] [float] NULL,
	[お支払金額] [float] NULL,
	[発行年月日] [date] NULL,
	[加入年月日] [date] NULL,
	[出資口数] [float] NULL,
	[出資金額2] [float] NULL,
	[出資預り金] [float] NULL,
	[作成日時] [datetime] NULL,
	[作成者コード] [nvarchar](6) NULL,
	[更新日時] [datetime] NULL,
	[更新者コード] [nvarchar](6) NULL,
 CONSTRAINT [PK_配当金通知ハガキデータ] PRIMARY KEY CLUSTERED 
(
	[年度] ASC,
	[事業所コード] ASC,
	[組合員コード] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [dbo].[組合員マスタ](
	[組合員コード] [nvarchar](7) NOT NULL,
	[組合員名] [nvarchar](17) NOT NULL,
	[組合員カナ名] [nvarchar](15) NULL,
	[班区分] [nvarchar](1) NULL,
	[班コード] [nvarchar](6) NULL,
	[生協コード] [nvarchar](2) NULL,
	[事業所グループ] [nvarchar](1) NULL,
	[事業所コード] [nvarchar](2) NULL,
	[ブロックコード] [nvarchar](4) NULL,
	[住所コード] [nvarchar](7) NULL,
	[住所名1] [nvarchar](20) NULL,
	[住所カナ名1] [nvarchar](20) NULL,
	[住所名2] [nvarchar](20) NULL,
	[住所カナ名2] [nvarchar](20) NULL,
	[電話番号] [nvarchar](12) NULL,
	[家族数] [int] NULL,
	[銀行扱い区分1] [nvarchar](1) NULL,
	[銀行扱い区分2] [nvarchar](1) NULL,
	[請求先銀行コード] [nvarchar](4) NULL,
	[請求先支店コード] [nvarchar](3) NULL,
	[請求先口座種別] [nvarchar](1) NULL,
	[請求先口座番号] [nvarchar](7) NULL,
	[請求先口座名] [nvarchar](20) NULL,
	[預年] [nvarchar](2) NULL,
	[現金配当振込先] [nvarchar](1) NULL,
	[振込先銀行コード] [nvarchar](4) NULL,
	[振込先支店コード] [nvarchar](3) NULL,
	[振込先口座種別] [nvarchar](1) NULL,
	[振込先口座番号] [nvarchar](8) NULL,
	[振込先口座名] [nvarchar](20) NULL,
	[最終利用企画] [char](8) NULL,
	[最終利用企画_年]  AS (substring([最終利用企画],(1),(4))),
	[最終利用企画_月]  AS (substring([最終利用企画],(5),(2))),
	[最終利用企画_回]  AS (substring([最終利用企画],(7),(1))),
	[最終利用企画_区分]  AS (substring([最終利用企画],(8),(1))),
	[LINE登録] [nvarchar](1) NULL,
	[申込年月日] [date] NULL,
	[生年月日] [date] NULL,
	[加入年月日] [date] NULL,
	[脱退年月日] [date] NULL,
	[脱退区分] [nvarchar](1) NULL,
	[脱退理由] [nvarchar](1) NULL,
	[出資金額] [float] NULL,
	[保有額] [float] NULL,
	[現金配当区分] [nvarchar](1) NULL,
	[おかず箱クラブ] [nvarchar](1) NULL,
	[共済区分] [nvarchar](1) NULL,
	[OCR停止回数] [nvarchar](1) NULL,
	[未注文回数] [nvarchar](1) NULL,
	[法人区分] [nvarchar](1) NULL,
	[幽霊区分] [nvarchar](1) NULL,
	[WEB区分] [nvarchar](1) NULL,
	[COOP委員] [nvarchar](1) NULL,
	[職域区分] [nvarchar](1) NULL,
	[債権分類区分] [nvarchar](1) NULL,
	[最終移動年月日] [date] NULL,
	[期首残_出資口数] [float] NULL,
	[期首残_預り金] [float] NULL,
	[期首残_出資金] [float] NULL,
	[出資残_出資口数] [float] NULL,
	[出資残_預り金] [float] NULL,
	[出資残_出資金] [float] NULL,
	[滞納DB移動年月] [nvarchar](4) NULL,
	[コース種別] [nvarchar](1) NULL,
	[コースコード] [nvarchar](3) NULL,
	[便区分] [nvarchar](1) NULL,
	[配達順] [nvarchar](3) NULL,
	[班長区分] [nvarchar](1) NULL,
	[当番区分_定番] [nvarchar](1) NULL,
	[当番区分_牛乳] [nvarchar](1) NULL,
	[増減回数] [int] NULL,
	[小学校区] [nvarchar](3) NULL,
	[愛知中央統合区分] [nvarchar](1) NULL,
	[作成日時] [datetime] NULL,
	[作成者コード] [nvarchar](6) NULL,
	[更新日時] [datetime] NULL,
	[更新者コード] [nvarchar](6) NULL,
 CONSTRAINT [PK_組合員マスタ] PRIMARY KEY CLUSTERED 
(
	[組合員コード] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [dbo].[事業所マスタ](
	[業所コード] [nvarchar](7) NOT NULL,
	[業所名] [nvarchar](15) NOT NULL,
	[業所カナ名] [nvarchar](30) NULL,
 CONSTRAINT [PK_事業所マスタ] PRIMARY KEY CLUSTERED 
(
	[業所コード] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [dbo].[住所マスタ](
	[住所コード] [nvarchar](7) NOT NULL,
	[住所名] [nvarchar](15) NOT NULL,
	[住所カナ名] [nvarchar](30) NULL,
	[郵便番号] [nvarchar](7) NULL,
	[ブロックコード] [nvarchar](4) NULL,
	[世帯数] [int] NULL,
	[人口] [int] NULL,
	[人口_男性] [int] NULL,
	[人口_女性] [int] NULL,
	[五十音] [nvarchar](1) NULL,
	[地区] [nvarchar](4) NULL,
	[作成日時] [datetime] NULL,
	[作成者コード] [nvarchar](6) NULL,
	[更新日時] [datetime] NULL,
	[更新者コード] [nvarchar](6) NULL,
 CONSTRAINT [PK_住所マスタ] PRIMARY KEY CLUSTERED 
(
	[住所コード] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [dbo].[班マスタ](
	[班コード] [nvarchar](6) NOT NULL,
	[班名] [nvarchar](10) NOT NULL,
	[班カナ名] [nvarchar](20) NULL,
	[班区分] [nvarchar](1) NULL,
	[生協コード] [nvarchar](2) NULL,
	[事業所グループ] [nvarchar](1) NULL,
	[事業所コード] [nvarchar](2) NULL,
	[ブロックコード] [nvarchar](4) NULL,
	[牛乳登録区分] [nvarchar](1) NULL,
	[地区] [nvarchar](4) NULL,
	[結成年月日] [date] NULL,
	[廃班年月日] [date] NULL,
	[廃班区分] [nvarchar](1) NULL,
	[班長] [nvarchar](7) NULL,
	[班人数] [int] NULL,
	[企画種別] [nvarchar](1) NULL,
	[コース種別1] [nvarchar](1) NULL,
	[コースコード1] [nvarchar](3) NULL,
	[便区分1] [nvarchar](1) NULL,
	[配達順1] [nvarchar](3) NULL,
	[コース種別2] [nvarchar](1) NULL,
	[コースコード2] [nvarchar](3) NULL,
	[便区分2] [nvarchar](1) NULL,
	[配達順2] [nvarchar](3) NULL,
	[個配区分] [nvarchar](1) NULL,
	[個配初回] [nvarchar](1) NULL,
	[割戻区分] [nvarchar](1) NULL,
	[住所コード] [nvarchar](7) NULL,
	[電話番号] [nvarchar](12) NULL,
	[牛乳当番] [nvarchar](7) NULL,
	[連絡先] [nvarchar](15) NULL,
	[連絡TEL] [nvarchar](12) NULL,
	[拠点コード] [nvarchar](4) NULL,
	[愛知中央統合区分] [nvarchar](1) NULL,
	[作成日時] [datetime] NULL,
	[作成者コード] [nvarchar](6) NULL,
	[更新日時] [datetime] NULL,
	[更新者コード] [nvarchar](6) NULL,
 CONSTRAINT [PK_班マスタ] PRIMARY KEY CLUSTERED 
(
	[班コード] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

*/