
$(document).ready(function () {
    LoadCarPayments();
 
    $(".txt-billing").change(function () {
        if ($(this).hasClass("txt-amount")) {
            let mode = ($(this).prop("id") == "txtCarAmount") ? 2 : 1;
            SetCarAmount(mode);
        }

        CalBillingFee();
        CalCarPayment();
    });

    $(".txt-change").change(function () {
        let idTr = $(this).parents("tr").prop("id");
        ChangeListIdAndName(idTr);
    });

    $(".form-check-input").each(function () {
        $(this).trigger("change");
        isChange = false;
    });
    HideProgress();

    // Đối tượng lưu trữ trạng thái của các checkbox
    const checkboxStates = {};

    // Lắng nghe sự kiện thay đổi của checkbox
    $('.custom-checkbox').change(function() {
        const checkboxId = $(this).attr('id');
        ChangeColorChkBox(checkboxStates, checkboxId, this);

      
    });
});

function ChangeColorChkBox(checkboxStates, checkboxId, ele){
    // Kiểm tra xem checkbox có tồn tại trong đối tượng checkboxStates chưa
    if (!checkboxStates.hasOwnProperty(checkboxId)) {
        checkboxStates[checkboxId] = {
            isChecked: false,
            isGreen: false
        };
    }
    const currentState = checkboxStates[checkboxId];
    if (currentState.isChecked) {
        if ($(ele).prop('checked')) {
            if (currentState.isGreen) {
                $(ele).next().css('background-color', 'red');
                if (!$(ele).hasClass("check-input-red")) $(ele).addClass("check-input-red");
                if ($(ele).hasClass("check-input-green")) $(ele).removeClass("check-input-green");
                currentState.isGreen = false;
            } else {
                $(ele).next().css('background-color', 'green');
                if (!$(ele).hasClass("check-input-green")) $(ele).addClass("check-input-green");
                if ($(ele).hasClass("check-input-red")) $(ele).removeClass("check-input-red");
                currentState.isGreen = true;
            }
        } else {
            $(ele).next().css('background-color', 'white');
            if ($(ele).hasClass("check-input-red")) $(ele).removeClass("check-input-red");
            if ($(ele).hasClass("check-input-green")) $(ele).removeClass("check-input-green");
        }
    } else {
        if ($(ele).prop('checked')) {
            $(ele).next().css('background-color', 'red');
            if (!$(ele).hasClass("check-input-red")) $(ele).addClass("check-input-red");
            if ($(ele).hasClass("check-input-green")) $(ele).removeClass("check-input-green");
            currentState.isChecked = true;
        } else {
            $(ele).next().css('background-color', 'white');
            if ($(ele).hasClass("check-input-red")) $(ele).removeClass("check-input-red");
            if ($(ele).hasClass("check-input-green")) $(ele).removeClass("check-input-green");
        }
    }
}
// Check input
function ChkInput() {
    if (!ChkValidateDay("txtRegisterDate")) return false;
    if (!ChkMaxLength("txtCarNumber")) return false;
    if (!ChkEmptyCmb("cmbUser")) return false;
    if (!ChkMaxLength("txtHeldNumber")) return false;
    if (!ChkMaxLength("txtEvaluation")) return false;
    if (!ChkMaxLength("txtCarName")) return false;
    if (!ChkMaxLength("txtCarInspection")) return false;
    if (!ChkEmpty("txtChassisNumber")) return false;
    if (!ChkHalfSizeAlpha("txtChassisNumber")) return false;
    if (!ChkMaxLength("txtMileage")) return false;
    if (!ChkMaxLength("txtCarHistory")) return false;
    if (!ChkEmpty("txtCarModel")) return false;
    if (!ChkMaxLength("txtFirstDate")) return false;
    if (!ChkMaxLength("txtGrade")) return false;
    if (!ChkMaxLength("txtOthers")) return false;
    if (!ChkMaxLength("txtCarNo")) return false;
    if (!ChkMaxLength("txtNewNumber")) return false;
    if (!ChkMaxLength("txtNameCopy1")) return false;
    if (!ChkMaxLength("txtNameCopy2")) return false;
    if (!ChkMaxLength("txtNote")) return false;
    if (!ChkMaxLength("txtCarAmount")) return false;
    if (!ChkMaxLength("txtConTax")) return false;
    if (!ChkMaxLength("txtCarTax")) return false;
    if (!ChkMaxLength("txtRDeposit")) return false;
    if (!ChkMaxLength("txtSelfTax")) return false;
    if (!ChkMaxLength("txtWinningBid")) return false;
    if (!ChkMaxLength("txtShippingFee")) return false;
    if (!ChkMaxLength("txtOtherFee")) return false;
    if (!ChkMaxLength("txtRepairFee")) return false;
    if (!ChkValidateDay("txtPaymentDate")) return false;
    if (!ChkMaxLength("txtExhibitTimes")) return false;
    if (!ChkDocListInput()) return false;
    if (!ChkPaymentInput()) return false;
    ChkDuplication();
    SubmitForm();
    return true;
}

