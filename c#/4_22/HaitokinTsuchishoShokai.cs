using System;
using System.Text;
using System.Data;
using static TN.SSS.HaitokinTsuchishoShokai;

namespace TN.SSS
{
    public class HaitokinTsuchishoShokai
    {
        #region Classes

        public class Const_HaitokinTsuchishoShokai_Params
        {
            public const string 年度 = "@年度";
            public const string 事業所コード = "@事業所コード";
            public const string 組合員コード = "@組合員コード";
            public const string 組合員名 = "@組合員名";
            public const string 電話番号 = "@電話番号";
            public const string 住所1 = "@住所1";
            public const string 住所2 = "@住所2";
            public const string 住所3 = "@住所3";
            public const string 郵便番号 = "@郵便番号";
            public const string 班コード = "@班コード";
            public const string 班名 = "@班名";
            public const string コースコード = "@コースコード";
            public const string 扱い = "@扱い";
            public const string 銀行名 = "@銀行名";
            public const string 支店名 = "@支店名";
            public const string 種別 = "@種別";
            public const string 口座番号 = "@口座番号";
            public const string 口座名 = "@口座名";
            public const string 通帳記号 = "@通帳記号";
            public const string 通帳番号 = "@通帳番号";
            public const string 通帳口座名 = "@通帳口座名";
            public const string 出資金額1 = "@出資金額1";
            public const string 配当率 = "@配当率";
            public const string 配当金額 = "@配当金額";
            public const string 税金 = "@税金";
            public const string 差引額 = "@差引額";
            public const string 前年預り金 = "@前年預り金";
            public const string 出資金繰入金 = "@出資金繰入金";
            public const string 今年度預り金 = "@今年度預り金";
            public const string お支払金額 = "@お支払金額";
            public const string 発行年月日 = "@発行年月日";
            public const string 加入年月日 = "@加入年月日";
            public const string 出資口数 = "@出資口数";
            public const string 出資金額2 = "@出資金額2";
            public const string 出資預り金 = "@出資預り金";
            public const string 作成日時 = "@作成日時";
            public const string 作成者コード = "@作成者コード";
            public const string 更新日時 = "@更新日時";
            public const string 更新者コード = "@更新者コード";

            //Extensions
            //↓↓↓----- F_HaitokinTsuchishoShokaiCond -----↓↓↓
            //↑↑↑----- F_HaitokinTsuchishoShokaiCond -----↑↑↑
        }

        #endregion

        #region Properties

        //年度 
        public string Nendo { get; set; }
        //事業所コード 
        public string JigyoshoCd { get; set; }
        //組合員コード 
        public string KumiaiinCd { get; set; }
        //組合員名 
        public string KumiaiinNm { get; set; }
        //電話番号 
        public string Tenwabango { get; set; }
        //住所1 
        public string Jusho1 { get; set; }
        //住所2 
        public string Jusho2 { get; set; }
        //住所3 
        public string Jusho3 { get; set; }
        //郵便番号 
        public string Yubenbango { get; set; }
        //班コード 
        public string HanCd { get; set; }
        //班名 
        public string HanNm { get; set; }
        //コースコード 
        public string KosuCd { get; set; }
        //扱い 
        public string Atsukai { get; set; }
        //銀行名 
        public string GinkoNm { get; set; }
        //支店名 
        public string ShitenNm { get; set; }
        //種別 
        public string Shubetsu { get; set; }
        //口座番号 
        public string KozaBango { get; set; }
        //口座名 
        public string KozaNm { get; set; }
        //通帳記号 
        public string TsuchoKigo { get; set; }
        //通帳番号 
        public string TsuchoBango { get; set; }
        //通帳口座名 
        public string TsuchoKozaNm { get; set; }
        //出資金額1 
        public double ShusshiKingaku1 { get; set; }
        //配当率 
        public double HaitoRitsu { get; set; }
        //配当金額 
        public double HaitoKingaku { get; set; }
        //税金 
        public double Zeikin { get; set; }
        //差引額 
        public double Sashihikigaku { get; set; }
        //前年預り金 
        public double ZennenAzukarikin { get; set; }
        //出資金繰入金 
        public double ShusshikinKuriirekin { get; set; }
        //今年度預り金 
        public double KonnendoAzukarikin { get; set; }
        //お支払金額 
        public double OShiharaiKingaku { get; set; }
        //発行年月日 
        public DateTime? HakkoNengappi { get; set; }
        //加入年月日 
        public DateTime? KanyuNengappi { get; set; }
        //出資口数 
        public double ShusshiKuchisu { get; set; }
        //出資金額2 
        public double ShusshiKingaku2 { get; set; }
        //出資預り金 
        public double ShusshiAzukarikin { get; set; }
        //作成日時 
        public DateTime? CreateDate { get; set; }
        //作成者コード 
        public string CreateUserCd { get; set; }
        //更新日時 
        public DateTime? UpdateDate { get; set; }
        //更新者コード 
        public string UpdateUserCd { get; set; }


