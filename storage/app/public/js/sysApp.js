/*
if('' == thema_system || null == thema_system){
    var _prefers_thema_system = 'light';
}else{
    var _prefers_thema_system = thema_system;
}

var prefers = _prefers_thema_system;
var html = document.querySelector('html');

html.classList.add(prefers);
html.setAttribute('data-bs-theme', prefers);
*/
const alertPreencher = ($message = null) => {
    let text = 'Field empty, please check!';
    if(null != $message){
        text = $message;
    }
    Swal.fire('Warning!',text,'warning')
}//fim função

const checkFieldFormAddValidClass = (selectorElement, alert = null, $message = null) => {
    let validaForm = document.querySelector(selectorElement)
    validaForm.classList.add("was-validated")

    if(null != alert){
        alertPreencher($message)
    }//fim if
}//fim função

const checkFieldFormValid = (field, atualValida) => {
    if(!field || '' == field || false == field || 0 == field){
        return false
    }else{
        return atualValida
    }//fim if
}//fim função

const reloadDataTables = (dataTable, className, tagSelector = '#', destroyTable = false) => {

    if(true == destroyTable){
        let table = $(`${tagSelector}${dataTable}`).DataTable();
        table.destroy();
    }

    let numberPageLength = 15;

    let simpleWithLenght = false;
    let simpleNoOrder = false;

    switch (className) {
        case 'simpledataTablesWithOptions5rows':
            numberPageLength = 5;
            break;
        case 'simpledataTablesWithOptions10rows':
            numberPageLength = 10;
            break;
        case 'simpledataTablesWithOptions100rows':
            numberPageLength = 100;
            break;
        case 'simpledataTables5row':
            numberPageLength = 5;
            simpleWithLenght = true;
            break;
        case 'simpledataTables10row':
            numberPageLength = 10;
            simpleWithLenght = true;
            break;
        case 'simpledataTables100row':
            numberPageLength = 100;
            simpleWithLenght = true;
            break;
            case 'simpledataTables100rowNoOrder':
                numberPageLength = 100;
                simpleNoOrder = true;
                simpleWithLenght = true;
                break;
        default:
            break;
    }

    if(true == simpleWithLenght && false == simpleNoOrder){
        $(`${tagSelector}${dataTable}`).DataTable({
            "order": [],
            "pageLength": numberPageLength,
            responsive: true,
        })
    }else if(true == simpleWithLenght && true == simpleNoOrder){
        $(`${tagSelector}${dataTable}`).DataTable({
            "order": [],
            "pageLength": numberPageLength,
            ordering: false,
            responsive: true,
        })

    }else if('simpledataTables' == className){
        $(`${tagSelector}${dataTable}`).DataTable({
            "order": [],
            responsive: true,
        })
    }else if('simpledataTablesWithOption' == className){
        $(`${tagSelector}${dataTable}`).each(function() {
            var tableName = $(this).data('download-name'); // Extrai o nome do atributo data-download-name
            var titleTableName = "download_allimaclenaing"; // Nome padrão para download

            if (tableName) {
                titleTableName = tableName;
            }

            $(this).DataTable({
                "order": [],
                responsive: true,
                dom: 'BQlfrtip',
                searchBuilder: true,
                "order": [],
                buttons: [{
                    text: 'Download Excel',
                    extend: 'excelHtml5',
                    title: titleTableName,
                    init: function(dt, node, config) {
                        $(node).removeClass('dt-button').addClass('btn btn-outline-success waves-effect waves-light material-shadow-none'); // Remove 'dt-button' e adiciona suas classes
                    }
                }]
            });
        });
    }else if('simpledataTablesWithOptionNoResponsive' == className){
        $(`${tagSelector}${dataTable}`).each(function() {
            var tableName = $(this).data('download-name'); // Extrai o nome do atributo data-download-name
            var titleTableName = "download_allimaclenaing"; // Nome padrão para download

            if (tableName) {
                titleTableName = tableName;
            }

            $(this).DataTable({
               "order": [],
                dom: 'BQlfrtip',
                searchBuilder: true,
                responsive: false, // remove responsividade que encurta
                scrollX: true, // Adiciona a rolagem lateral
                "order": [],
                buttons: [{
                    text: 'Download Excel',
                    extend: 'excelHtml5',
                    title: titleTableName,
                    init: function(dt, node, config) {
                        $(node).removeClass('dt-button').addClass('btn btn-outline-success waves-effect waves-light material-shadow-none'); // Remove 'dt-button' e adiciona suas classes
                    }
                }]
            });
        });
    }else{
        $(`${tagSelector}${dataTable}`).each(function() {
            var tableName = $(this).data('download-name'); // Extrai o nome do atributo data-download-name
            var titleTableName = "download_allimaclenaing"; // Nome padrão para download

            if (tableName) {
                titleTableName = tableName;
            }

            $(this).DataTable({
                "order": [],
                responsive: true,
                dom: 'Bfrtip',
                pageLength: numberPageLength,
                buttons: [{
                    text: 'Download Excel',
                    extend: 'excelHtml5',
                    title: titleTableName,
                    init: function(dt, node, config) {
                        $(node).removeClass('dt-button').addClass('btn btn-outline-success waves-effect waves-light material-shadow-none'); // Remove 'dt-button' e adiciona suas classes
                    }
                }]
            });
        });
    }
}//fim função