// Check duplication
function ChkDuplication() {
    let url = $("#hidChkDup").val();
    let id = $("#hidId").val();
    let num = $("#txtChassisNumber").val();
    let model = $("#txtCarModel").val();
    let data = {
        number: num,
        model: model,
        id: id
    };
    CallAjax(url, data, function (results) {
        console.log(results.count);
        if (results.count > 0) {
            ShowAlert(msgRegDuplicate.replace(/0/g, "車両"), $("#txtChassisNumber"));
        } else {
            ShowDialog(msgConfirmSave, function(){
                SubmitForm();
            });
        }
    });
}

// Check doc list input
function ChkDocListInput() {
    let isOk = true;
    if ($("#chkDocDate").is(":checked")) isOk = ChkValidateDay("txtDocDate");

    if (isOk) {
        $(".chkSelect2").each(function () {
            const control = $(this);
            if (control.is(":checked")) {
                let id = GetElementId($(this).prop("id"));
                if (!ChkMaxLength("txtTextCon2_" + id)) {
                    isOk = false;
                    return isOk;
                }
            }
        });
    }

    if (isOk) {
        if ($("#chkAccessoryDate").is(":checked")) isOk = ChkValidateDay("txtAccessoryDate");
    }

    if (isOk) {
        $(".chkSelect3").each(function () {
            const control = $(this);
            if (control.is(":checked")) {
                let id = GetElementId($(this).prop("id"));
                if (!ChkMaxLength("txtTextCon3_" + id)) {
                    isOk = false;
                    return isOk;
                }
            }
        });
    }

    if (isOk) {
        if ($("#chkAccessoryOthers").is(":checked")) isOk = ChkMaxLength("txtAccessoryOthers");
    }

    return isOk;
}

// Check payment list input
function ChkPaymentInput() {
    let isOk = true;
    $("input[name^='txtSPaymentDate']:visible").each(function () {
        const control = $(this);
        if (!ChkValidateDay(control.prop("id"), "lblSPaymentDate")) {
            isOk = false;
            return isOk;
        }
    });

    if (isOk) {
        $("input[name^='txtSAuctionResult']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSAuctionResult")) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSCarNumber']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSCarNumber")) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSHeldNumber']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSHeldNumber")) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSExhibitNumber']:visible").each(function () {
            const control = $(this);
            const id = control.prop("id");
            let isError = !ChkMaxLength(control.prop("id"), "lblSExhibitNumber");
            if (isError) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSOthers']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSOthers")) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSCarAmount']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSCarAmount")) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSConTax']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSConTax")) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSRDeposit']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSRDeposit")) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSCarTax']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSCarTax")) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSExhibitFee']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSExhibitFee")) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSClosingFee']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSClosingFee")) {
                isOk = false;
                return isOk;
            }
        });
    }

    if (isOk) {
        $("input[name^='txtSReAuctionFee']:visible").each(function () {
            const control = $(this);
            if (!ChkMaxLength(control.prop("id"), "lblSReAuctionFee")) {
                isOk = false;
                return isOk;
            }
        });
    }

    return isOk;
}

