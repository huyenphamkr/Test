using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace TN.SSS.週次
{
    public partial class F_TeiseiData : Form
    {
        public F_TeiseiData(JugyoinMaster jugyoin)
            : base(jugyoin)
        {
            InitializeComponent();
        }

        #region Properties

        DataTable dtbTeiseiData;
        private DateTime? datBackupMaxUpdateDate;

        #endregion 変数

        #region Events

        private void F_TeiseiData_KeyDown(object sender, KeyEventArgs e)
        {
            try
            {
                switch (e.KeyCode)
                {
                    case Keys.F1:
                        if (this.btnHeader01.Enabled == false || this.btnHeader01.Visible == false) return;
                        this.btnHeader01.Focus();
                        this.F_TeiseiData_btnHeader01_Click(null, null);
                        break;

                    case Keys.F2:
                        if (this.btnHeader02.Enabled == false || this.btnHeader02.Visible == false) return;
                        this.btnHeader02.Focus();
                        this.F_TeiseiData_btnHeader02_Click(null, null);
                        break;

                    case Keys.F3:
                        if (this.btnHeader03.Enabled == false || this.btnHeader03.Visible == false) return;
                        this.btnHeader03.Focus();
                        this.F_TeiseiData_btnHeader03_Click(null, null);
                        break;

                    case Keys.F5:
                        if (this.btnHeader05.Enabled == false || this.btnHeader05.Visible == false) return;
                        this.btnHeader05.Focus();
                        this.F_TeiseiData_btnHeader05_Click(null, null);
                        break;

                    case Keys.F6:
                        if (this.btnHeader06.Enabled == false || this.btnHeader06.Visible == false) return;
                        this.btnHeader06.Focus();
                        this.F_TeiseiData_btnHeader06_Click(null, null);
                        break;

                    case Keys.F9:
                        if (this.btnHeader09.Enabled == false || this.btnHeader09.Visible == false) return;
                        this.btnHeader09.Focus();
                        this.F_TeiseiData_btnHeader09_Click(null, null);
                        break;

                    case Keys.F12:
                        if (this.btnHeader12.Enabled == false || this.btnHeader12.Visible == false) return;
                        this.btnHeader12.Focus();
                        this.F_BaseSmall_btnHeader12_Click(null, null);
                        break;
                }
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

        private void F_TeiseiData_Load(object sender, EventArgs e)
        {
            try
            {
                this.F_TeiseiData_SetButton();
                this.F_TeiseiData_SetGrid();
                this.F_TeiseiData_SetControlInput();
                this.F_TeiseiData_SetTabOrder();
                this.F_TeiseiData_ClearForm();
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

        private void F_TeiseiData_btnHeader01_Click(object sender, EventArgs e)
        {
            try
            {
                this.lblShoriMode.Text = ConstSSS.ShoriMode.Add;
                this.lblShortcutKey.Text = "F1：" + ConstSSS.ShoriMode.Add;
                if (TeiseiData.IsExists(this.ctrKumiaiinCdNm.txtCode.Text, this.txtTeiseiKikakuYear.Text + this.txtTeiseiKikakuMTP.Text))
                {
                    if (Alert.Question(Alert.ConstMessage.Q_DuplicateDataMoveUpdate) == DialogResult.Yes)
                        this.F_TeiseiData_btnHeader02_Click(null, null);
                    else
                        this.ctrKumiaiinCdNm.txtCode.Focus();
                    return;
                }
                else
                {
                    if (Parser.ToString(this.txtTeiseiKikakuYear.Text) == "")
                    {
                        Alert.Exclamation(Alert.ConstMessage.I_MustInput, "企画_年");
                        this.txtTeiseiKikakuYear.Focus();
                        return;
                    }

                    if (Parser.ToString(this.txtTeiseiKikakuMTP.Text) == "")
                    {
                        Alert.Exclamation(Alert.ConstMessage.I_MustInput, "企画_月・回・企画");
                        this.txtTeiseiKikakuMTP.Focus();
                        return;
                    }

                    DateTime dateTeiseiKikaku = Parser.ToDateTime(this.txtTeiseiKikakuYear.Text + "/" + this.txtTeiseiKikakuMTP.Text.Substring(0, 2) + "/01");
                    if (dateTeiseiKikaku.Day > 365)
                    {
                        Alert.Exclamation(Alert.ConstMessage.I_1Year);
                        this.txtTeiseiKikakuYear.Focus();
                        return;
                    }

                    if (Parser.ToString(this.ctrKumiaiinCdNm.txtCode.Text) == "")
                    {
                        Alert.Exclamation(Alert.ConstMessage.I_MustInput, "組合員コード");
                        this.ctrKumiaiinCdNm.txtCode.Focus();
                        return;
                    }
                }

                this.F_TeiseiData_GetData();
                this.F_TeiseiData_SetShoriMode();
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

        private void F_TeiseiData_btnHeader02_Click(object sender, EventArgs e)
        {
            try
            {
                this.lblShoriMode.Text = ConstSSS.ShoriMode.Update;
                this.lblShortcutKey.Text = "F2：" + ConstSSS.ShoriMode.Update;

                if (Parser.ToString(this.ctrKumiaiinCdNm.txtCode.Text) != "" && Parser.ToString(this.txtTeiseiKikakuYear.Text) != "" && Parser.ToString(this.txtTeiseiKikakuMTP.Text) != "")
                {
                    this.dtbTeiseiData = TeiseiData.GetTableByKey(null, this.ctrKumiaiinCdNm.txtCode.Text, this.txtTeiseiKikakuYear.Text + this.txtTeiseiKikakuMTP.Text);
                    if (this.dtbTeiseiData == null || this.dtbTeiseiData.Rows.Count <= 0)
                    {
                        Alert.Exclamation(Alert.ConstMessage.E_NoData);
                        this.ctrKumiaiinCdNm.txtCode.Focus();
                        return;
                    }
                }
                else
                {
                    this.F_TeiseiData_OpenCondForm();
                    return;
                }

                this.F_TeiseiData_GetData();
                this.F_TeiseiData_SetShoriMode();
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

        private void F_TeiseiData_btnHeader03_Click(object sender, EventArgs e)
        {
            try
            {
                this.lblShoriMode.Text = ConstSSS.ShoriMode.Delete;
                this.lblShortcutKey.Text = "F3：" + ConstSSS.ShoriMode.Delete;

                if (Parser.ToString(this.ctrKumiaiinCdNm.txtCode.Text) != "")
                {
                    this.dtbTeiseiData = MenjoshaMaster.GetTableByKumiaiinCd(null, this.ctrKumiaiinCdNm.txtCode.Text);
                    if (this.dtbTeiseiData == null || this.dtbTeiseiData.Rows.Count <= 0)
                    {
                        Alert.Exclamation(Alert.ConstMessage.E_NoData);
                        this.ctrKumiaiinCdNm.txtCode.Focus();
                        return;
                    }
                }
                else
                {
                    this.F_TeiseiData_OpenCondForm();
                    return;
                }

                this.F_TeiseiData_GetData();
                this.F_TeiseiData_SetShoriMode();
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

        private void F_TeiseiData_btnHeader05_Click(object sender, EventArgs e)
        {
            try
            {
                if (this.grdDataViewer.RowCount <= 0 || this.grdDataViewer.Row >= this.grdDataViewer.RowCount) return;
                if (Alert.Question(Alert.ConstMessage.Q_Shori, (this.grdDataViewer.Row + 1) + "行目削除") == DialogResult.No) return;
                this.grdDataViewer.UpdateData();
                this.grdDataViewer.Delete(this.grdDataViewer.Row);
                ((DataTable)this.grdDataViewer.DataSource).AcceptChanges();
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

        private void F_TeiseiData_btnHeader06_Click(object sender, EventArgs e)
        {
            try
            {
                if (Alert.Question(Alert.ConstMessage.Q_Back) == DialogResult.Yes)
                {
                    this.F_TeiseiData_ClearForm();
                }
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

        private void F_TeiseiData_btnHeader09_Click(object sender, EventArgs e)
        {
            if (this.F_TeiseiData_CheckInput() == false) return;
            if (Alert.Question(Alert.ConstMessage.Q_Shori, this.lblShoriMode.Text) == DialogResult.No) return;
            SqlWrapper dbCnn = new SqlWrapper();
            int intReturn = ConstSSS.intDbUpdErr;
            try
            {
                this.Cursor = Cursors.WaitCursor;
                dbCnn.CurrentTransaction = dbCnn.BeginTransaction();
                switch (this.lblShoriMode.Text)
                {
                    case ConstSSS.ShoriMode.Add:
                        intReturn = MenjoshaMaster.BulkInserts(dbCnn, this.Jugyoin, (DataTable)this.grdDataViewer.DataSource);
                        break;
                    case ConstSSS.ShoriMode.Update:
                        intReturn = MenjoshaMaster.DeleteByKumiaiCd(dbCnn, this.Jugyoin, this.ctrKumiaiinCdNm.txtCode.Text, this.datBackupMaxUpdateDate);
                        intReturn = MenjoshaMaster.BulkInserts(dbCnn, this.Jugyoin, (DataTable)this.grdDataViewer.DataSource);
                        break;
                    case ConstSSS.ShoriMode.Delete:
                        intReturn = MenjoshaMaster.DeleteByKumiaiCd(dbCnn, this.Jugyoin, this.ctrKumiaiinCdNm.txtCode.Text, this.datBackupMaxUpdateDate);
                        break;
                }

                switch (intReturn)
                {
                    case ConstSSS.intDbUpdErrInserted:
                    case ConstSSS.intDbUpdErrUpdated:
                    case ConstSSS.intDbUpdErrDeleted:
                        Alert.Exclamation(Alert.ConstMessage.E_DbUpdErrUpdated, "組合員コード = " + this.ctrKumiaiinCdNm.txtCode.Text, this.lblShoriMode.Text);
                        dbCnn.CurrentTransaction.Rollback();
                        return;
                    case 0:
                        Alert.Exclamation(Alert.ConstMessage.E_DbUpdErrNoUpdated, "組合員コード = " + this.ctrKumiaiinCdNm.txtCode.Text, this.lblShoriMode.Text);
                        dbCnn.CurrentTransaction.Rollback();
                        return;
                }

                dbCnn.CurrentTransaction.Commit();
                Alert.Information(Alert.ConstMessage.I_ShoriOK, this.lblShoriMode.Text);
                this.F_TeiseiData_ClearForm();
            }
            catch (Exception ex)
            {
                dbCnn.CurrentTransaction.Rollback();
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
            finally
            {
                this.Cursor = Cursors.Default;
            }
        }

        #endregion

        #region Methods

        private void F_TeiseiData_SetButton()
        {
            this.SetButtonImage(new System.Drawing.Bitmap[] {
                    global::TN.SSS.Properties.Resources.add, global::TN.SSS.Properties.Resources.update,
                    global::TN.SSS.Properties.Resources.delete, global::TN.SSS.Properties.Resources.no_image,
                    global::TN.SSS.Properties.Resources.delete2, global::TN.SSS.Properties.Resources.back,
                    global::TN.SSS.Properties.Resources.no_image, global::TN.SSS.Properties.Resources.no_image,
                    global::TN.SSS.Properties.Resources.confirm2, global::TN.SSS.Properties.Resources.no_image,
                    global::TN.SSS.Properties.Resources.print, global::TN.SSS.Properties.Resources.close
                });
            this.SetButtonText(new string[] {
                    ConstSSS.ShoriMode.Add + "(F1)", ConstSSS.ShoriMode.Update + "(F2)",
                    ConstSSS.ShoriMode.Delete + "(F3)", "", "行削除(F5)", "戻る(F6)",
                    "", "", "確認(F9)", "", "", "閉じる(F12)"
                });
            this.SetButtonVisible(new bool[] { true, true, true, false, true, true, false, false, true, false, false, true });
        }

        private void F_TeiseiData_SetControlInput()
        {

            SetC1TextBoxCode(this.txtTeiseiKikakuYear, "0000");
            SetC1TextBoxCode(this.txtTeiseiKikakuMTP, "0000");
            this.txtTeiseiKikakuMTP.MaxLength = 4;
            this.txtTeiseiKikakuMTP.MaskInfo.RegexpEditMask = "(0[1-9]|1[0-2])[1-5][0-9]";
            this.txtTeiseiKikakuMTP.MaskInfo.ErrorMessage = "企画_月・回・企画（01-12月・1-5回・区分）を入力してください";
            this.ctrKumiaiinCdNm.CodeType = ConstSSS.Format.Type.cod組合員;
        }

        private void F_TeiseiData_ClearForm()
        {
            this.SetButtonEnabled(new bool[] { true, true, true, true, false, true, false, false, false, false, false, true });
            this.pnlBodyChild1.Enabled = true;
            this.txtTeiseiKikakuYear.Text = "";
            this.txtTeiseiKikakuMTP.Text = "";
            this.ctrKumiaiinCdNm.txtCode.Text = "";

            this.lblShoriMode.Text = "";
            this.lblShortcutKey.Text = "";

            this.dtbTeiseiData = null;
            this.grdDataViewer.SetDataBinding(this.dtbTeiseiData, "", true);
            this.grdDataViewer.SetGridForShoriMode(ConstSSS.GridMode.ReadOnly);

            this.txtTeiseiKikakuYear.Focus();
            this.txtTeiseiKikakuYear.Select();
        }

        private void F_TeiseiData_SetTabOrder()
        {
            int intTabIndex = -1;
            try
            {
                SetTabOrder(this.txtTeiseiKikakuYear, ref intTabIndex);
                SetTabOrder(this.txtTeiseiKikakuMTP, ref intTabIndex);
                SetTabOrder(this.ctrKumiaiinCdNm, ref intTabIndex);
                SetTabOrder(this.grdDataViewer, ref intTabIndex);
                SetTabOrder(this.btnHeader01, ref intTabIndex);
                SetTabOrder(this.btnHeader02, ref intTabIndex);
                SetTabOrder(this.btnHeader03, ref intTabIndex);
                SetTabOrder(this.btnHeader04, ref intTabIndex);
                SetTabOrder(this.btnHeader05, ref intTabIndex);
                SetTabOrder(this.btnHeader06, ref intTabIndex);
                SetTabOrder(this.btnHeader07, ref intTabIndex);
                SetTabOrder(this.btnHeader08, ref intTabIndex);
                SetTabOrder(this.btnHeader09, ref intTabIndex);
                SetTabOrder(this.btnHeader10, ref intTabIndex);
                SetTabOrder(this.btnHeader12, ref intTabIndex);
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

        private void F_TeiseiData_SetShoriMode()
        {
            try
            {
                switch (this.lblShoriMode.Text)
                {
                    case ConstSSS.ShoriMode.Add:
                        this.SetButtonEnabled(new bool[] { false, false, false, false, true, true, false, false, true, false, false, true });
                        this.pnlBodyChild1.Enabled = false;
                        this.grdDataViewer.SetGridForShoriMode(ConstSSS.GridMode.Update);
                        this.grdDataViewer.Focus();
                        break;

                    case ConstSSS.ShoriMode.Update:
                        this.SetButtonEnabled(new bool[] { false, false, false, false, true, true, false, false, true, false, false, true });
                        this.pnlBodyChild1.Enabled = false;
                        this.grdDataViewer.SetGridForShoriMode(ConstSSS.GridMode.Update);
                        this.grdDataViewer.Focus();
                        break;

                    case ConstSSS.ShoriMode.Delete:
                        this.SetButtonEnabled(new bool[] { false, false, false, false, false, true, false, false, true, false, false, true });
                        this.pnlBodyChild1.Enabled = false;
                        this.grdDataViewer.SetGridForShoriMode(ConstSSS.GridMode.ReadOnly);
                        this.btnHeader06.Focus();
                        break;
                }
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

        private void F_TeiseiData_OpenCondForm()
        {
            try
            {
                using (F_KumiaiinMasterCond frmCond = new F_KumiaiinMasterCond(this.Jugyoin))
                {
                    frmCond.GetSetItemSearch = new F_KumiaiinMasterCond.ItemSearch();
                    frmCond.lblShoriMode.Text = this.lblShoriMode.Text;
                    frmCond.GetMenjosha = 1;
                    if (frmCond.ShowDialog(this) == DialogResult.OK)
                    {
                        this.ctrKumiaiinCdNm.txtCode.Value = frmCond.SelectedObject.KumiaiinCd;
                        this.ctrKumiaiinCdNm.lblName.Text = frmCond.SelectedObject.KumiaiinNm;
                        this.dtbTeiseiData = MenjoshaMaster.GetTableByKumiaiinCd(null, this.ctrKumiaiinCdNm.txtCode.Text);
                        this.F_TeiseiData_GetData();
                        this.F_TeiseiData_SetShoriMode();
                    }
                }
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

        private void F_TeiseiData_SetGrid()
        {
            GridView.ColumnInfo[] arrCol = new GridView.ColumnInfo[8];
            int intCol = -1;

            intCol++;
            arrCol[intCol] = new GridView.ColumnInfo();
            arrCol[intCol].Caption = "商品コード";
            arrCol[intCol].DataField = "商品コード";
            arrCol[intCol].Width = 100;
            arrCol[intCol].AllowFocus = true;
            arrCol[intCol].NumberFormat = ConstSSS.Format.Type.cod商品;
            arrCol[intCol].DataWidth = 8;
            arrCol[intCol].ShowButton = true;

            intCol++;
            arrCol[intCol] = new GridView.ColumnInfo();
            arrCol[intCol].Caption = "部門";
            arrCol[intCol].DataField = "部門";
            arrCol[intCol].Width = 50;
            arrCol[intCol].DataWidth = 2;
            arrCol[intCol].Locked = true;
            arrCol[intCol].AllowFocus = false;

            intCol++;
            arrCol[intCol] = new GridView.ColumnInfo();
            arrCol[intCol].Caption = "訂正理由";
            arrCol[intCol].DataField = "訂正理由";
            arrCol[intCol].Width = 100;
            arrCol[intCol].ComboBoxDataSource = KubunMaster.GetComboByKubunCd(KubunMaster.KubunCode.訂正データ_訂正企画, false);


            intCol++;
            arrCol[intCol] = new GridView.ColumnInfo();
            arrCol[intCol].Caption = "商品名";
            arrCol[intCol].DataField = "品名";
            arrCol[intCol].Width = 150;
            arrCol[intCol].Locked = true;
            arrCol[intCol].AllowFocus = false;

            intCol++;
            arrCol[intCol] = new GridView.ColumnInfo();
            arrCol[intCol].Caption = "数";
            arrCol[intCol].DataField = "訂正数量";
            arrCol[intCol].DataWidth = 3;
            arrCol[intCol].Width = 50;
            arrCol[intCol].NumberFormat = ConstSSS.Format.Type.num数量_0;

            intCol++;
            arrCol[intCol] = new GridView.ColumnInfo();
            arrCol[intCol].Caption = "単価]";
            arrCol[intCol].DataField = "訂正単価]";
            arrCol[intCol].Width = 100;


            intCol++;
            arrCol[intCol] = new GridView.ColumnInfo();
            arrCol[intCol].Caption = "金額";
            arrCol[intCol].DataField = "訂正金額";
            arrCol[intCol].Width = 200;
            arrCol[intCol].Locked = true;
            arrCol[intCol].AllowFocus = false;

            intCol++;
            arrCol[intCol] = new GridView.ColumnInfo();
            arrCol[intCol].Caption = "お届料除外";
            arrCol[intCol].DataField = "配送料除外";
            arrCol[intCol].Width = 100;
            arrCol[intCol].IsCheckBox = true;
            this.grdDataViewer.SetGrid(arrCol, ConstSSS.GridMode.ReadOnly);
        }



        private bool F_TeiseiData_GetData()
        {
            bool blnGetData = false;
            try
            {
                if (this.dtbTeiseiData == null) return blnGetData;

                this.grdDataViewer.SetDataBinding(this.dtbTeiseiData, "", true);
                datBackupMaxUpdateDate = Parser.ToNullableDate(this.dtbTeiseiData.Compute("MAX(更新日時)", ""));

                blnGetData = true;
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
            return blnGetData;
        }

        private bool F_TeiseiData_CheckInput()
        {
            bool blnCheckInput = false;
            try
            {
                if (this.lblShoriMode.Text == ConstSSS.ShoriMode.Delete) return true;
                bool blnCheckInputBody = this.F_TeiseiData_CheckInputBody();
                if (!blnCheckInputBody) return blnCheckInput;

                /*
                if (Parser.ToString(this.txtTeiseiKikakuYear.Text) == "")
                {
                    Alert.Exclamation(Alert.ConstMessage.I_MustInput, "企画_年");
                    this.txtTeiseiKikakuYear.Focus();
                    return blnCheckInput;
                }

                if (Parser.ToString(this.txtTeiseiKikakuMTP.Text) == "")
                {
                    Alert.Exclamation(Alert.ConstMessage.I_MustInput, "企画_月・回・企画");
                    this.txtTeiseiKikakuMTP.Focus();
                    return blnCheckInput;
                }

                DateTime dateTeiseiKikaku = Parser.ToDateTime(this.txtTeiseiKikakuYear.Text + "/" + this.txtTeiseiKikakuMTP.Text.Substring(0, 2) + "/01");
                if (dateTeiseiKikaku.Day > 365)
                {
                    Alert.Exclamation(Alert.ConstMessage.I_1Year);
                    this.txtTeiseiKikakuYear.Focus();
                    return blnCheckInput;
                }

                if (string.IsNullOrEmpty(this.ctrKumiaiinCdNm.txtCode.Text))
                {
                    Alert.Exclamation(Alert.ConstMessage.I_MustInput, "組合員コード");
                    this.ctrKumiaiinCdNm.txtCode.Focus();
                    return blnCheckInput;
                }
                */

                this.grdDataViewer.UpdateData();
                if (this.grdDataViewer.RowCount <= 0)
                {
                    Alert.Exclamation(Alert.ConstMessage.I_MustInput, "免除者データ");
                    this.grdDataViewer.Focus();
                    return blnCheckInput;
                }
                else
                {
                    DataTable dtbData = (DataTable)this.grdDataViewer.DataSource;
                    foreach (DataRow row in dtbData.Rows) row["組合員コード"] = this.ctrKumiaiinCdNm.txtCode.Text; //new row 組合員コード is empty

                    for (int intRow = 0; intRow < this.grdDataViewer.RowCount; intRow++)
                    {
                        for (int intCol = 0; intCol < this.grdDataViewer.Columns.Count; intCol++)
                        {
                            switch (this.grdDataViewer.Columns[intCol].Caption)
                            {
                                case "免除区分":
                                case "妊婦区分":
                                case "生年月日":
                                case "対象者氏名":
                                case "解除日":
                                case "登録日":
                                    if (Parser.ToString(this.grdDataViewer.Columns[intCol].CellValue(intRow)) == "")
                                    {
                                        Alert.Exclamation(Alert.ConstMessage.I_MustInput, (intRow + 1) + "行目の" + this.grdDataViewer.Columns[intCol].Caption);
                                        this.grdDataViewer.Row = intRow;
                                        this.grdDataViewer.Col = intCol;
                                        this.grdDataViewer.Focus();
                                        return blnCheckInput;
                                    }
                                    break;
                            }
                        }
                    }

                    var countData = dtbData.AsEnumerable()
                        .GroupBy(row => new
                        {
                            KumiaiinCd = row.Field<string>("組合員コード"),
                            MenjoKbn = row.Field<string>("免除区分"),
                            Birthday = row.Field<DateTime>("生年月日")
                        }
                        )
                        .Select(grp => new
                        {
                            KumiaiinCd = grp.Key.KumiaiinCd,
                            MenjoKbn = grp.Key.MenjoKbn,
                            Birthday = grp.Key.Birthday,
                            Count = grp.Count()
                        }).ToList();

                    for (int intRow = 0; intRow < countData.Count; intRow++)
                    {
                        if (countData[intRow].Count > 1)
                        {
                            Alert.Exclamation(Alert.ConstMessage.I_CodeExist, (intRow + 1) + "行目の主キー（免除区分、生年月日)");
                            this.grdDataViewer.Row = intRow;
                            this.grdDataViewer.Col = 0;
                            this.grdDataViewer.Focus();
                            return blnCheckInput;
                        }
                    }

                    using (DataTable dtbSearch = MenjoshaMaster.GetTableByKumiaiinCd(null, this.ctrKumiaiinCdNm.txtCode.Text))
                    {
                        if (this.lblShoriMode.Text == ConstSSS.ShoriMode.Add && (dtbSearch != null && dtbSearch.Rows.Count > 0))
                        {
                            Alert.Exclamation(Alert.ConstMessage.I_CodeExist, "免除者データ");
                            this.ctrKumiaiinCdNm.txtCode.Focus();
                            return blnCheckInput;
                        }
                    }
                }

                blnCheckInput = true;
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
            return blnCheckInput;
        }

        private bool F_TeiseiData_CheckInputBody()
        {
            bool blnCheckInput = false;
            try
            {

                if (Parser.ToString(this.txtTeiseiKikakuYear.Text) == "")
                {
                    Alert.Exclamation(Alert.ConstMessage.I_MustInput, "企画_年");
                    this.txtTeiseiKikakuYear.Focus();
                    return blnCheckInput;
                }

                if (Parser.ToString(this.txtTeiseiKikakuMTP.Text) == "")
                {
                    Alert.Exclamation(Alert.ConstMessage.I_MustInput, "企画_月・回・企画");
                    this.txtTeiseiKikakuMTP.Focus();
                    return blnCheckInput;
                }

                DateTime dateTeiseiKikaku = Parser.ToDateTime(this.txtTeiseiKikakuYear.Text + "/" + this.txtTeiseiKikakuMTP.Text.Substring(0, 2) + "/01");
                if (dateTeiseiKikaku.Day > 365)
                {
                    Alert.Exclamation(Alert.ConstMessage.I_1Year);
                    this.txtTeiseiKikakuYear.Focus();
                    return blnCheckInput;
                }

                if (string.IsNullOrEmpty(this.ctrKumiaiinCdNm.txtCode.Text))
                {
                    Alert.Exclamation(Alert.ConstMessage.I_MustInput, "組合員コード");
                    this.ctrKumiaiinCdNm.txtCode.Focus();
                    return blnCheckInput;
                }
                blnCheckInput = true;
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
            return blnCheckInput;
        }



        #endregion

        private void grdDataViewer_ButtonClick(object sender, C1.Win.C1TrueDBGrid.ColEventArgs e)
        {
            try
            {
                switch (this.grdDataViewer.Columns[e.ColIndex].DataField)
                {
                    case "商品コード":

                        using (F_YoyakuShohinMasterCond frmCond = new F_YoyakuShohinMasterCond(this.Jugyoin))
                        {
                            frmCond.GetSetItemSearch = new F_YoyakuShohinMasterCond.ItemSearch();
                            frmCond.lblShoriMode.Text = this.lblShoriMode.Text;
                            if (frmCond.ShowDialog() == DialogResult.OK)
                            {
                                if (frmCond.SelectedObject != null)
                                {
                                    this.grdDataViewer.UpdateData();
                                    // this.grdDataViewer.Columns["No"].Value = this.grdDataViewer.Row + 1;
                                    this.grdDataViewer.Columns["商品コード"].Value = frmCond.SelectedObject.ShohinCd;
                                    // this.grdDataViewer.Columns["商品名"].Value = frmCond.SelectedObject.ShohinNm;
                                    //this.grdDataViewer.Columns["注文"].Value = frmCond.SelectedObject.ChumonNo;
                                    SendKeys.Send("{TAB}");
                                }
                            }
                        }

                        break;
                }
            }
            catch (Exception ex)
            {
                Alert.Error(MethodInfo.GetCurrentMethod().DeclaringType.ToString() + "." + MethodInfo.GetCurrentMethod().Name + "でエラーが発生しました。" + Environment.NewLine + Environment.NewLine + ex.ToString());
            }
        }

    }
}
