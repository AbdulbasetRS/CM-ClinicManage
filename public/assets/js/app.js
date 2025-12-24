let lang = document.documentElement.lang; // هيجيب "ar" أو "en" أو غيره

// ✅ Apply global defaults for DataTables
$.extend(true, $.fn.dataTable.defaults, {
    language: {
        url: `/assets/libraries/dataTables/2.4.2/i18n/${lang}.json`,
    },
    pageLength: 10,
    dom: '<"top d-flex justify-content-between align-items-center"Bf>rt<"bottom d-flex justify-content-between align-items-center"lip>',
    buttons: [
        {
            extend: "copy",
            exportOptions: {
                columns: ":visible",
            },
            className: "btn btn-primary",
        },
        {
            extend: "excel",
            exportOptions: {
                columns: ":visible",
            },
            className: "btn btn-success",
        },
        {
            extend: "csv",
            exportOptions: {
                columns: ":visible",
            },
            className: "btn btn-info",
        },
        {
            extend: "pdf",
            exportOptions: {
                columns: ":visible",
            },
            className: "btn btn-danger",
        },
        {
            extend: "print",
            exportOptions: {
                columns: ":visible",
            },
            className: "btn btn-warning",
        },
        {
            extend: "colvis",
            className: "btn btn-dark",
        },
    ],
    lengthMenu: [
        [10, 20, 50, -1],
        [10, 20, 50, "All"],
    ],
    processing: true,
    serverSide: true,
    columnDefs: [
        {
            targets: -1,
            className: "dt-center",
        },
    ],
});