// Enable edit element
function EnableEdit(ele, txtId, index) {
    $("#" + txtId).prop("readonly", !ele.checked);
    $("#" + txtId).prop("tabindex", -1);
    $("#" + txtId).addClass("shadow-none");
    if (ele.checked) {
        $("#" + txtId).removeClass("shadow-none");
        if (!$("#" + txtId).hasClass("visually-hidden")) $("#" + txtId).removeAttr("tabindex");
        //if (!$(ele).hasClass("check-red")) $(ele).addClass("check-input-green");
    }

    if (index && $("#linkFile" + index).length) {
        $("#linkFile" + index).addClass("pointer-none");
        $("#btnSelFile" + index).addClass("pointer-none");
        $("#btnDelFile" + index).addClass("pointer-none");
        $("#lblFile" + index).addClass("shadow-none");
        if (ele.checked) {
            $("#linkFile" + index).removeClass("pointer-none");
            $("#btnSelFile" + index).removeClass("pointer-none");
            $("#btnDelFile" + index).removeClass("pointer-none");
            $("#lblFile" + index).removeClass("shadow-none");
        }
    }
}

// Load Others
function LoadCarPayments() {
    let arrCarPayments = $("#hidCarPayments").val();
    if (arrCarPayments != "") {
        arrCarPayments = $.parseJSON(arrCarPayments);
        arrCarPayments.filter((item) => {
            AddRow(1, item);
        });
    }
}

