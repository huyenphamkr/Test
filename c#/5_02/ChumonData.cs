using System;
using System.Text;
using System.Data;

namespace TN.SSS
{
    public class ChumonData
    {
        #region Classes

        public class Const_ChumonData_Params
        {
            public const string ID = "@ID";
            public const string レコード区分 = "@レコード区分";
            public const string 生協コード = "@生協コード";
            public const string 支部グループ = "@支部グループ";
            public const string 支部コード = "@支部コード";
            public const string 入力区分 = "@入力区分";
            public const string 法人区分 = "@法人区分";
            public const string ブロックコード = "@ブロックコード";
            public const string 班区分 = "@班区分";
            public const string 班コード = "@班コード";
            public const string 個配 = "@個配";
            public const string 自振区分 = "@自振区分";
            public const string 企画種別 = "@企画種別";
            public const string コース種別 = "@コース種別";
            public const string コースコード = "@コースコード";
            public const string 便区分 = "@便区分";
            public const string 配達順 = "@配達順";
            public const string 販促不可フラグ = "@販促不可フラグ";
            public const string 組合員コード = "@組合員コード";
            public const string 組合員名 = "@組合員名";
            public const string 班名 = "@班名";
            public const string MIXMATCH区分 = "@MIXMATCH区分";
            public const string 商品コード = "@商品コード";
            public const string 数量 = "@数量";
            public const string 注文No = "@注文No";
            public const string 処理フラグ = "@処理フラグ";
            public const string 税区分 = "@税区分";
            public const string 配達企画 = "@配達企画";
            public const string 配達企画_年 = "@配達企画_年";
            public const string 配達企画_月 = "@配達企画_月";
            public const string 配達企画_回 = "@配達企画_回";
            public const string 配達企画_区分 = "@配達企画_区分";
            public const string 申込企画 = "@申込企画";
            public const string 申込企画_年 = "@申込企画_年";
            public const string 申込企画_月 = "@申込企画_月";
            public const string 申込企画_回 = "@申込企画_回";
            public const string 申込企画_区分 = "@申込企画_区分";
            public const string SEQ = "@SEQ";
            public const string 配送料除外 = "@配送料除外";
            public const string 軽減税率区分 = "@軽減税率区分";
            public const string 作成日時 = "@作成日時";
            public const string 作成者コード = "@作成者コード";
            public const string 更新日時 = "@更新日時";
            public const string 更新者コード = "@更新者コード";

            //Extensions
            //↓↓↓----- F_ChumonDataCond -----↓↓↓
            //public const string 更新日時_From = "@更新日時_From";
            //public const string 更新日時_To = "@更新日時_To";
            //↑↑↑----- F_ChumonDataCond -----↑↑↑

        }

        #endregion

        #region Properties

