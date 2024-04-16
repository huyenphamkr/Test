using System;
using System.Data;
using System.Text;
using static TN.SSS.KumiaiinMaster;

namespace TN.SSS
{
    public class TeiseiData
    {
        #region Classes

        public class Const_TeiseiData_Params
        {
            public const string ID = "@ID";
            public const string レコード区分 = "@レコード区分";
            public const string 支部グループ = "@支部グループ";
            public const string 支部コード = "@支部コード";
            public const string 班コード = "@班コード";
            public const string 組合員コード = "@組合員コード";
            public const string コースコード = "@コースコード";
            public const string 便区分 = "@便区分";
            public const string 順 = "@順";
            public const string 個配区分 = "@個配区分";
            public const string 訂正企画 = "@訂正企画";
            public const string 訂正企画_年 = "@訂正企画_年";
            public const string 訂正企画_月 = "@訂正企画_月";
            public const string 訂正企画_回 = "@訂正企画_回";
            public const string 訂正企画_区分 = "@訂正企画_区分";
            public const string 訂正曜日 = "@訂正曜日";
            public const string 配達企画 = "@配達企画";
            public const string 配達企画_年 = "@配達企画_年";
            public const string 配達企画_月 = "@配達企画_月";
            public const string 配達企画_回 = "@配達企画_回";
            public const string 配達企画_区分 = "@配達企画_区分";
            public const string 配達曜日 = "@配達曜日";
            public const string 注文企画 = "@注文企画";
            public const string 注文企画_年 = "@注文企画_年";
            public const string 注文企画_月 = "@注文企画_月";
            public const string 注文企画_回 = "@注文企画_回";
            public const string 注文企画_区分 = "@注文企画_区分";
            public const string 商品コード = "@商品コード";
            public const string 品名 = "@品名";
            public const string 注文番号 = "@注文番号";
            public const string 部門コード = "@部門コード";
            public const string 訂正理由 = "@訂正理由";
            public const string 訂正数量 = "@訂正数量";
            public const string 訂正単価 = "@訂正単価";
            public const string 訂正金額 = "@訂正金額";
            public const string 配送料除外 = "@配送料除外";
            public const string 購入数量 = "@購入数量";
            public const string 購入税込額 = "@購入税込額";
            public const string 購入税抜額 = "@購入税抜額";
            public const string 伝票削除フラグ = "@伝票削除フラグ";
            public const string 一括訂正フラグ = "@一括訂正フラグ";
            public const string 処理フラグ = "@処理フラグ";
            public const string 処理日 = "@処理日";
            public const string 登録日 = "@登録日";
            public const string 仮更新フラグ = "@仮更新フラグ";
            public const string 作成日時 = "@作成日時";
            public const string 作成者コード = "@作成者コード";
            public const string 更新日時 = "@更新日時";
            public const string 更新者コード = "@更新者コード";
        }

        #endregion

        #region Properties

        //ID
        public int TeiseiCd { get; set; }
        //レコード区分
        public string RekodoKbn { get; set; }
        //支部グループ
        public string ShibuGrp { get; set; }
        //支部コード
        public string ShibuCd { get; set; }
        //班コード
        public string HanCd { get; set; }
        //組合員コード
        public string KumiaiinCd { get; set; }
        //コースコード
        public string KosuCd { get; set; }
        //便区分
        public string BenKbn { get; set; }
        //順
        public string Jun { get; set; }
        //個配区分
        public string KoHaiKbn { get; set; }
        //訂正企画
        public string TeiseiKikaku { get; set; }
        //訂正企画_年
        public string TeiseiKikakuYear { get; set; }
        //訂正企画_月
        public string TeiseiKikakuMonth { get; set; }
        //訂正企画_回
        public string TeiseiKikakuWeek { get; set; }
        //訂正企画_区分
        public string TeiseiKikakuKbn { get; set; }
        //訂正曜日
        public string TeiseiYobi { get; set; }
        //配達企画
        public string HaitatsuKikaku { get; set; }
        //配達企画_年
        public string HaitatsuKikakuYear { get; set; }
        //配達企画_月
        public string HaitatsuKikakuMonth { get; set; }
        //配達企画_回
        public string HaitatsuKikakuWeek { get; set; }
        //配達企画_区分
        public string HaitatsuKikakuKbn { get; set; }
        //配達曜日
        public string HaitatsuYobi { get; set; }
        //注文企画
        public string ChumonKikaku { get; set; }
        //注文企画_年
        public string ChumonKikakuYear { get; set; }
        //注文企画_月
        public string ChumonKikakuMonth { get; set; }
        //注文企画_回
        public string ChumonKikakuWeek { get; set; }
        //注文企画_区分
        public string ChumonKikakuKbn { get; set; }
        //商品コード
        public string ShohinCd { get; set; }
        //品名
        public string HinNm { get; set; }
        //注文番号
        public string ChumonBan { get; set; }
        //部門コード
        public string BumonCd { get; set; }
        //訂正理由
        public string Teiseiriyu { get; set; }
        //訂正数量
        public string TeiseiSuryo { get; set; }
        //訂正単価
        public double TeiseiTanka { get; set; }
        //訂正金額
        public double TeiseiKingaku { get; set; }
        //配送料除外
        public string HaisoryoJogai { get; set; }
        //購入数量
        public string KonyuSuryo { get; set; }
        //購入税込額
        public double KonyuZeikomiGaku { get; set; }
        //購入税抜額
        public double KonyuZeinuGaku { get; set; }
        //伝票削除フラグ
        public string DenpyoSakujoFuragu { get; set; }
        //一括訂正フラグ
        public string IkkatsuTeiseiFuragu { get; set; }
        //処理フラグ
        public string ShoriFuragu { get; set; }
        //処理日
        public DateTime? Shoribi { get; set; }
        //登録日
        public DateTime? Torokubi { get; set; }
        //仮更新フラグ
        public string KariKoshinFuragu { get; set; }
        //作成日時
        public DateTime? CreateDate { get; set; }
        //作成者コード
        public string CreateUserCd { get; set; }
        //更新日時
        public DateTime? UpdateDate { get; set; }
        //更新者コード
        public string UpdateUserCd { get; set; }