const verificaSeCelularDevice = () => {
    if( navigator.userAgent.match(/Android/i)
    || navigator.userAgent.match(/webOS/i)
    || navigator.userAgent.match(/iPhone/i)
    || navigator.userAgent.match(/iPad/i)
    || navigator.userAgent.match(/iPod/i)
    || navigator.userAgent.match(/BlackBerry/i)
    || navigator.userAgent.match(/Windows Phone/i)
    ){
        return true; // está utilizando celular
    }
    else {
        return false; // não é celular
    }
}

const mudaHideShowElemento = (Selector, btn = null, txtShow = null, txtHide = null) =>{
    let el = document.querySelector(`${Selector}`);
    let elBtn;

    if(null != btn){
        elBtn = document.querySelector(`${btn}`);
    }

    if('block' == el.style.display){
        el.style.display = 'none'
        if(null != btn && null != txtShow && null != txtHide){
            removeClass(elBtn, 'btn-secondary')
            addClass(elBtn, 'btn-info')
            elBtn.innerHTML = `${txtShow}`
        }
    }else{
        el.style.display = 'block'
        if(null != btn && null != txtShow && null != txtHide){
            removeClass(elBtn, 'btn-info')
            addClass(elBtn, 'btn-secondary')
            elBtn.innerHTML = `${txtHide}`
        }
    }
}//fim função

const hasClass = (el, className) => {
    if (el.classList)
        return el.classList.contains(className)
    else
        return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'))
}

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
})//fim funcao

const addClass = (el, className) => {
    if (el.classList)
        el.classList.add(className)
    else if (!hasClass(el, className)) el.className += " " + className
}

const removeClass = (el, className) => {
    if (el.classList)
        el.classList.remove(className)
    else if (hasClass(el, className)) {
        var reg = new RegExp('(\\s|^)' + className + '(\\s|$)')
    el.className=el.className.replace(reg, ' ')
    }
}

const removeAndAddClass = (el, className) => {
    removeClass(el, className)
    addClass(el, className)
}

const bloquear = () => {
    $.blockUI({
        css: {
            border: "none",
            padding: "0px",
            backgroundColor: "transparent",
            color: "#fff",
            "z-index": 9999
        },
        message:
            `<img src="${base_URL}/img/loadingC.svg" class="ld ld-beat"/>`
    })
}

const desbloquear = () => {
    $.unblockUI();
}

bloquear();

window.onload = function posLoad() {
    desbloquear();
}

let getInputMaskMoneyNoMAsk = (elementSelector) => {
    let num = $(elementSelector).maskMoney('unmasked')[0];
    return num;
}//fim função

let addMaskMoney = (elementSelector) => {
    $(elementSelector).maskMoney('mask');
}//fim função

$(document).on('click','.bloqueiaClick', function(){
    bloquear();
});

let loadPlugins = () => {
    // Masks
    $('.datepicker').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        orientation: "bottom auto",
        todayHighlight: true
    });

    $('.datepicker-multi-data').datepicker({
        format: 'mm/dd/yyyy',
        orientation: "bottom auto",
        multidate: true,
        todayHighlight: true
    });

    $('#divFormFilter .input-daterange').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        orientation: "bottom auto",
        todayHighlight: true
    });

    $('#divFilterDatesEmployeePayments .input-daterange').datepicker({
        format: 'mm/dd/yyyy',
        autoclose: true,
        orientation: "bottom auto",
        todayHighlight: true
    });

    $(".select2").each(function() {
        $(this)
            .wrap("<div class=\"position-relative\"></div>")
            .select2({
                placeholder: "Select Option",
                allowClear: true,
                dropdownParent: $(this).parent()
            });
    });

    $('.phoneMask').inputmask("(999) 999-9999");
    $('.postalCodeMask').inputmask("99999");  //static mask
    $('.dataMask').inputmask("99/99/9999");  //static mask
    $('.dataRangeMask').inputmask("99/99/9999 - 99/99/9999");  //static mask
    //$('.moneyMask').inputmask('000.000.000.000.000,00', {reverse: true});// static mask money
    //mask money
    $('.moneyMask').maskMoney({
        prefix:'US$ '
    });

    $('.emailMask').inputmask({
        mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
        greedy: false,
        onBeforePaste: function (pastedValue, opts) {
          pastedValue = pastedValue.toLowerCase();
          return pastedValue.replace("mailto:", "");
        },
        definitions: {
          '*': {
            validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
            casing: "lower"
          }
        }
      });
  }//fim função loadPlugins