        //ID 
        public int ChumonCd { get; set; }
        //レコード区分 
        public string RekodoKbn { get; set; }
        //生協コード 
        public string SeikyoCd { get; set; }
        //支部グループ 
        public string ShibuGrp { get; set; }
        //支部コード 
        public string ShibuCd { get; set; }
        //入力区分 
        public string NyuryokuKbn { get; set; }
        //法人区分 
        public string HojinKbn { get; set; }
        //ブロックコード 
        public string BurokkuCd { get; set; }
        //班区分 
        public string HanKbn { get; set; }
        //班コード 
        public string HanCd { get; set; }
        //個配 
        public string Kohai { get; set; }
        //自振区分 
        public string JifuriKbn { get; set; }
        //企画種別 
        public string KikakuShubetsu { get; set; }
        //コース種別 
        public string KosuShubetsu { get; set; }
        //コースコード 
        public string KosuCd { get; set; }
        //便区分 
        public string BenKbn { get; set; }
        //配達順 
        public string HaitatsuJun { get; set; }
        //販促不可フラグ 
        public string HansokufukaFlg { get; set; }
        //組合員コード 
        public string KumiaiinCd { get; set; }
        //組合員名 
        public string KumiaiinNm { get; set; }
        //班名 
        public string HanNm { get; set; }
        //MIXMATCH区分 
        public string MIXMATCHKbn { get; set; }
        //商品コード 
        public string ShohinCd { get; set; }
        //数量 
        public int Suryo { get; set; }
        //注文No 
        public string ChumonNo { get; set; }
        //処理フラグ 
        public string ShoriFlg { get; set; }
        //税区分 
        public string ZeiKbn { get; set; }
        //配達企画 
        public string HaitatsuKikaku { get; set; }
        //配達企画_年 
        public string HaitatsuKikakuY { get; set; }
        //配達企画_月 
        public string HaitatsuKikakuM { get; set; }
        //配達企画_回 
        public string HaitatsuKikakuW { get; set; }
        //配達企画_区分 
        public string HaitatsuKikakuKbn { get; set; }
        //申込企画 
        public string MoshikomiKikaku { get; set; }
        //申込企画_年 
        public string MoshikomiKikakuY { get; set; }
        //申込企画_月 
        public string MoshikomiKikakuM { get; set; }
        //申込企画_回 
        public string MoshikomiKikakuW { get; set; }
        //申込企画_区分 
        public string MoshikomiKikakuKbn { get; set; }
        //SEQ 
        public string ChumonSEQ { get; set; }
        //配送料除外 
        public string HaisoryoJogai { get; set; }
        //軽減税率区分 
        public string KeigenZeiritsuKbn { get; set; }
        //作成日時 
        public DateTime? CreateDate { get; set; }
        //作成者コード 
        public string CreateUserCd { get; set; }
        //更新日時 
        public DateTime? UpdateDate { get; set; }
        //更新者コード 
        public string UpdateUserCd { get; set; }