        #endregion



        /// <summary>
        /// インスタンスを取得する。
        /// </summary>
        /// <param name="teiseiCd">区分</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static TeiseiData GetInstance(string teiseiCd)
        {
            return GetInstance(null, teiseiCd);
        }

        /// <summary>
        /// インスタンスを取得する。
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="teiseiCd">区分</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static TeiseiData GetInstance(SqlWrapper dbCnn, string teiseiCd)
        {
            DataRow row = GetRow(dbCnn, teiseiCd);
            if (row == null) return null;

            TeiseiData teisei = new TeiseiData();
            teisei.TeiseiCd = Parser.ToInt32(row["ID"]);
            teisei.RekodoKbn = Parser.ToString(row["レコード区分"]);
            teisei.ShibuGrp = Parser.ToString(row["支部グループ"]);
            teisei.ShibuCd = Parser.ToString(row["支部コード"]);
            teisei.HanCd = Parser.ToString(row["班コード"]);
            teisei.KumiaiinCd = Parser.ToString(row["組合員コード"]);
            teisei.KosuCd = Parser.ToString(row["コースコード"]);
            teisei.BenKbn = Parser.ToString(row["便区分"]);
            teisei.Jun = Parser.ToString(row["順"]);
            teisei.KoHaiKbn = Parser.ToString(row["個配区分"]);
            teisei.TeiseiKikaku = Parser.ToString(row["訂正企画"]);
            teisei.TeiseiKikakuYear = Parser.ToString(row["訂正企画_年"]);
            teisei.TeiseiKikakuMonth = Parser.ToString(row["訂正企画_月"]);
            teisei.TeiseiKikakuWeek = Parser.ToString(row["訂正企画_回"]);
            teisei.TeiseiKikakuKbn = Parser.ToString(row["訂正企画_区分"]);
            teisei.TeiseiYobi = Parser.ToString(row["訂正曜日"]);
            teisei.HaitatsuKikaku = Parser.ToString(row["配達企画"]);
            teisei.HaitatsuKikakuYear = Parser.ToString(row["配達企画_年"]);
            teisei.HaitatsuKikakuMonth = Parser.ToString(row["配達企画_月"]);
            teisei.HaitatsuKikakuWeek = Parser.ToString(row["配達企画_回"]);
            teisei.HaitatsuKikakuKbn = Parser.ToString(row["配達企画_区分"]);
            teisei.HaitatsuYobi = Parser.ToString(row["配達曜日"]);
            teisei.ChumonKikaku = Parser.ToString(row["注文企画"]);
            teisei.ChumonKikakuYear = Parser.ToString(row["注文企画_年"]);
            teisei.ChumonKikakuMonth = Parser.ToString(row["注文企画_月"]);
            teisei.ChumonKikakuWeek = Parser.ToString(row["注文企画_回"]);
            teisei.ChumonKikakuKbn = Parser.ToString(row["注文企画_区分"]);
            teisei.ShohinCd = Parser.ToString(row["商品コード"]);
            teisei.HinNm = Parser.ToString(row["品名"]);
            teisei.ChumonBan = Parser.ToString(row["注文番号"]);
            teisei.BumonCd = Parser.ToString(row["部門コード"]);
            teisei.Teiseiriyu = Parser.ToString(row["訂正理由"]);
            teisei.TeiseiSuryo = Parser.ToString(row["訂正数量"]);
            teisei.TeiseiTanka = Parser.ToDouble(row["訂正単価"]);
            teisei.TeiseiKingaku = Parser.ToDouble(row["訂正金額"]);
            teisei.HaisoryoJogai = Parser.ToString(row["配送料除外"]);
            teisei.KonyuSuryo = Parser.ToString(row["購入数量"]);
            teisei.KonyuZeikomiGaku = Parser.ToDouble(row["購入税込額"]);
            teisei.KonyuZeinuGaku = Parser.ToDouble(row["購入税抜額"]);
            teisei.DenpyoSakujoFuragu = Parser.ToString(row["伝票削除フラグ"]);
            teisei.IkkatsuTeiseiFuragu = Parser.ToString(row["一括訂正フラグ"]);
            teisei.ShoriFuragu = Parser.ToString(row["処理フラグ"]);
            teisei.Shoribi = Parser.ToNullableDate(row["処理日"]);
            teisei.Torokubi = Parser.ToNullableDate(row["登録日"]);
            teisei.KariKoshinFuragu = Parser.ToString(row["仮更新フラグ"]);
            teisei.CreateDate = Parser.ToNullableDate(row["作成日時"]);
            teisei.CreateUserCd = Parser.ToString(row["作成者コード"]);
            teisei.UpdateDate = Parser.ToNullableDate(row["更新日時"]);
            teisei.UpdateUserCd = Parser.ToString(row["更新者コード"]);

            return teisei;
        }




