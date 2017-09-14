<html>
<head>
    <title>USC Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <style type="text/css">
        * {
            font-size: 13px;
            overflow: visible !important;

            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        small {
            font-size: 10px;
        }

        h3 {
            font-size: 20px !important;
        }

        h5 {
            font-size: 10px !important;
        }

        .page-break {
            page-break-after: always;
            clear: both;
            display: block;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        body > table {
            margin-top: 10px;
        }

        table.border-cells,
        table.building-serial {
            border-collapse: collapse;
            border-spacing: 0;
        }

        .building-serial-container {
            padding: 0 !important;
            border: 1px solid black;
        }

        tr.border-cells td,
        td.border-cell,
        div.border-cell {
            border: 1px solid #000;
        }

        .small-text td,
        .small-text span,
        .small-text strong,
        .small-text div,
        ul.rto_terms li {
            font-size: 11px !important;
        }

        .dl-form-title {
            white-space: nowrap;
            text-align: right;
        }
    </style>
    @yield('pdf-style')
</head>
<body style="width: 800px;">
@yield('pdf-content')
</body>
</html>
