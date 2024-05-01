public static DataTable F_TeiseiDataCond_SearchData(SqlWrapper dbCnn, SqlWrapper.Parameters paramsQuery)
{
    StringBuilder query = new StringBuilder();
    query.AppendLine("SELECT TD.商品コード");
    query.AppendLine("     , TD.部門");
    query.AppendLine("     , TD.訂正理由");
    query.AppendLine("     , TD.品名");
    query.AppendLine("     , TD.訂正数量");
    query.AppendLine("     , TD.訂正単価");
    query.AppendLine("     , TD.訂正金額");
    query.AppendLine("     , TD.配送料除外");
    query.AppendLine("     , TD.訂正企画");
    query.AppendLine("     , TD.組合員コード");
    query.AppendLine("     , TD.支部コード");
    query.AppendLine("     , TD.班コード");
    query.AppendLine("     , SB.支部カナ名");
    query.AppendLine("     , HM.班名");
    query.AppendLine("     , KI.組合員名"); 
    query.AppendLine("  FROM ").Append(ConstSSS.NameMaster.TeiseiData).Append(" TD");
    query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.KumiaiinMaster).Append(" KI");
    query.AppendLine("         ON TD.組合員コード = KI.組合員コード");
    query.AppendLine("         ON TD.班コード = KI.班コード");
    query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.HanMaster).Append(" HM");
    query.AppendLine("         ON TD.班コード = HM.班コード");
    query.AppendLine("  LEFT JOIN ").Append(ConstSSS.NameMaster.ShibuMaster).Append(" SB");
    query.AppendLine("         ON TD.支部コード = SB.支部コード");
    query.AppendLine(" WHERE 1 = 1");
    if (paramsQuery.ContainsKey(Const_TeiseiData_Params.支部コード) && Parser.ToString(paramsQuery[Const_TeiseiData_Params.支部コード].Value) != "")
        query.AppendLine("   AND TD.支部コード = ").Append(Const_TeiseiData_Params.支部コード);
    if (paramsQuery.ContainsKey(Const_TeiseiData_Params.支部名) && Parser.ToString(paramsQuery[Const_TeiseiData_Params.支部名].Value).Trim('%') != "")
        query.AppendLine("   AND SB.支部カナ名 LIKE ").Append(Const_TeiseiData_Params.支部名);
    
    if (paramsQuery.ContainsKey(Const_TeiseiData_Params.班コード) && Parser.ToString(paramsQuery[Const_TeiseiData_Params.班コード].Value) != "")
        query.AppendLine("   AND TD.班コード = ").Append(Const_TeiseiData_Params.班コード);
    if (paramsQuery.ContainsKey(Const_TeiseiData_Params.班名) && Parser.ToString(paramsQuery[Const_TeiseiData_Params.班名].Value).Trim('%') != "")
        query.AppendLine("   AND HM.班名 LIKE ").Append(Const_TeiseiData_Params.班名);

    if (paramsQuery.ContainsKey(Const_TeiseiData_Params.組合員コード) && Parser.ToString(paramsQuery[Const_TeiseiData_Params.組合員コード].Value) != "")
        query.AppendLine("   AND TD.組合員コード = ").Append(Const_TeiseiData_Params.組合員コード);
    if (paramsQuery.ContainsKey(Const_TeiseiData_Params.組合員名) && Parser.ToString(paramsQuery[Const_TeiseiData_Params.組合員名].Value).Trim('%') != "")
        query.AppendLine("   AND KI.組合員名 LIKE ").Append(Const_TeiseiData_Params.組合員名);

    if (paramsQuery.ContainsKey(Const_TeiseiData_Params.訂正企画) && Parser.ToString(paramsQuery[Const_TeiseiData_Params.訂正企画].Value) != "")
        query.AppendLine("   AND TD.訂正企画 = ").Append(Const_TeiseiData_Params.訂正企画);
    
    query.AppendLine("  ORDER BY TD.組合員コード");
    query.AppendLine("		   , TD.訂正企画");

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