$(document).ready(function() {

    var masks = loadPlugins()

    // DataTable
    var simpletable = $('.simpledataTables').DataTable({
        "order": [],
        responsive: true,
    })

    // DataTable
    var simpletable = $('.simpledataTablesWithOption').each(function() {
        var tableName = $(this).data('download-name'); // Extrai o nome do atributo data-download-name
        var titleTableName = "download_allimaclenaing"; // Nome padrão para download

        if (tableName) {
            titleTableName = tableName;
        }

        $(this).DataTable({
            "order": [],
            responsive: true,
            dom: 'BQlfrtip',
            searchBuilder: true,
            buttons: [{
                text: 'Download Excel',
                extend: 'excelHtml5',
                title: titleTableName,
                init: function(dt, node, config) {
                    $(node).removeClass('dt-button').addClass('btn btn-outline-success waves-effect waves-light material-shadow-none'); // Remove 'dt-button' e adiciona suas classes
                }
            }]
        });
    });

    // DataTable
    var simpletable = $('.simpledataTablesWithOptionNoResponsive').each(function() {
        var tableName = $(this).data('download-name'); // Extrai o nome do atributo data-download-name
        var titleTableName = "download_allimaclenaing"; // Nome padrão para download

        if (tableName) {
            titleTableName = tableName;
        }

        $(this).DataTable({
            "order": [],
            dom: 'BQlfrtip',
            searchBuilder: true,
            responsive: false, // remove responsividade que encurta
            scrollX: true, // Adiciona a rolagem lateral,
            buttons: [{
                text: 'Download Excel',
                extend: 'excelHtml5',
                title: titleTableName,
                init: function(dt, node, config) {
                    $(node).removeClass('dt-button').addClass('btn btn-outline-success waves-effect waves-light material-shadow-none'); // Remove 'dt-button' e adiciona suas classes
                }
            }]
        });
    });

    // DataTable
    var simpletable5rows = $('.simpledataTablesWithOptions5rows').each(function() {
        var tableName = $(this).data('download-name'); // Extrai o nome do atributo data-download-name
        var titleTableName = "download_allimaclenaing"; // Nome padrão para download

        if (tableName) {
            titleTableName = tableName;
        }

        $(this).DataTable({
            "order": [],
            responsive: true,
            dom: 'Bfrtip',
            pageLength: 5,
            buttons: [{
                text: 'Download Excel',
                extend: 'excelHtml5',
                title: titleTableName,
                init: function(dt, node, config) {
                    $(node).removeClass('dt-button').addClass('btn btn-outline-success waves-effect waves-light material-shadow-none'); // Remove 'dt-button' e adiciona suas classes
                }
            }]
        });
    });

    var simpletable10rows = $('.simpledataTablesWithOptions10rows').each(function() {
        var tableName = $(this).data('download-name'); // Extrai o nome do atributo data-download-name
        var titleTableName = "download_allimaclenaing"; // Nome padrão para download

        if (tableName) {
            titleTableName = tableName;
        }

        $(this).DataTable({
            "order": [],
            responsive: true,
            dom: 'Bfrtip',
            pageLength: 10,
            buttons: [{
                text: 'Download Excel',
                extend: 'excelHtml5',
                title: titleTableName,
                init: function(dt, node, config) {
                    $(node).removeClass('dt-button').addClass('btn btn-outline-success waves-effect waves-light material-shadow-none'); // Remove 'dt-button' e adiciona suas classes
                }
            }]
        });
    });

    var simpletable100rows = $('.simpledataTablesWithOptions100rows').each(function() {
        var tableName = $(this).data('download-name'); // Extrai o nome do atributo data-download-name
        var titleTableName = "download_allimaclenaing"; // Nome padrão para download

        if (tableName) {
            titleTableName = tableName;
        }

        $(this).DataTable({
            "order": [],
            responsive: true,
            dom: 'Bfrtip',
            pageLength: 100,
            buttons: [{
                text: 'Download Excel',
                extend: 'excelHtml5',
                title: titleTableName,
                init: function(dt, node, config) {
                    $(node).removeClass('dt-button').addClass('btn btn-outline-success waves-effect waves-light material-shadow-none'); // Remove 'dt-button' e adiciona suas classes
                }
            }]
        });
    });

    // DataTable
    var simpletable100row = $('.simpledataTables100row').DataTable({
        "order": [],
        "pageLength": 100,
        responsive: true,
    })

    var simpletable100row = $('.simpledataTables100rowNoOrder').DataTable({
        "order": [],
        "pageLength": 100,
        ordering: false,
        responsive: true,
    })

    var tableFiltroInputColumn = $('.simpleDataTablesFilterByInputColumn').dataTable( {
        "order": [],
        responsive: true,
        initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                    let column = this;
                    let title = column.footer().textContent;

                    // Create input element
                    let input = document.createElement('input');
                    input.classList.add("form-control");
                    input.placeholder = `Filtrar: ${title}`;
                    column.footer().replaceChildren(input);

                    // Event listener for user input
                    input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                });
        }
    });//fim datatables filtro

    var tableFiltroInput = $('.dataTablesFilterByInputColumn').dataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ],
        "order": [],
        responsive: true,
        initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                    let column = this;
                    let title = column.footer().textContent;

                    // Create input element
                    let input = document.createElement('input');
                    input.classList.add("form-control");
                    input.placeholder = `Filtrar: ${title}`;
                    column.footer().replaceChildren(input);

                    // Event listener for user input
                    input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                });
        }
    });//fim datatables filtro
});

