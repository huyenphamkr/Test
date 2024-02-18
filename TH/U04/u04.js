var isChangeValue = false;
$(document).ready(function () {
    LoadCarPayments();
    $(".txt-change").change(function () {
        let idTr = $(this).parents("tr").prop("id");
        ChangeListIdAndName(idTr);
        isChangeValue = true;
    });
    HideProgress();
    ChangeMenuLink();
    ChangePagination();
});

// Check input
function ChkInput() {
    if (ChkPaymentInput() && isChangeValue){
        ShowDialog(msgConfirmSave, function(){
            $("#hidMode").val("insert");
            SubmitForm();
        });
    }   
    return true;
}

// Check payment list input
function ChkPaymentInput() {
    let isOk = true;

    if (isOk) {
        $("select[name^='cmbSUser']:visible").each(function () {
            const control = $(this);
            if (!ChkEmptyCmb(control.prop("id"), "lblSUser")) {
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

    // if (isOk) {
    //     $("input[name^='txtSCarTax']:visible").each(function () {
    //         const control = $(this);
    //         if (!ChkMaxLength(control.prop("id"), "lblSCarTax")) {
    //             isOk = false;
    //             return isOk;
    //         }
    //     });
    // }

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
    let id = "";
    let line = GetLastIdRow(idTr) + 1;
    let paymentDate = "";
    let auctionResult = "";
    let carNumber = "";
    let carName =  "";
    let carModel = "";
    let carGrade = "";
    let chassisNumber = "";
    let userId =  "";
    let cCarAmount = "";
    let cBillingSubtotal = "";
    let CarNo = "";

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

    let carSubtotal ="";
    let PaymentTax = "";
    let PaymentTotal = "";
    let Benefit = "";

    if (data) {
        id = data.id;
        line = data.id+"-"+data.line;
        paymentDate = data.payment_date ?? "";
        auctionResult = data.auction_result ?? "";
        carNumber = data.ccar_number ?? "";
        carName = data.car_name ?? "";
        carModel = data.car_model ?? "";
        carGrade = data.grade ?? "";
        chassisNumber = data.chassis_number ?? "";
        userId = data.user_id ?? "";
        cCarAmount = data.ccar_amount ?? "";
        cBillingSubtotal = data.cbilling_subtotal ?? "";
        CarNo =  data.car_no.slice(0, 4) ?? "";

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

        carSubtotal = data.cpayment_subtotal;
        PaymentTax = data.payment_tax;
        PaymentTotal = data.payment_total;
        Benefit = data.benefit;
    }
    let modeNm = (mode == 2) ? "In" : "";
    let idLine = modeNm + "_" + line;
    idTr += line;
    let maxDate = SetMaxYearFromDate(Date.now());

    let tbdHtml = `<tr id="${idTr}">` +
        `<td scope="row" class="p-0 sticky-xxl-left left-position">` +
           `<input readonly tabindex="-1" id="txtSCarNumber${idLine}" name="txtSCarNumber${modeNm}[]" type="text" value="${carNumber}"` +
                `class="txt-change form-control border-radius-none border-0 shadow-none">` +
        `</td>` +
        `<td scope="row" class="p-0 sticky-xxl-left left-position">` +
           `<input readonly tabindex="-1" id="txtSCarName${idLine}" name="txtSCarName${modeNm}[]" type="text" value="${carName}"` +
                `class="txt-change form-control border-radius-none border-0 shadow-none">` +
        `</td>` +
        `<td scope="row" class="p-0 sticky-xxl-left left-position">` +
           `<input readonly tabindex="-1" id="txtSCarModel${idLine}" name="txtSCarModel${modeNm}[]" type="text" value="${carModel}"` +
                `class="txt-change form-control border-radius-none border-0 shadow-none">` +
        `</td>` +
        `<td scope="row" class="p-0 sticky-xxl-left left-position">` +
            `<input readonly tabindex="-1" id="txtSGrade${idLine}" name="txtSGrade${modeNm}[]" type="text" value="${carGrade}"` +
                `class="txt-change form-control border-radius-none border-0 shadow-none">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input readonly tabindex="-1" id="txtSChassisNumber${idLine}" name="txtSChassisNumber${modeNm}[]" type="text" value="${chassisNumber}"` +
                `class="txt-change form-control border-radius-none border-0 shadow-none">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<select id="cmbSUser${idLine}" name="cmbSUser${modeNm}[]" class="txt-change form-select border-radius-none border-0 cmb-user-${id}"></select>` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input readonly tabindex="-1" id="txtCarAmount${idLine}" name="txtCarAmount${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${cCarAmount}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money_minus text-end txt-amount shadow-none">` +
        `</td>` +
        `<td scope="row" class="p-0 d-none">` +
            `<input readonly tabindex="-1" id="lblBillingSubtotal${idLine}" name="lblBillingSubtotal${modeNm}[]" type="text" autocomplete="off" value="${cBillingSubtotal}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money_minus text-end shadow-none lblBillingSubtotal-${id}">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input readonly tabindex="-1" id="txtSCarNo${idLine}" name="txtSCarNo${modeNm}[]" type="text" value="${CarNo}"` +
                `class="txt-change form-control border-radius-none border-0 shadow-none">` +
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
        `<td scope="row" class="p-0 d-none">` +
            `<input id="txtSOthers${idLine}" name="txtSOthers${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${others}"  tabindex="-1"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-${line}">` +
        `</td>` +
        // `<td scope="row" class="p-0 d-none">`+
        //     `<input id="txtSCarTax${idLine}" name="txtSCarTax${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${carTax}"` +
        //         `class="txt-change form-control border-radius-none border-0 allow_money text-end payment-tax-${id}">` +
        // `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSExhibitFee${idLine}" name="txtSExhibitFee${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${exhibitFee}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-fee-${line}">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSClosingFee${idLine}" name="txtSClosingFee${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${closingFee}"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-fee-${line}">` +
        `</td>` +
        `<td scope="row" class="p-0">` +
            `<input id="txtSReAuctionFee${idLine}" name="txtSReAuctionFee${modeNm}[]" type="text" autocomplete="off" maxlength="13" value="${reAuctionFee}" tabindex="-1"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end txt-billing-fee-${line}">` +
        `</td>` +
        `<td scope="row" class="p-0 d-none">` +
            `<input id="lblSPaymentSubtotal${idLine}" name="lblSPaymentSubtotal${modeNm}[]" readonly type="text" autocomplete="off" value="${subtotal}" tabindex="-1"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end shadow-none txt-billing-total-${line} payment-total-${id}">` +
            `<input id="hidPaymentLine${idLine}" name="hidPaymentLine${modeNm}[]" type="hidden" autocomplete="off" value="${line}" class="txt-change">` +
            `<input id="hidSPaymentSubtotal${idLine}" name="hidSPaymentSubtotal${modeNm}[]" type="hidden" value="${subtotal}" class="hidSPaymentSubtotal-${id}">` +
        `</td>` + 

        `<td scope="row" class="p-0 d-none">` +
            `<input id="lblPaymentSubtotal${idLine}" name="lblPaymentSubtotal${modeNm}[]" readonly type="text" autocomplete="off" value="${carSubtotal}" tabindex="-1"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end shadow-none payment-sub-total-${id}">` +
            `<input id="carSubtotal${idLine}" name="carSubtotal${modeNm}[]" type="hidden" value="${carSubtotal}" tabindex="-1" class="car-subtotal-${id}">` +
        `</td>` + 
        `<td scope="row" class="p-0 d-none">` +
            `<input id="lblPaymentTax${idLine}" name="lblPaymentTax${modeNm}[]" readonly type="text" autocomplete="off" value="${PaymentTax}" tabindex="-1"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end shadow-none payment-tax-${id}">` +
        `</td>` + 
        `<td scope="row" class="p-0 d-none">` +
            `<input id="lblPaymentTotal${idLine}" name="lblPaymentTotal${modeNm}[]" readonly type="text" autocomplete="off" value="${PaymentTotal}" tabindex="-1"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end shadow-none payment-done-${id}">` +
        `</td>` + 
        `<td scope="row" class="p-0">` +
            `<input id="lblBenefit${idLine}" name="lblBenefit${modeNm}[]" readonly type="text" autocomplete="off" value="${Benefit}" tabindex="-1"` +
                `class="txt-change form-control border-radius-none border-0 allow_money text-end shadow-none benefit-done-${id}">` +
        `</td>` + 
        
// hiden
        `<td scope="row" class="p-0 d-none">` +
            `<input type="hidden" id="hidId${idLine}" name="hidId${modeNm}[]" value="${id}" class="txt-change">` +
            // `<input id="txtSPaymentDate${idLine}" name="txtSPaymentDate${modeNm}[]" type="date" autocomplete="off" value="${paymentDate}" maxlength="10"` +
            //     `min="${Date_Min_1}" max="${maxDate}" class="txt-change form-control border-radius-none border-0">` +
        `</td>` +
        // `<td scope="row" class="p-0 d-none">` +
        //     `<input id="txtSAuctionResult${idLine}" name="txtSAuctionResult${modeNm}[]" type="text" autocomplete="off" maxlength="10" value="${auctionResult}"` +
        //         `class="txt-change form-control border-radius-none border-0">` +
        // `</td>` +
        // `<td scope="row" class="p-0 d-none">` +
        //     `<select id="cmbSVenue${idLine}" name="cmbSVenue${modeNm}[]" class="txt-change form-select border-radius-none border-0"></select>` +
        // `</td>` +
        // `<td scope="row" class="p-0 d-none">` +
        //     `<input id="txtSHeldNumber${idLine}" name="txtSHeldNumber${modeNm}[]" type="text" autocomplete="off" maxlength="10" value="${heldNumber}"` +
        //         `class="txt-change form-control border-radius-none border-0 allow_numeric">` +
        // `</td>` +
        // `<td scope="row" class="p-0 d-none">` +
        //    `<input id="txtSExhibitNumber${idLine}" name="txtSExhibitNumber${modeNm}[]" type="text" autocomplete="off" maxlength="10" value="${exhibitNumber}"` +
        //        `class="txt-change form-control border-radius-none border-0 allow_numeric">` +
        // `</td>` +

    `</tr>`;
    $("#tblPayments").append(tbdHtml);
    AddChangeEvent();
    CheckFormatInput();
    InitFormatInput();

    $(`.txt-billing-${line}`).change(function () {
        isChangeValue = true;
        CalListBillingFee(line);
        CalCarPayment(id);
    });

    $(`.txt-billing-fee-${line}`).change(function () {
        isChangeValue = true;
        CalListBillingFee(line);
        CalCarPayment(id);
    });

    options = $("#cmbUser").html();
    $(`#cmbSUser${idLine}`).html(options);
    $(`#cmbSUser${idLine}`).val(userId);
    $(`.cmb-user-${id}`).change(function () {
        isChangeValue = true;
        CalListUser(id, this);
    });
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
function CalCarPayment(id) {
    //get value subTotal hidden
    let hidSubTotal = HandlHidTotal(id);

    // 自動車代金小計
    let subTotal = 0;
    $(".payment-total-" + id).each(function () {
        let value = TryParseInt($(this).val());
        subTotal += value;
    });

    subTotal += hidSubTotal;

    $(".payment-sub-total-" + id).each(function () {
        $(this).val((subTotal != 0) ? subTotal : "");
        console.log('lblPaymentSubtotal: '+subTotal);
    });

    // 自動車税小計
    let tax = 0;
    $(".payment-tax-" + id).each(function () {
        let value = TryParseInt($(this).val());
        tax = value;
        return false;
    });

    // 支払合計
    let total = subTotal + tax;
    $(".payment-done-" + id).each(function () {
        $(this).val((total != 0) ? total : "");
        console.log('lblPaymentTotal: '+total);
    });

    // 利益
    let billSubTotal = 0;
    $(".lblBillingSubtotal-" + id).each(function () {
        let value = TryParseInt($(this).val());
        billSubTotal = value;
        return false;
    });

    let benefit = subTotal - billSubTotal;
    $(".benefit-done-" + id).each(function () {
        $(this).val((benefit != 0) ? benefit : "");
    });

    InitFormatInput();
}

// Check input search
function ChkInputSearch () {
    if (!ChkEmpty("txtDate")) return false;
    if (ChkValidateDay("txtDate")) {
        ChkChangeValue(function() {
            $("#hidMode").val("search");
            SubmitForm();
        });
    }
    return true;
}


// change value in list user
function CalListUser(id, ele) {
    let value = $(ele).val();
    $(".cmb-user-" + id).each(function () {
        $(this).val(value);
    });
}

function HandlHidTotal(id){
    let subTotal = 0;
    //get sum car lblPaymentSubtotal 
    $(".car-subtotal-" + id).each(function () {
        let value = TryParseInt($(this).val());
        subTotal = value;
        return false;
    });
    // sum payment sub total current hidSPaymentSubtotal-id
    let subTotalCus = 0;
    $(".hidSPaymentSubtotal-" + id).each(function () {
        let value = TryParseInt($(this).val());
        subTotalCus += value;
    });
    //payment sub total hidden
    return subTotal - subTotalCus;
}


// Check change value
function ChkChangeValue(func) {
    if (isChangeValue) {
        ShowDialog(msgConfirmBack, function() {
            func();
        });
    } else {
        func();
    }
}

// Change menu link
function ChangeMenuLink() {
    $("a[id^='menu_']:not(.dropdown-toggle)").each(function() {
        let url = $(this).prop("href");
        $(this).prop("href", `javascript:ChkChangeValue(function() { window.location.href = '${url}' })`);
    });
}

// Change Pagination link
function ChangePagination() {
    $("a[class^='page-link']").each(function() {
        let url = $(this).prop("href");
        $(this).prop("href", `javascript:ChkChangeValue(function() { window.location.href = '${url}' })`);
    });
}