        #region Select Methods

        /// <summary>
        /// レコード有無の検証
        /// </summary>
        /// <param name="kumiaiinCd">組合員コード</param>
        /// <param name="teiseiKikaku">訂正企画</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static bool IsExists(string kumiaiinCd, string teiseiKikaku)
        {
            return IsExists(null, kumiaiinCd, teiseiKikaku);
        }

        /// <summary>
        /// レコード有無の検証
        /// </summary>
        /// <param name="dbCnn">DBコネクション</param>
        /// <param name="kumiaiinCd">組合員コード</param>
        /// <param name="teiseiKikaku">訂正企画</param>
        /// <returns></returns>
        /// <remarks></remarks>
        public static bool IsExists(SqlWrapper dbCnn, string kumiaiinCd, string teiseiKikaku)
        {
            StringBuilder query = new StringBuilder();
            query.AppendLine("SELECT COUNT(*)");
            query.AppendLine("  FROM ").Append(ConstSSS.NameMaster.TeiseiData);
            query.AppendLine(" WHERE 組合員コード = ").Append(Const_TeiseiData_Params.組合員コード);
            query.AppendLine("   AND 訂正企画 = ").Append(Const_TeiseiData_Params.訂正企画);
            SqlWrapper.Parameters paramsQuery = new SqlWrapper.Parameters
            {
                { Const_TeiseiData_Params.組合員コード, kumiaiinCd },
                { Const_TeiseiData_Params.訂正企画, teiseiKikaku }
            };

            if (dbCnn != null)
            {
                return Parser.DBCountToBoolean(dbCnn.ExecuteScalar(query, paramsQuery));
            }

            return Parser.DBCountToBoolean(SqlWrapper.ReadOne(query, paramsQuery));
        }

        public static DataTable GetTableByKey(SqlWrapper dbCnn, string kumiaiinCd, string teiseiKikaku)
        {
            StringBuilder query = new StringBuilder();
            query.AppendLine("SELECT *");
            query.AppendLine("  FROM ").Append(ConstSSS.NameMaster.TeiseiData);
            query.AppendLine(" WHERE 組合員コード = ").Append(Const_TeiseiData_Params.組合員コード);
            query.AppendLine(" AND 訂正企画 = ").Append(Const_TeiseiData_Params.訂正企画);

            SqlWrapper.Parameters paramsQuery = new SqlWrapper.Parameters
            {
                { Const_TeiseiData_Params.組合員コード, kumiaiinCd },
                { Const_TeiseiData_Params.訂正企画, teiseiKikaku }
            };

            if (dbCnn != null)
            {
                DataTable dtb = new DataTable();
                dbCnn.ExecuteAdapterFill(query, paramsQuery, dtb);
                return dtb;
            }
            return SqlWrapper.ReadTable(query, paramsQuery);
        }


        #endregion DB取得