        #endregion

        #region Methods

        /// <summary>
        /// インスタンスを取得する。
        /// </summary>
        /// <param name="nendo">年度</param>
        /// <param name="kumiaiinCd">組合員コード</param>
        /// <param name="jigyoshoCd">事業所コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static HaitokinTsuchishoShokai GetInstance(string nendo, string kumiaiinCd, string jigyoshoCd)
        {
            return GetInstance(null, nendo, kumiaiinCd, jigyoshoCd);
        }

        /// <summary>
        /// インスタンスを取得する。
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="nendo">年度</param>
        /// <param name="kumiaiinCd">組合員コード</param>
        /// <param name="jigyoshoCd">事業所コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static HaitokinTsuchishoShokai GetInstance(SqlWrapper dbCnn, string nendo, string kumiaiinCd, string jigyoshoCd)
        {
            DataRow row = GetRow(dbCnn, nendo, kumiaiinCd, jigyoshoCd);
            if (row == null)
            {
                return null;
            }
            HaitokinTsuchishoShokai haitokinTsuchishoShokai = new HaitokinTsuchishoShokai();
            haitokinTsuchishoShokai.Nendo = Parser.ToString(row["年度"]);
            haitokinTsuchishoShokai.JigyoshoCd = Parser.ToString(row["事業所コード"]);
            haitokinTsuchishoShokai.KumiaiinCd = Parser.ToString(row["組合員コード"]);
            haitokinTsuchishoShokai.KumiaiinNm = Parser.ToString(row["組合員名"]);
            haitokinTsuchishoShokai.Tenwabango = Parser.ToString(row["電話番号"]);
            haitokinTsuchishoShokai.Jusho1 = Parser.ToString(row["住所1"]);
            haitokinTsuchishoShokai.Jusho2 = Parser.ToString(row["住所2"]);
            haitokinTsuchishoShokai.Jusho3 = Parser.ToString(row["住所3"]);
            haitokinTsuchishoShokai.Yubenbango = Parser.ToString(row["郵便番号"]);
            haitokinTsuchishoShokai.HanCd = Parser.ToString(row["班コード"]);
            haitokinTsuchishoShokai.HanNm = Parser.ToString(row["班名"]);
            haitokinTsuchishoShokai.KosuCd = Parser.ToString(row["コースコード"]);
            haitokinTsuchishoShokai.Atsukai = Parser.ToString(row["扱い"]);
            haitokinTsuchishoShokai.GinkoNm = Parser.ToString(row["銀行名"]);
            haitokinTsuchishoShokai.ShitenNm = Parser.ToString(row["支店名"]);
            haitokinTsuchishoShokai.Shubetsu = Parser.ToString(row["種別"]);
            haitokinTsuchishoShokai.KozaBango = Parser.ToString(row["口座番号"]);
            haitokinTsuchishoShokai.KozaNm = Parser.ToString(row["口座名"]);
            haitokinTsuchishoShokai.TsuchoKigo = Parser.ToString(row["通帳記号"]);
            haitokinTsuchishoShokai.TsuchoBango = Parser.ToString(row["通帳番号"]);
            haitokinTsuchishoShokai.TsuchoKozaNm = Parser.ToString(row["通帳口座名"]);
            haitokinTsuchishoShokai.ShusshiKingaku1 = Parser.ToDouble(row["出資金額1"]);
            haitokinTsuchishoShokai.HaitoRitsu = Parser.ToDouble(row["配当率"]);
            haitokinTsuchishoShokai.HaitoKingaku = Parser.ToDouble(row["配当金額"]);
            haitokinTsuchishoShokai.Zeikin = Parser.ToDouble(row["税金"]);
            haitokinTsuchishoShokai.Sashihikigaku = Parser.ToDouble(row["差引額"]);
            haitokinTsuchishoShokai.ZennenAzukarikin = Parser.ToDouble(row["前年預り金"]);
            haitokinTsuchishoShokai.ShusshikinKuriirekin = Parser.ToDouble(row["出資金繰入金"]);
            haitokinTsuchishoShokai.KonnendoAzukarikin = Parser.ToDouble(row["今年度預り金"]);
            haitokinTsuchishoShokai.OShiharaiKingaku = Parser.ToDouble(row["お支払金額"]);
            haitokinTsuchishoShokai.HakkoNengappi = Parser.ToNullableDate(row["発行年月日"]);
            haitokinTsuchishoShokai.KanyuNengappi = Parser.ToNullableDate(row["加入年月日"]);
            haitokinTsuchishoShokai.ShusshiKuchisu = Parser.ToDouble(row["出資口数"]);
            haitokinTsuchishoShokai.ShusshiKingaku2 = Parser.ToDouble(row["出資金額2"]);
            haitokinTsuchishoShokai.ShusshiAzukarikin = Parser.ToDouble(row["出資預り金"]);
            haitokinTsuchishoShokai.CreateDate = Parser.ToNullableDate(row["作成日時"]);
            haitokinTsuchishoShokai.CreateUserCd = Parser.ToString(row["作成者コード"]);
            haitokinTsuchishoShokai.UpdateDate = Parser.ToNullableDate(row["更新日時"]);
            haitokinTsuchishoShokai.UpdateUserCd = Parser.ToString(row["更新者コード"]);

            return haitokinTsuchishoShokai;
        }

