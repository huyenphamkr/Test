//F_KojinHaitatsuTesuryoMuryoKaisuHenko
private void F_HaitokinTsuchishoShokai_btnHeader04_Click(object sender, EventArgs e)
{

    this.ctrKumiaiinCdNm.SelectedObject.JigyoshoCd
     if (Parser.ToString(this.txtYear.Text) != "" && !string.IsNullOrEmpty(this.ctrKumiaiinCdNm.txtCode.Text))
    {
        this.clsHaitokinTsuchishoShokai = HaitokinTsuchishoShokai.GetInstance(this.txtYear.Text, this.ctrKumiaiinCdNm.tctCode.Text, this.ctrKumiaiinCdNm.SelectedObject.JigyoshoCd);
        if (this.clsHaitokinTsuchishoShokai == null)
        {
            Alert.Exclamation(Alert.ConstMessage.E_NoData);
            this.txtYear.Focus();
            return;
        }
    }
    else
    {
        this.F_HaitokinTsuchishoShokai_OpenCondForm();
        return;
    }

    this.F_HaitokinTsuchishoShokai_GetData();
    this.F_HaitokinTsuchishoShokai_SetShoriMode();

}