        #region Insert, Update, Delete Methods

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
            query.AppendLine("DELETE ").Append(ConstSSS.NameMaster.TeiseiData);
            query.AppendLine(" WHERE 組合員コード = ").Append(Const_TeiseiData_Params.組合員コード);
            query.AppendLine(" AND 訂正企画 = ").Append(Const_TeiseiData_Params.訂正企画);
            SqlWrapper.Parameters paramsQuery = this.GetRegistParams(dbCnn, jugyoin);

            DataRow row = GetRow(dbCnn, this.KumiaiinCd);
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
                { Const_TeiseiData_Params.ID, this.TeiseiCd},
                { Const_TeiseiData_Params.レコード区分, this.RekodoKbn},
                { Const_TeiseiData_Params.支部グループ, this.ShibuGrp},
                { Const_TeiseiData_Params.支部コード, this.ShibuCd},
                { Const_TeiseiData_Params.班コード, this.HanCd},
                { Const_TeiseiData_Params.組合員コード, this.KumiaiinCd},
                { Const_TeiseiData_Params.コースコード, this.KosuCd},
                { Const_TeiseiData_Params.便区分, this.BenKbn},
                { Const_TeiseiData_Params.順, this.Jun},
                { Const_TeiseiData_Params.個配区分, this.KoHaiKbn},
                { Const_TeiseiData_Params.訂正企画, this.TeiseiKikaku},
                { Const_TeiseiData_Params.訂正企画_年, this.TeiseiKikakuYear},
                { Const_TeiseiData_Params.訂正企画_月, this.TeiseiKikakuMonth},
                { Const_TeiseiData_Params.訂正企画_回, this.TeiseiKikakuWeek},
                { Const_TeiseiData_Params.訂正企画_区分, this.TeiseiKikakuKbn},
                { Const_TeiseiData_Params.訂正曜日, this.TeiseiYobi},
                { Const_TeiseiData_Params.配達企画, this.HaitatsuKikaku},
                { Const_TeiseiData_Params.配達企画_年, this.HaitatsuKikakuYear},
                { Const_TeiseiData_Params.配達企画_月, this.HaitatsuKikakuMonth},
                { Const_TeiseiData_Params.配達企画_回, this.HaitatsuKikakuWeek},
                { Const_TeiseiData_Params.配達企画_区分, this.HaitatsuKikakuKbn},
                { Const_TeiseiData_Params.配達曜日, this.HaitatsuYobi},
                { Const_TeiseiData_Params.注文企画, this.ChumonKikaku},
                { Const_TeiseiData_Params.注文企画_年, this.ChumonKikakuYear},
                { Const_TeiseiData_Params.注文企画_月, this.ChumonKikakuMonth},
                { Const_TeiseiData_Params.注文企画_回, this.ChumonKikakuWeek},
                { Const_TeiseiData_Params.注文企画_区分, this.ChumonKikakuKbn},
                { Const_TeiseiData_Params.商品コード, this.ShohinCd},
                { Const_TeiseiData_Params.品名, this.HinNm},
                { Const_TeiseiData_Params.注文番号, this.ChumonBan},
                { Const_TeiseiData_Params.部門コード, this.BumonCd},
                { Const_TeiseiData_Params.訂正理由, this.Teiseiriyu},
                { Const_TeiseiData_Params.訂正数量, this.TeiseiSuryo},
                { Const_TeiseiData_Params.訂正単価, this.TeiseiTanka},
                { Const_TeiseiData_Params.訂正金額, this.TeiseiKingaku},
                { Const_TeiseiData_Params.配送料除外, this.HaisoryoJogai},
                { Const_TeiseiData_Params.購入数量, this.KonyuSuryo},
                { Const_TeiseiData_Params.購入税込額, this.KonyuZeikomiGaku},
                { Const_TeiseiData_Params.購入税抜額, this.KonyuZeinuGaku},
                { Const_TeiseiData_Params.伝票削除フラグ, this.DenpyoSakujoFuragu},
                { Const_TeiseiData_Params.一括訂正フラグ, this.IkkatsuTeiseiFuragu},
                { Const_TeiseiData_Params.処理フラグ, this.ShoriFuragu},
                { Const_TeiseiData_Params.処理日, this.Shoribi},
                { Const_TeiseiData_Params.登録日, this.Torokubi},
                { Const_TeiseiData_Params.仮更新フラグ, this.KariKoshinFuragu},
                { Const_Kumiaiin_Params.作成日時, datNowDatetime },
                { Const_Kumiaiin_Params.作成者コード, jugyoin.JugyoinCd },
                { Const_Kumiaiin_Params.更新日時, datNowDatetime },
                { Const_Kumiaiin_Params.更新者コード, jugyoin.JugyoinCd }
            };

            return paramsQuery;
        }


        #endregion Insert, Update, Delete Methods


    }
}