        #endregion

        #region Select Methods

        /// <summary>
        /// レコード有無の検証
        /// </summary>
        /// <param name="nendo">年度</param>
        /// <param name="kumiaiinCd">組合員コード</param>
        /// <param name="jigyoshoCd">事業所コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static bool IsExists(string nendo, string kumiaiinCd, string jigyoshoCd)
        {
            return IsExists(null, nendo, kumiaiinCd, jigyoshoCd);
        }

        /// <summary>
        /// レコード有無の検証
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="nendo">年度</param>
        /// <param name="kumiaiinCd">組合員コード</param>
        /// <param name="jigyoshoCd">事業所コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static bool IsExists(SqlWrapper dbCnn, string nendo, string kumiaiinCd, string jigyoshoCd)
        {
            StringBuilder query = new StringBuilder();
            query.AppendLine("SELECT COUNT(*)");
            query.AppendLine("  FROM ").Append(ConstSSS.NameMaster.HaitokinTsuchishoShokai);
            query.AppendLine(" WHERE MT.年度  = ").Append(Const_HaitokinTsuchishoShokai_Params.年度);
            query.AppendLine("   AND MT.事業所コード = ").Append(Const_HaitokinTsuchishoShokai_Params.事業所コード);
            query.AppendLine("   AND MT.組合員コード = ").Append(Const_HaitokinTsuchishoShokai_Params.組合員コード);
            SqlWrapper.Parameters paramsQuery = new SqlWrapper.Parameters
            {
                { Const_HaitokinTsuchishoShokai_Params.年度, nendo },
                { Const_HaitokinTsuchishoShokai_Params.事業所コード, kumiaiinCd },
                { Const_HaitokinTsuchishoShokai_Params.組合員コード, jigyoshoCd }
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
        /// <param name="nendo">年度</param>
        /// <param name="kumiaiinCd">組合員コード</param>
        /// <param name="jigyoshoCd">事業所コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static DataRow GetRow(string  nendo, string kumiaiinCd, string jigyoshoCd)
        {
            return GetRow(null, nendo, kumiaiinCd, jigyoshoCd);
        }

        /// <summary>
        /// レコードを取得する。
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="nendo">年度</param>
        /// <param name="kumiaiinCd">組合員コード</param>
        /// <param name="jigyoshoCd">事業所コード</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static DataRow GetRow(SqlWrapper dbCnn, string nendo, string kumiaiinCd, string jigyoshoCd)
        {
            StringBuilder query = new StringBuilder();
            query.AppendLine("SELECT MT.*");
            query.AppendLine("     , U1.従業員名 AS 作成者名");
            query.AppendLine("     , U2.従業員名 AS 更新者名");
            query.AppendLine("  FROM ").Append(ConstSSS.NameMaster.HaitokinTsuchishoShokai).Append(" MT");
            query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.JugyoinMaster).Append(" JT ON MT.事業所コード = JT.事業所コード");
            query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.KumiaiinMaster).Append(" KT ON MT.組合員コード = KT.組合員コード");
            query.AppendLine(" WHERE MT.年度  = ").Append(Const_HaitokinTsuchishoShokai_Params.年度);
            query.AppendLine("   AND MT.事業所コード = ").Append(Const_HaitokinTsuchishoShokai_Params.事業所コード);
            query.AppendLine("   AND MT.組合員コード = ").Append(Const_HaitokinTsuchishoShokai_Params.組合員コード);
            SqlWrapper.Parameters paramsQuery = new SqlWrapper.Parameters
            {
                { Const_HaitokinTsuchishoShokai_Params.年度, nendo },
                { Const_HaitokinTsuchishoShokai_Params.事業所コード, kumiaiinCd },
                { Const_HaitokinTsuchishoShokai_Params.組合員コード, jigyoshoCd }
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

        /*
        public static DataTable F_HaitokinTsuchishoShokaiCond_SearchData(SqlWrapper dbCnn, SqlWrapper.Parameters paramsQuery)
        {
            StringBuilder query = new StringBuilder();
            query.AppendLine("SELECT MT.銀行コード");
            query.AppendLine("     , MT.銀行名");
            query.AppendLine("     , MT.銀行カナ名");
            query.AppendLine("     , MT.支店コード");
            query.AppendLine("     , MT.支店名");
            query.AppendLine("     , MT.支店カナ名");
            query.AppendLine("     , MT.自振区分");
            query.AppendLine("     , MT.支店限定1 + MT.支店限定2 + MT.支店限定3 AS 支店限定");
            query.AppendLine("     , MT.集約区分");
            query.AppendLine("     , MT.廃止年月");
            query.AppendLine("  FROM ").Append(ConstSSS.NameMaster.HaitokinTsuchishoShokai).Append(" MT");
            query.AppendLine(" WHERE 1 = 1");

            if (paramsQuery.ContainsKey(Const_Ginko_Params.内訳区分) && Parser.ToString(paramsQuery[Const_Ginko_Params.内訳区分].Value) == KubunMaster.銀行_内訳区分.銀行)
                query.AppendLine("   AND TRIM(MT.支店コード) = ''");

            if(paramsQuery.ContainsKey(Const_Ginko_Params.内訳区分) && Parser.ToString(paramsQuery[Const_Ginko_Params.内訳区分].Value) == KubunMaster.銀行_内訳区分.支店)
                query.AppendLine("   AND TRIM(MT.支店コード) <> ''");

            if (paramsQuery.ContainsKey(Const_Ginko_Params.銀行コード) && Parser.ToString(paramsQuery[Const_Ginko_Params.銀行コード].Value) != "")
                query.AppendLine("   AND MT.銀行コード = ").Append(Const_Ginko_Params.銀行コード);

            if (paramsQuery.ContainsKey(Const_Ginko_Params.銀行名) && Parser.ToString(paramsQuery[Const_Ginko_Params.銀行名].Value).Trim('%') != "")
                query.AppendLine("   AND MT.銀行名 LIKE ").Append(Const_Ginko_Params.銀行名);

            if (paramsQuery.ContainsKey(Const_Ginko_Params.銀行カナ名) && Parser.ToString(paramsQuery[Const_Ginko_Params.銀行カナ名].Value).Trim('%') != "")
                query.AppendLine("   AND MT.銀行カナ名 LIKE ").Append(Const_Ginko_Params.銀行カナ名);

            if (paramsQuery.ContainsKey(Const_Ginko_Params.支店コード) && Parser.ToString(paramsQuery[Const_Ginko_Params.支店コード].Value) != "")
                query.AppendLine("   AND MT.支店コード = ").Append(Const_Ginko_Params.支店コード);

            if (paramsQuery.ContainsKey(Const_Ginko_Params.支店名) && Parser.ToString(paramsQuery[Const_Ginko_Params.支店名].Value).Trim('%') != "")
                query.AppendLine("   AND MT.支店名 LIKE ").Append(Const_Ginko_Params.支店名);

            if (paramsQuery.ContainsKey(Const_Ginko_Params.支店カナ名) && Parser.ToString(paramsQuery[Const_Ginko_Params.支店カナ名].Value).Trim('%') != "")
                query.AppendLine("   AND MT.支店カナ名 LIKE ").Append(Const_Ginko_Params.支店カナ名);

            if (paramsQuery.ContainsKey(Const_Ginko_Params.自振区分) && Parser.ToString(paramsQuery[Const_Ginko_Params.自振区分].Value) != "")
                query.AppendLine("   AND MT.自振区分 = ").Append(Const_Ginko_Params.自振区分);

            if (paramsQuery.ContainsKey(Const_Ginko_Params.集約区分) && Parser.ToString(paramsQuery[Const_Ginko_Params.集約区分].Value) != "")
                query.AppendLine("   AND MT.集約区分 = ").Append(Const_Ginko_Params.集約区分);

            if (paramsQuery.ContainsKey(Const_Ginko_Params.廃止年月) && Parser.ToString(paramsQuery[Const_Ginko_Params.廃止年月].Value) != "")
                query.AppendLine("   AND MT.廃止年月 = ").Append(Const_Ginko_Params.廃止年月);

            if (paramsQuery.ContainsKey(Const_Ginko_Params.更新日時_From) && Parser.ToString(paramsQuery[Const_Ginko_Params.更新日時_From].Value) != "")
                query.AppendLine("   AND FORMAT(MT.更新日時, 'yyyy/MM/dd') >= ").Append(Const_Ginko_Params.更新日時_From);

            if (paramsQuery.ContainsKey(Const_Ginko_Params.更新日時_To) && Parser.ToString(paramsQuery[Const_Ginko_Params.更新日時_To].Value) != "")
                query.AppendLine("   AND FORMAT(MT.更新日時, 'yyyy/MM/dd') <= ").Append(Const_Ginko_Params.更新日時_To);

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
            query.AppendLine("INSERT INTO ").Append(ConstSSS.NameMaster.HaitokinTsuchishoShokai).Append(" WITH (TABLOCK) (");
            query.AppendLine("       銀行コード");
            query.AppendLine("     , 銀行名");
            query.AppendLine("     , 銀行カナ名");
            if (Parser.ToString(this.ShitenCd) != "")
            {
                query.AppendLine("     , 支店コード");
                query.AppendLine("     , 支店名");
                query.AppendLine("     , 支店カナ名");
            } 
            query.AppendLine("     , 自振区分");
            query.AppendLine("     , 支店限定1");
            query.AppendLine("     , 支店限定2");
            query.AppendLine("     , 支店限定3");
            query.AppendLine("     , 集約区分");
            query.AppendLine("     , 廃止年月");
            query.AppendLine("     , 作成日時");
            query.AppendLine("     , 作成者コード");
            query.AppendLine("     , 更新日時");
            query.AppendLine("     , 更新者コード");
            query.AppendLine("     ) VALUES (");
            query.AppendLine("       ").Append(Const_Ginko_Params.銀行コード);
            query.AppendLine("     , ").Append(Const_Ginko_Params.銀行名);
            query.AppendLine("     , ").Append(Const_Ginko_Params.銀行カナ名);
            if (Parser.ToString(this.ShitenCd) != "")
            {
                query.AppendLine("     , ").Append(Const_Ginko_Params.支店コード);
                query.AppendLine("     , ").Append(Const_Ginko_Params.支店名);
                query.AppendLine("     , ").Append(Const_Ginko_Params.支店カナ名);
            }
            query.AppendLine("     , ").Append(Const_Ginko_Params.自振区分);
            query.AppendLine("     , ").Append(Const_Ginko_Params.支店限定1);
            query.AppendLine("     , ").Append(Const_Ginko_Params.支店限定2);
            query.AppendLine("     , ").Append(Const_Ginko_Params.支店限定3);
            query.AppendLine("     , ").Append(Const_Ginko_Params.集約区分);
            query.AppendLine("     , ").Append(Const_Ginko_Params.廃止年月);
            query.AppendLine("     , ").Append(Const_Ginko_Params.作成日時);
            query.AppendLine("     , ").Append(Const_Ginko_Params.作成者コード);
            query.AppendLine("     , ").Append(Const_Ginko_Params.更新日時);
            query.AppendLine("     , ").Append(Const_Ginko_Params.更新者コード);
            query.AppendLine("     )");
            query.AppendLine(";");
            SqlWrapper.Parameters paramsQuery = this.GetRegistParams(dbCnn, jugyoin);

            if (IsExists(dbCnn, this.GinkoCd, this.ShitenCd))
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
            query.AppendLine("UPDATE ").Append(ConstSSS.NameMaster.HaitokinTsuchishoShokai).Append(" SET");
            query.AppendLine("       銀行名 = ").Append(Const_Ginko_Params.銀行名);
            query.AppendLine("     , 銀行カナ名 = ").Append(Const_Ginko_Params.銀行カナ名);
            if (Parser.ToString(this.ShitenCd) != "")
            {
                query.AppendLine("     , 支店名 = ").Append(Const_Ginko_Params.支店名);
                query.AppendLine("     , 支店カナ名 = ").Append(Const_Ginko_Params.支店カナ名);
            }
            query.AppendLine("     , 自振区分 = ").Append(Const_Ginko_Params.自振区分);
            query.AppendLine("     , 支店限定1 = ").Append(Const_Ginko_Params.支店限定1);
            query.AppendLine("     , 支店限定2 = ").Append(Const_Ginko_Params.支店限定2);
            query.AppendLine("     , 支店限定3 = ").Append(Const_Ginko_Params.支店限定3);
            query.AppendLine("     , 集約区分 = ").Append(Const_Ginko_Params.集約区分);
            query.AppendLine("     , 廃止年月 = ").Append(Const_Ginko_Params.廃止年月);
            query.AppendLine("     , 更新日時 = ").Append(Const_Ginko_Params.更新日時);
            query.AppendLine("     , 更新者コード = ").Append(Const_Ginko_Params.更新者コード);
            query.AppendLine(" WHERE 銀行コード = ").Append(Const_Ginko_Params.銀行コード);
            if (Parser.ToString(this.ShitenCd) != "") query.AppendLine("   AND 支店コード = ").Append(Const_Ginko_Params.支店コード);
            SqlWrapper.Parameters paramsQuery = this.GetRegistParams(dbCnn, jugyoin);

            DataRow row = GetRow(dbCnn, this.GinkoCd, this.ShitenCd);
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
            query.AppendLine("DELETE ").Append(ConstSSS.NameMaster.HaitokinTsuchishoShokai);
            query.AppendLine(" WHERE 銀行コード = ").Append(Const_Ginko_Params.銀行コード);
            if (Parser.ToString(this.ShitenCd) != "") query.AppendLine("   AND 支店コード = ").Append(Const_Ginko_Params.支店コード);
            SqlWrapper.Parameters paramsQuery = this.GetRegistParams(dbCnn, jugyoin);

            DataRow row = GetRow(dbCnn, this.GinkoCd, this.ShitenCd);
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
                { Const_Ginko_Params.銀行コード, this.GinkoCd },
                { Const_Ginko_Params.銀行名, this.GinkoNm },
                { Const_Ginko_Params.銀行カナ名, this.GinkoKanaNm },
                { Const_Ginko_Params.支店コード, this.ShitenCd },
                { Const_Ginko_Params.支店名, this.ShitenNm },
                { Const_Ginko_Params.支店カナ名, this.ShitenKanaNm },
                { Const_Ginko_Params.自振区分, this.TransferKbn },
                { Const_Ginko_Params.支店限定1, this.ShitenGentei1 },
                { Const_Ginko_Params.支店限定2, this.ShitenGentei2 },
                { Const_Ginko_Params.支店限定3, this.ShitenGentei3 },
                { Const_Ginko_Params.集約区分, this.ShuyakuKbn },
                { Const_Ginko_Params.廃止年月, this.HaishiYm },
                { Const_Ginko_Params.作成日時, datNowDatetime },
                { Const_Ginko_Params.作成者コード, jugyoin.JugyoinCd },
                { Const_Ginko_Params.更新日時, datNowDatetime },
                { Const_Ginko_Params.更新者コード, jugyoin.JugyoinCd }
            };

            return paramsQuery;
        }
        */
        #endregion
    }
}