// Add row to table
function AddRow(mode = 2, data = null) {
    let idTr = "trPayment_";
    let line = GetLastIdRow(idTr) + 1;
    let paymentDate = "";
    let auctionResult = "";
    let carNumber = "";
    let venue = "";
    let heldNumber = "";
    let exhibitNumber = "";
    let others = "";
    let carAmount = "";
    let conTax = "";
    let rDeposit = "";
    let carTax = "";
    let exhibitFee = "";
    let closingFee = "";
    let reAuctionFee = "";
    let subtotal = "";
    let isUpdate = false;

    if (data) {
        line = data.line;
        paymentDate = data.payment_date ?? "";
        auctionResult = data.auction_result ?? "";
        carNumber = data.car_number ?? "";
        venue = data.venue ?? "";
        heldNumber = data.held_number ?? "";
        exhibitNumber = data.exhibit_number ?? "";
        others = data.others ?? "";
        carAmount = data.car_amount ?? "";
        conTax = data.consumption_tax ?? "";
        rDeposit = data.r_deposit ?? "";
        carTax = data.car_tax ?? "";
        exhibitFee = data.exhibit_fee ?? "";
        closingFee = data.closing_fee ?? "";
        reAuctionFee = data.re_auction_fee ?? "";
        subtotal = data.payment_subtotal ?? "";
        isUpdate = true;
    }
    let modeNm = (mode == 2) ? "In" : "";
    let idLine = modeNm + "_" + line;
    idTr += line;
    let maxDate = SetMaxYearFromDate(Date.now());

    let tbdHtml = `<tr id="${idTr}">` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSPaymentDate${idLine}" name="txtSPaymentDate${modeNm}[]" type="date" autocomplete="off" value="${paymentDate}" maxlength="10"` +
                `min="${Date_Min_1}" max="${maxDate}" class="txt-change form-control border-radius-none border-0">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSAuctionResult${idLine}" name="txtSAuctionResult${modeNm}[]" type="text" autocomplete="off" maxlength="10" value="${auctionResult}"` +
                `class="txt-change form-control border-radius-none border-0">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
           `<input id="txtSCarNumber${idLine}" name="txtSCarNumber${modeNm}[]" type="text" autocomplete="off" maxlength="10" value="${carNumber}"` +
                `class="txt-change form-control border-radius-none border-0">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<select id="cmbSVenue${idLine}" name="cmbSVenue${modeNm}[]" class="txt-change form-select border-radius-none border-0"></select>` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSHeldNumber${idLine}" name="txtSHeldNumber${modeNm}[]" type="text" autocomplete="off" maxlength="10" value="${heldNumber}"` +
                `class="txt-change form-control border-radius-none border-0 allow_numeric">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
           `<input id="txtSExhibitNumber${idLine}" name="txtSExhibitNumber${modeNm}[]" onchange="LoadExhibitNum()" type="text" autocomplete="off" maxlength="10" value="${exhibitNumber}"` +
               `class="txt-change form-control border-radius-none border-0 allow_numeric">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSCarAmount${idLine}" name="txtSCarAmount${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${carAmount}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-${line}">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSConTax${idLine}" name="txtSConTax${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${conTax}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-${line}">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSRDeposit${idLine}" name="txtSRDeposit${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${rDeposit}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-${line}">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSOthers${idLine}" name="txtSOthers${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${others}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-${line}">` +
        `</td>` +
        `<td scope="row" class="p-0">`+
            `<input id="txtSCarTax${idLine}" name="txtSCarTax${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${carTax}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end payment-tax">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSExhibitFee${idLine}" name="txtSExhibitFee${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${exhibitFee}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-fee-${line}">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSClosingFee${idLine}" name="txtSClosingFee${modeNm}[]" onchange="LoadExhibitNum()" type="text" autocomplete="off" maxlength="13" value="${closingFee}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-fee-${line}">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSReAuctionFee${idLine}" name="txtSReAuctionFee${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${reAuctionFee}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-fee-${line}">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="lblSPaymentSubtotal${idLine}" name="lblSPaymentSubtotal${modeNm}[]" readonly type="text" autocomplete="off" value="${subtotal}" tabindex="-1"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end shadow-none txt-billing-total-${line} payment-total">` +
        `</td>` + 
        `<td scope="row" class="text-center w-c-50 border-0 border-bottom border-top border-white align-items-center align-middle p-0">` +
            `<button type="button" class="btn btn-none border-0 p-0" tabindex="-1" onclick="ShowConfirmDelRow(msgConfirmDel, '${idTr}', ${isUpdate}, SetCountAndCalculate)">` +
                `<i class="bi bi-x-circle-fill text-danger"></i>` +
            `</button>` +
            `<input id="hidPaymentLine${idLine}" name="hidPaymentLine${modeNm}[]" type="hidden" autocomplete="off" value="${line}" class="txt-change">` +
        `</td>` +
    `</tr>`;
    $("#tblPayments").append(tbdHtml);
    CloneCmbVenueToAnother(`cmbSVenue${idLine}`, venue);
    AddChangeEvent();
    CheckFormatInput();
    InitFormatInput();
    if (mode == 2) $(`#txtSPaymentDate${idLine}`).focus();

    $(`.txt-billing-${line}`).change(function () {
        CalListBillingFee(line);
        CalCarPayment();
    });

    $(`.txt-billing-fee-${line}`).change(function () {
        CalListBillingFee(line);
        CalCarPayment();
    });

    $(`.payment-tax`).change(function () {
        CalCarPayment();
    });

    if (!data) {
        let count = TryParseInt($("#txtExhibitTimes").val());
        $("#txtExhibitTimes").val(count + 1);
    }
    LoadExhibitNum();
}

// Set count line And calculate
function SetCountAndCalculate() {
    let count = TryParseInt($("#txtExhibitTimes").val());
    $("#txtExhibitTimes").val(count - 1);
    CalCarPayment();
    LoadExhibitNum();
}


// Clone combobox venue to another
function CloneCmbVenueToAnother(id, value) {
    var $options = $("#cmbVenue > option").clone();
    $('#' + id).append($options);
    $('#' + id).val(value);
}

// Set car amount
function SetCarAmount(mode = 1) {
    // 車両金額
    let carAmount = TryParseInt($("#txtCarAmount").val());
    // 加修費
    let repairFee = TryParseInt($("#txtRepairFee").val());

    if (mode == 1) {
        carAmount += repairFee;
        $("#txtCarAmount").val(carAmount);
    } else {
        if (carAmount <= repairFee) {
            $("#txtRepairFee").val("");
        }
    }
    InitFormatInput();
}

// Calculate billing fee
function CalBillingFee() {
    // 車両金額
    let carAmount = TryParseInt($("#txtCarAmount").val());
    // 消費税
    let conTax = TryParseInt($("#txtConTax").val());
    // 自動車税
    let carTax = TryParseInt($("#txtCarTax").val());
    // R預託金
    let rDeposit = TryParseInt($("#txtRDeposit").val());
    // 自税相当額
    let selfTax = TryParseInt($("#txtSelfTax").val());
    // 落札料
    let winningBid = TryParseInt($("#txtWinningBid").val());
    // 陸送料
    let shippingFee = TryParseInt($("#txtShippingFee").val());
    // その他
    let otherFee = TryParseInt($("#txtOtherFee").val());

    // 自動車代金小計
    let subtotal = carAmount + conTax + rDeposit + selfTax + winningBid + shippingFee + otherFee;
    $("#lblBillingSubtotal").val((subtotal != 0) ? subtotal : "");

    // 請求合計
    let total = subtotal + carTax;
    $("#lblBillingTotal").val((total != 0) ? total : "");
    InitFormatInput();
}


// Calculate billing fee in list
function CalListBillingFee(line) {
    let total = 0;
    $(".txt-billing-" + line).each(function () {
        let id = $(this).prop("id");
        let value = TryParseInt($(this).val());
        total += value;
    });
    $(".txt-billing-fee-" + line).each(function () {
        let id = $(this).prop("id");
        let value = TryParseInt($(this).val());
        total -= value;
    });
    $(".txt-billing-total-" + line).val((total != 0) ? total : "");
    InitFormatInput();
}

// Calculate car payment
function CalCarPayment() {
    // 自動車代金小計
    let subTotal = 0;
    $(".payment-total:visible").each(function () {
        let value = TryParseInt($(this).val());
        subTotal += value;
    });
    $("#lblPaymentSubtotal").val((subTotal != 0) ? subTotal : "");

    // 自動車税小計
    let tax = 0;
    $(".payment-tax:visible").each(function () {
        let value = TryParseInt($(this).val());
        tax += value;
    });
    $("#lblPaymentTax").val((tax != 0) ? tax : "");

    // 支払合計
    let total = subTotal + tax;
    $("#lblPaymentTotal").val((total != 0) ? total : "");

    // 利益
    let billSubTotal = TryParseInt($("#lblBillingSubtotal").val());
    let benefit = subTotal - billSubTotal;
    $("#lblBenefit").val((benefit != 0) ? benefit : "");
    InitFormatInput();
}

// Delete file
function DelFile(name) {
    ShowDialog(msgConfirmDel, function () {
        const tempPath = $("#tmp" + name).val();
        if (tempPath) {
            let url = $("#hidUrlDelFile").val();
            let data = {
                path: tempPath
            };
            CallAjax(url, data, function (result) {
                if (result.isSuccess) ClearFile(name);
            });
        } else ClearFile(name);
    });
}

/**削除の確認 */
function ConfirmDelCar() {
    let msg = msgConfirmDelCar.replace("0", $("#txtRegisterNo").val());
    ShowDialog(msg, function() { SubmitForm('form-main', true) });
}

function LoadExhibitNum(){
    let idTr = "trPayment_";
    let lineTr = GetLastIdRowVisible(idTr);
    let lblExhibitNum = "";

    for (let line = 1; line <= lineTr; line++) {
        let txtSClosingFee = getValInputTable("#txtSClosingFee",'_'+line);
        let txtSExhibitNumber = getValInputTable("#txtSExhibitNumber",'_'+line);
        if(txtSClosingFee === undefined) continue;
        if(txtSClosingFee != "" && txtSExhibitNumber != "")
        {
            lblExhibitNum = txtSExhibitNumber;
        }
    }
    $('#lblExhibitNum').val(lblExhibitNum);
}

function getValInputTable(name, line){
    let txtInput = $(name+line).val();
    let modeNm = ["In","Up"];
    if(txtInput === undefined){
        txtInput = $(name+modeNm[0]+line).val();
        if(txtInput === undefined){
            txtInput = $(name+modeNm[1]+line).val();
        }
    }
    return txtInput;
}

// Get id of last row
function GetLastIdRowVisible(idSrc) {
    let id = $("tr[id^='" + idSrc + "']:visible").last().attr("id");
    return id ? Number(GetElementId(id)) : 0;
}