        #endregion
        /*
        #region Methods

        /// <summary>
        /// インスタンスを取得する。
        /// </summary>
        /// <param name="hanCd">班コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static ChumonData GetInstance(string hanCd)
        {
            return GetInstance(null, hanCd);
        }

        /// <summary>
        /// インスタンスを取得する。
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="hanCd">班コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static ChumonData GetInstance(SqlWrapper dbCnn, string hanCd)
        {
            DataRow row = GetRow(dbCnn, hanCd);
            if (row == null)
            {
                return null;
            }
            ChumonData han = new ChumonData();
            han.HanCd = Parser.ToString(row["班コード"]);
            han.HanNm = Parser.ToString(row["班名"]);
            han.HanKanaNm = Parser.ToString(row["班カナ名"]);
            han.HanKbn = Parser.ToString(row["班区分"]);
            han.SeikyoCd = Parser.ToString(row["生協コード"]);
            han.JigyoshoGrp = Parser.ToString(row["事業所グループ"]);
            han.JigyoshoCd = Parser.ToString(row["事業所コード"]);
            han.BlockCd = Parser.ToString(row["ブロックコード"]);
            han.GyunyuTorokuKbn = Parser.ToString(row["牛乳登録区分"]);
            han.Chiku = Parser.ToString(row["地区"]);
            han.KesseiYmd = Parser.ToNullableDate(row["結成年月日"]);
            han.HaiHanYmd = Parser.ToNullableDate(row["廃班年月日"]);
            han.HaiHanKbn = Parser.ToString(row["廃班区分"]);
            han.HanchoCd = Parser.ToString(row["班長"]);
            han.HanNinzu = Parser.ToString(row["班人数"]);
            han.KikakuShubetsu = Parser.ToString(row["企画種別"]);
            han.KosuShubetsu1 = Parser.ToString(row["コース種別1"]);
            han.KosuCd1 = Parser.ToString(row["コースコード1"]);
            han.BenKbn1 = Parser.ToString(row["便区分1"]);
            han.HaitatsuJun1 = Parser.ToString(row["配達順1"]);
            han.KosuShubetsu2 = Parser.ToString(row["コース種別2"]);
            han.KosuCd2 = Parser.ToString(row["コースコード2"]);
            han.BenKbn2 = Parser.ToString(row["便区分2"]);
            han.HaitatsuJun2 = Parser.ToString(row["配達順2"]);
            han.KohaiKbn = Parser.ToString(row["個配区分"]);
            han.KohaiShokai = Parser.ToString(row["個配初回"]);
            han.WarimodoshiKbn = Parser.ToString(row["割戻区分"]);
            han.JushoCd = Parser.ToString(row["住所コード"]);
            han.Tel = Parser.ToString(row["電話番号"]);
            han.GyunyuToban = Parser.ToString(row["牛乳当番"]);
            han.RenrakuSaki = Parser.ToString(row["連絡先"]);
            han.RenrakuTel = Parser.ToString(row["連絡TEL"]);
            han.KyotenCd = Parser.ToString(row["拠点コード"]);
            han.AichiChuoTogoKbn = Parser.ToString(row["愛知中央統合区分"]);

            han.TableDataMeisai = HanMeisaiMaster.GetTableDetailByKey(dbCnn, han.HanCd);

            han.CreateDate = Parser.ToNullableDate(row["作成日時"]);
            han.CreateUserCd = Parser.ToString(row["作成者コード"]);
            han.CreateUserNm = Parser.ToString(row["作成者名"]);
            han.UpdateDate = Parser.ToNullableDate(row["更新日時"]);
            han.UpdateUserCd = Parser.ToString(row["更新者コード"]);
            han.UpdateUserNm = Parser.ToString(row["更新者名"]);
            return han;
        }

        #endregion

        #region Select Methods

        /// <summary>
        /// レコード有無の検証
        /// </summary>
        /// <param name="hanCd">班コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static bool IsExists(string hanCd)
        {
            return IsExists(null, hanCd);
        }

        /// <summary>
        /// レコード有無の検証
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="hanCd">班コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static bool IsExists(SqlWrapper dbCnn, string hanCd)
        {
            StringBuilder query = new StringBuilder();
            query.AppendLine("SELECT COUNT(*)");
            query.AppendLine("  FROM ").Append(ConstSSS.NameMaster.ChumonData);
            query.AppendLine(" WHERE 班コード = ").Append(Const_Han_Params.班コード);
            SqlWrapper.Parameters paramsQuery = new SqlWrapper.Parameters
            {
                { Const_Han_Params.班コード, hanCd }
            };

            if (dbCnn != null)
            {
                return Parser.DBCountToBoolean(dbCnn.ExecuteScalar(query, paramsQuery));
            }
            else
            {
                return Parser.DBCountToBoolean(SqlWrapper.ReadOne(query, paramsQuery));
            }
        }

        /// <summary>
        /// レコードを取得する。
        /// </summary>
        /// <param name="hanCd">班コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static DataRow GetRow(string hanCd)
        {
            return GetRow(null, hanCd);
        }

        /// <summary>
        /// レコードを取得する。
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="hanCd">班コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static DataRow GetRow(SqlWrapper dbCnn, string hanCd)
        {
            StringBuilder query = new StringBuilder();
            query.AppendLine("SELECT MT.*");
            query.AppendLine("     , U1.従業員名 AS 作成者名");
            query.AppendLine("     , U2.従業員名 AS 更新者名");
            query.AppendLine("  FROM ").Append(ConstSSS.NameMaster.ChumonData).Append(" MT");
            query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.JugyoinMaster).Append(" U1 ON MT.作成者コード = U1.従業員コード");
            query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.JugyoinMaster).Append(" U2 ON MT.更新者コード = U2.従業員コード");
            query.AppendLine(" WHERE MT.班コード = ").Append(Const_Han_Params.班コード);
            SqlWrapper.Parameters paramsQuery = new SqlWrapper.Parameters
            {
                { Const_Han_Params.班コード, hanCd }
            };

            if (dbCnn != null)
            {
                return dbCnn.ExecuteScalarRow(query, paramsQuery);
            }
            else
            {
                return SqlWrapper.ReadRow(query, paramsQuery);
            }
        }

        public static DataTable F_ChumonDataCond_SearchData(SqlWrapper dbCnn, SqlWrapper.Parameters paramsQuery)
        {
            StringBuilder query = new StringBuilder();
            query.AppendLine(" SELECT U1.*");
            query.AppendLine("   FROM ").Append(ConstSSS.NameMaster.ChumonData).Append(" U1");
            query.AppendLine("  WHERE 1 = 1");

            if (paramsQuery.ContainsKey(Const_Han_Params.班コード) && Parser.ToString(paramsQuery[Const_Han_Params.班コード].Value) != "")
                query.AppendLine("   AND U1.班コード = ").Append(Const_Han_Params.班コード);

            if (paramsQuery.ContainsKey(Const_Han_Params.班名) && Parser.ToString(paramsQuery[Const_Han_Params.班名].Value).Trim('%') != "")
                query.AppendLine("   AND U1.班名 LIKE ").Append(Const_Han_Params.班名);

            if (paramsQuery.ContainsKey(Const_Han_Params.班カナ名) && Parser.ToString(paramsQuery[Const_Han_Params.班カナ名].Value).Trim('%') != "")
                query.AppendLine("   AND U1.班カナ名 LIKE ").Append(Const_Han_Params.班カナ名);

            if (paramsQuery.ContainsKey(Const_Han_Params.事業所コード) && Parser.ToString(paramsQuery[Const_Han_Params.事業所コード].Value) != "")
                query.AppendLine("   AND U1.事業所コード = ").Append(Const_Han_Params.事業所コード);

            if (paramsQuery.ContainsKey(Const_Han_Params.ブロックコード) && Parser.ToString(paramsQuery[Const_Han_Params.ブロックコード].Value) != "")
                query.AppendLine("   AND U1.ブロックコード = ").Append(Const_Han_Params.ブロックコード);

            if (paramsQuery.ContainsKey(Const_Han_Params.更新日時_From) && Parser.ToString(paramsQuery[Const_Han_Params.更新日時_From].Value) != "")
                query.AppendLine("   AND FORMAT(U1.更新日時, 'yyyy/MM/dd') >= ").Append(Const_Han_Params.更新日時_From);

            if (paramsQuery.ContainsKey(Const_Han_Params.更新日時_To) && Parser.ToString(paramsQuery[Const_Han_Params.更新日時_To].Value) != "")
                query.AppendLine("   AND FORMAT(U1.更新日時, 'yyyy/MM/dd') <= ").Append(Const_Han_Params.更新日時_To);

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

        #region Insert, Update, Delete Methods

        /// <summary>
        /// レコード挿入
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="jugyoin">ログイン情報</param>
        /// <remarks></remarks>
        public int InsertRow(SqlWrapper dbCnn, JugyoinMaster jugyoin)
        {
            int intRetrurn = ConstSSS.intDbUpdErr;

            StringBuilder query = new StringBuilder();
            query.AppendLine("INSERT INTO ").Append(ConstSSS.NameMaster.ChumonData).Append(" WITH (TABLOCK) (");
            query.AppendLine("       班コード");
            query.AppendLine("     , 班名");
            query.AppendLine("     , 班カナ名");
            query.AppendLine("     , 班区分");
            query.AppendLine("     , 生協コード");
            query.AppendLine("     , 事業所グループ");
            query.AppendLine("     , 事業所コード");
            query.AppendLine("     , ブロックコード");
            query.AppendLine("     , 牛乳登録区分");
            query.AppendLine("     , 地区");
            query.AppendLine("     , 結成年月日");
            query.AppendLine("     , 廃班年月日");
            query.AppendLine("     , 廃班区分");
            query.AppendLine("     , 班長");
            query.AppendLine("     , 班人数");
            query.AppendLine("     , 企画種別");
            query.AppendLine("     , コース種別1");
            query.AppendLine("     , コースコード1");
            query.AppendLine("     , 便区分1");
            query.AppendLine("     , 配達順1");
            query.AppendLine("     , コース種別2");
            query.AppendLine("     , コースコード2");
            query.AppendLine("     , 便区分2");
            query.AppendLine("     , 配達順2");
            query.AppendLine("     , 個配区分");
            query.AppendLine("     , 個配初回");
            query.AppendLine("     , 割戻区分");
            query.AppendLine("     , 住所コード");
            query.AppendLine("     , 電話番号");
            query.AppendLine("     , 牛乳当番");
            query.AppendLine("     , 連絡先");
            query.AppendLine("     , 連絡TEL");
            query.AppendLine("     , 拠点コード");
            query.AppendLine("     , 愛知中央統合区分");
            query.AppendLine("     , 作成日時");
            query.AppendLine("     , 作成者コード");
            query.AppendLine("     , 更新日時");
            query.AppendLine("     , 更新者コード");
            query.AppendLine("     ) VALUES (");
            query.AppendLine("       ").Append(Const_Han_Params.班コード);
            query.AppendLine("     , ").Append(Const_Han_Params.班名);
            query.AppendLine("     , ").Append(Const_Han_Params.班カナ名);
            query.AppendLine("     , ").Append(Const_Han_Params.班区分);
            query.AppendLine("     , ").Append(Const_Han_Params.生協コード);
            query.AppendLine("     , ").Append(Const_Han_Params.事業所グループ);
            query.AppendLine("     , ").Append(Const_Han_Params.事業所コード);
            query.AppendLine("     , ").Append(Const_Han_Params.ブロックコード);
            query.AppendLine("     , ").Append(Const_Han_Params.牛乳登録区分);
            query.AppendLine("     , ").Append(Const_Han_Params.地区);
            query.AppendLine("     , ").Append(Const_Han_Params.結成年月日);
            query.AppendLine("     , ").Append(Const_Han_Params.廃班年月日);
            query.AppendLine("     , ").Append(Const_Han_Params.廃班区分);
            query.AppendLine("     , ").Append(Const_Han_Params.班長);
            query.AppendLine("     , ").Append(Const_Han_Params.班人数);
            query.AppendLine("     , ").Append(Const_Han_Params.企画種別);
            query.AppendLine("     , ").Append(Const_Han_Params.コース種別1);
            query.AppendLine("     , ").Append(Const_Han_Params.コースコード1);
            query.AppendLine("     , ").Append(Const_Han_Params.便区分1);
            query.AppendLine("     , ").Append(Const_Han_Params.配達順1);
            query.AppendLine("     , ").Append(Const_Han_Params.コース種別2);
            query.AppendLine("     , ").Append(Const_Han_Params.コースコード2);
            query.AppendLine("     , ").Append(Const_Han_Params.便区分2);
            query.AppendLine("     , ").Append(Const_Han_Params.配達順2);
            query.AppendLine("     , ").Append(Const_Han_Params.個配区分);
            query.AppendLine("     , ").Append(Const_Han_Params.個配初回);
            query.AppendLine("     , ").Append(Const_Han_Params.割戻区分);
            query.AppendLine("     , ").Append(Const_Han_Params.住所コード);
            query.AppendLine("     , ").Append(Const_Han_Params.電話番号);
            query.AppendLine("     , ").Append(Const_Han_Params.牛乳当番);
            query.AppendLine("     , ").Append(Const_Han_Params.連絡先);
            query.AppendLine("     , ").Append(Const_Han_Params.連絡TEL);
            query.AppendLine("     , ").Append(Const_Han_Params.拠点コード);
            query.AppendLine("     , ").Append(Const_Han_Params.愛知中央統合区分);
            query.AppendLine("     , ").Append(Const_Han_Params.作成日時);
            query.AppendLine("     , ").Append(Const_Han_Params.作成者コード);
            query.AppendLine("     , ").Append(Const_Han_Params.更新日時);
            query.AppendLine("     , ").Append(Const_Han_Params.更新者コード);
            query.AppendLine("     )");
            query.AppendLine(";");
            SqlWrapper.Parameters paramsQuery = this.GetRegistParams(dbCnn, jugyoin);

            if (IsExists(dbCnn, this.HanCd))
            {
                intRetrurn = ConstSSS.intDbUpdErrInserted;
            }
            else
            {
                intRetrurn = dbCnn.ExecuteNonQuery(query, paramsQuery);
            }
            return intRetrurn;
        }

        /// <summary>
        /// レコード更新
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="jugyoin">ログイン情報</param>
        /// <remarks></remarks>
        public int UpdateRow(SqlWrapper dbCnn, JugyoinMaster jugyoin)
        {
            int intRetrurn = ConstSSS.intDbUpdErr;

            StringBuilder query = new StringBuilder();
            query.AppendLine("UPDATE ").Append(ConstSSS.NameMaster.ChumonData).Append(" SET");
            query.AppendLine("       班名 = ").Append(Const_Han_Params.班名);
            query.AppendLine("     , 班カナ名 = ").Append(Const_Han_Params.班カナ名);
            query.AppendLine("     , 班区分 = ").Append(Const_Han_Params.班区分);
            query.AppendLine("     , 生協コード = ").Append(Const_Han_Params.生協コード);
            query.AppendLine("     , 事業所グループ = ").Append(Const_Han_Params.事業所グループ);
            query.AppendLine("     , 事業所コード = ").Append(Const_Han_Params.事業所コード);
            query.AppendLine("     , ブロックコード = ").Append(Const_Han_Params.ブロックコード);
            query.AppendLine("     , 牛乳登録区分 = ").Append(Const_Han_Params.牛乳登録区分);
            query.AppendLine("     , 地区 = ").Append(Const_Han_Params.地区);
            query.AppendLine("     , 結成年月日 = ").Append(Const_Han_Params.結成年月日);
            query.AppendLine("     , 廃班年月日 = ").Append(Const_Han_Params.廃班年月日);
            query.AppendLine("     , 廃班区分 = ").Append(Const_Han_Params.廃班区分);
            query.AppendLine("     , 班長 = ").Append(Const_Han_Params.班長);
            query.AppendLine("     , 班人数 = ").Append(Const_Han_Params.班人数);
            query.AppendLine("     , 企画種別 = ").Append(Const_Han_Params.企画種別);
            query.AppendLine("     , コース種別1 = ").Append(Const_Han_Params.コース種別1);
            query.AppendLine("     , コースコード1 = ").Append(Const_Han_Params.コースコード1);
            query.AppendLine("     , 便区分1 = ").Append(Const_Han_Params.便区分1);
            query.AppendLine("     , 配達順1 = ").Append(Const_Han_Params.配達順1);
            query.AppendLine("     , コース種別2 = ").Append(Const_Han_Params.コース種別2);
            query.AppendLine("     , コースコード2 = ").Append(Const_Han_Params.コースコード2);
            query.AppendLine("     , 便区分2 = ").Append(Const_Han_Params.便区分2);
            query.AppendLine("     , 配達順2 = ").Append(Const_Han_Params.配達順2);
            query.AppendLine("     , 個配区分 = ").Append(Const_Han_Params.個配区分);
            query.AppendLine("     , 個配初回 = ").Append(Const_Han_Params.個配初回);
            query.AppendLine("     , 割戻区分 = ").Append(Const_Han_Params.割戻区分);
            query.AppendLine("     , 住所コード = ").Append(Const_Han_Params.住所コード);
            query.AppendLine("     , 電話番号 = ").Append(Const_Han_Params.電話番号);
            query.AppendLine("     , 牛乳当番 = ").Append(Const_Han_Params.牛乳当番);
            query.AppendLine("     , 連絡先 = ").Append(Const_Han_Params.連絡先);
            query.AppendLine("     , 連絡TEL = ").Append(Const_Han_Params.連絡TEL);
            query.AppendLine("     , 拠点コード = ").Append(Const_Han_Params.拠点コード);
            query.AppendLine("     , 愛知中央統合区分 = ").Append(Const_Han_Params.愛知中央統合区分);
            query.AppendLine("     , 更新日時 = ").Append(Const_Han_Params.更新日時);
            query.AppendLine("     , 更新者コード = ").Append(Const_Han_Params.更新者コード);
            query.AppendLine(" WHERE 班コード = ").Append(Const_Han_Params.班コード);
            SqlWrapper.Parameters paramsQuery = this.GetRegistParams(dbCnn, jugyoin);

            DataRow row = GetRow(dbCnn, this.HanCd);
            if (row == null)
            {
                intRetrurn = ConstSSS.intDbUpdErrDeleted;
            }
            else if (Parser.ToNullableDate(row["更新日時"]) != this.UpdateDate)
            {
                intRetrurn = ConstSSS.intDbUpdErrUpdated;
            }
            else
            {
                intRetrurn = dbCnn.ExecuteNonQuery(query, paramsQuery);
            }
            return intRetrurn;
        }

        /// <summary>
        /// レコード削除
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="jugyoin">ログイン情報</param>
        /// <remarks></remarks>
        public int DeleteRow(SqlWrapper dbCnn, JugyoinMaster jugyoin)
        {
            int intRetrurn = ConstSSS.intDbUpdErr;

            StringBuilder query = new StringBuilder();
            query.AppendLine("DELETE ").Append(ConstSSS.NameMaster.ChumonData);
            query.AppendLine(" WHERE 班コード = ").Append(Const_Han_Params.班コード);
            SqlWrapper.Parameters paramsQuery = this.GetRegistParams(dbCnn, jugyoin);

            DataRow row = GetRow(dbCnn, this.HanCd);
            if (row == null)
            {
                intRetrurn = ConstSSS.intDbUpdErrDeleted;
            }
            else if (Parser.ToNullableDate(row["更新日時"]) != this.UpdateDate)
            {
                intRetrurn = ConstSSS.intDbUpdErrUpdated;
            }
            else
            {
                intRetrurn = dbCnn.ExecuteNonQuery(query, paramsQuery);
            }
            return intRetrurn;
        }

        /// <summary>
        /// 登録用パラメータの取得
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="jugyoin">ログイン情報</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public SqlWrapper.Parameters GetRegistParams(SqlWrapper dbCnn, JugyoinMaster jugyoin)
        {
            DateTime? datNowDatetime = dbCnn.GetDateTime();
            SqlWrapper.Parameters paramsQuery = new SqlWrapper.Parameters
            {
                { Const_Han_Params.班コード, this.HanCd },
                { Const_Han_Params.班名, this.HanNm },
                { Const_Han_Params.班カナ名, this.HanKanaNm },
                { Const_Han_Params.班区分, this.HanKbn },
                { Const_Han_Params.生協コード, this.SeikyoCd },
                { Const_Han_Params.事業所グループ, this.JigyoshoGrp },
                { Const_Han_Params.事業所コード, this.JigyoshoCd },
                { Const_Han_Params.ブロックコード, this.BlockCd },
                { Const_Han_Params.牛乳登録区分, this.GyunyuTorokuKbn },
                { Const_Han_Params.地区, this.Chiku },
                { Const_Han_Params.結成年月日, this.KesseiYmd },
                { Const_Han_Params.廃班年月日, this.HaiHanYmd },
                { Const_Han_Params.廃班区分, this.HaiHanKbn },
                { Const_Han_Params.班長, this.HanchoCd },
                { Const_Han_Params.班人数, this.HanNinzu },
                { Const_Han_Params.企画種別, this.KikakuShubetsu },
                { Const_Han_Params.コース種別1, this.KosuShubetsu1 },
                { Const_Han_Params.コースコード1, this.KosuCd1 },
                { Const_Han_Params.便区分1, this.BenKbn1 },
                { Const_Han_Params.配達順1, this.HaitatsuJun1 },
                { Const_Han_Params.コース種別2, this.KosuShubetsu2 },
                { Const_Han_Params.コースコード2, this.KosuCd2 },
                { Const_Han_Params.便区分2, this.BenKbn2 },
                { Const_Han_Params.配達順2, this.HaitatsuJun2 },
                { Const_Han_Params.個配区分, this.KohaiKbn },
                { Const_Han_Params.個配初回, this.KohaiShokai },
                { Const_Han_Params.割戻区分, this.WarimodoshiKbn },
                { Const_Han_Params.住所コード, this.JushoCd },
                { Const_Han_Params.電話番号, this.Tel },
                { Const_Han_Params.牛乳当番, this.GyunyuToban },
                { Const_Han_Params.連絡先, this.RenrakuSaki },
                { Const_Han_Params.連絡TEL, this.RenrakuTel },
                { Const_Han_Params.拠点コード, this.KyotenCd },
                { Const_Han_Params.愛知中央統合区分, this.AichiChuoTogoKbn },
                { Const_Han_Params.作成日時, datNowDatetime },
                { Const_Han_Params.作成者コード, jugyoin.JugyoinCd },
                { Const_Han_Params.更新日時, datNowDatetime },
                { Const_Han_Params.更新者コード, jugyoin.JugyoinCd }
            };

            return paramsQuery;
        }
        #endregion
        */


    }
}
