<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>
        OS #{{ $maintenanceOrder->id }}
    </title>

    <style>

        @page {
            size: A4;
            margin: 14mm 14mm 12mm 14mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #111827;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.45;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .label {
            margin-bottom: 5px;
            font-size: 7.5px;
            font-weight: bold;
            letter-spacing: .7px;
            text-transform: uppercase;
            color: #64748b;
        }

        .value {
            font-size: 10.5px;
            font-weight: 600;
            color: #111827;
        }


        /*
        |--------------------------------------------------------------------------
        | CABEÇALHO
        |--------------------------------------------------------------------------
        */

        .header {
            padding-bottom: 18px;
            border-bottom: 1px solid #1f2937;
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: #111827;
        }

        .document-title {
            margin-top: 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: #111827;
        }

        .document-description {
            width: 350px;
            margin-top: 7px;
            font-size: 9px;
            line-height: 1.5;
            color: #64748b;
        }

        .os-container {
            text-align: right;
        }

        .os-box {
            display: inline-block;
            width: 135px;
            padding: 12px 14px;
            border: 1px solid #1f2937;
            text-align: center;
        }

        .os-label {
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #475569;
        }

        .os-number {
            margin-top: 3px;
            font-size: 25px;
            line-height: 1.1;
            font-weight: 800;
            color: #111827;
        }

        .os-divider {
            margin: 9px 0;
            border-top: 1px solid #cbd5e1;
        }

        .cycle {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: #334155;
        }


        /*
        |--------------------------------------------------------------------------
        | RESUMO DO ENVIO
        |--------------------------------------------------------------------------
        */

        .summary {
            margin-top: 18px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
        }

        .summary td {
            width: 33.33%;
            padding: 13px 15px;
            vertical-align: top;
        }

        .summary td + td {
            border-left: 1px solid #d1d5db;
        }


        /*
        |--------------------------------------------------------------------------
        | SEÇÕES
        |--------------------------------------------------------------------------
        */

        .section {
            margin-top: 22px;
        }

        .section-heading {
            width: 100%;
            padding-bottom: 7px;
            border-bottom: 1px solid #1f2937;
        }

        .section-number-cell {
            width: 26px;
            height: 22px;
            padding: 0;
            background: #111827;
            color: #ffffff;
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            font-weight: 700;
            line-height: 22px;
        }

        .section-title-cell {
            padding-left: 8px;
            vertical-align: middle;
        }

        .section-title {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: #111827;
        }


        /*
        |--------------------------------------------------------------------------
        | EQUIPAMENTO
        |--------------------------------------------------------------------------
        */

        .equipment {
            margin-top: 10px;
            border: 1px solid #d1d5db;
        }

        .equipment td {
            width: 50%;
            padding: 13px 15px;
            vertical-align: top;
            background: #ffffff;
        }

        .equipment tr + tr td {
            border-top: 1px solid #e2e8f0;
        }

        .equipment td + td {
            border-left: 1px solid #e2e8f0;
        }

        .equipment-name {
            font-size: 12px;
            font-weight: 700;
            color: #111827;
        }

        .asset {
            font-size: 12px;
            font-weight: 700;
            color: #111827;
        }


        /*
        |--------------------------------------------------------------------------
        | INFORMAÇÕES DO REPARO
        |--------------------------------------------------------------------------
        */

        .repair-box {
            margin-top: 10px;
            padding: 14px 16px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            page-break-inside: avoid;
        }

        .repair-box.current {
            background: #f8fafc;
        }

        .repair-text {
            margin-top: 10px;
            min-height: 38px;
            font-size: 10.5px;
            line-height: 1.6;
            color: #111827;
            white-space: pre-line;
            word-wrap: break-word;
        }

        .repair-box.current .repair-text {
            font-weight: 600;
        }


        /*
        |--------------------------------------------------------------------------
        | OBSERVAÇÃO
        |--------------------------------------------------------------------------
        */

        .observation {
            margin-top: 10px;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
        }

        .observation-text {
            margin-top: 7px;
            font-size: 9.5px;
            line-height: 1.5;
            white-space: pre-line;
        }


        /*
        |--------------------------------------------------------------------------
        | RODAPÉ
        |--------------------------------------------------------------------------
        */

        .footer {
            margin-top: 25px;
            padding-top: 9px;
            border-top: 1px solid #cbd5e1;
            text-align: center;
            font-size: 7.5px;
            color: #64748b;
        }

    </style>

</head>


<body>

@php

    /*
     * Primeiro envio da OS.
     *
     * É utilizado para recuperar o defeito
     * informado originalmente.
     */
    $initialShipment = $maintenanceOrder->shipments
        ->sortBy('sequence')
        ->first();

@endphp


{{-- =====================================================================
     CABEÇALHO
===================================================================== --}}

<table class="header">

    <tr>

        <td
            style="
                width: 65%;
                vertical-align: top;
            "
        >

            <div class="brand">
                PREVENTIVAS.
            </div>

            <div class="document-title">
                Reparo Externo
            </div>

            <div class="document-description">
                Acompanhamento do equipamento enviado
                para assistência técnica.
            </div>

        </td>


        <td
            style="
                width: 35%;
                vertical-align: top;
            "
            class="os-container"
        >

            <div class="os-box">

                <div class="os-label">
                    Ordem de Serviço
                </div>

                <div class="os-number">
                    #{{ $maintenanceOrder->id }}
                </div>

                <div class="os-divider"></div>

                <div class="cycle">
                    Ciclo de envio #{{ $latestShipment->sequence }}
                </div>

            </div>

        </td>

    </tr>

</table>


{{-- =====================================================================
     RESUMO DO ENVIO
===================================================================== --}}

<table class="summary">

    <tr>

        <td>

            <div class="label">
                Data do envio
            </div>

            <div class="value">
                {{ $latestShipment->sent_at?->format('d/m/Y H:i') ?? 'Não informado' }}
            </div>

        </td>


        <td>

            <div class="label">
                Empresa responsável
            </div>

            <div class="value">
                {{ $latestShipment->company?->name ?? 'Não informado' }}
            </div>

        </td>


        <td>

            <div class="label">
                Nota fiscal
            </div>

            <div class="value">
                {{ $latestShipment->invoice_number ?: 'Não informada' }}
            </div>

        </td>

    </tr>

</table>


{{-- =====================================================================
     IDENTIFICAÇÃO DO EQUIPAMENTO
===================================================================== --}}

<div class="section">

    <table class="section-heading">

        <tr>

            <td class="section-number-cell">
                01
            </td>

            <td class="section-title-cell">

                <span class="section-title">
                    Identificação do equipamento
                </span>

            </td>

        </tr>

    </table>


    <table class="equipment">

        <tr>

            <td>

                <div class="label">
                    Equipamento
                </div>

                <div class="equipment-name">
                    {{ $maintenanceOrder->equipment?->name ?? 'Não informado' }}
                </div>

            </td>


            <td>

                <div class="label">
                    Patrimônio
                </div>

                <div class="asset">
                    {{ $maintenanceOrder->equipment?->asset_number ?? 'Não informado' }}
                </div>

            </td>

        </tr>


        <tr>

            <td>

                <div class="label">
                    Categoria
                </div>

                <div class="value">
                    {{ $maintenanceOrder->equipment?->equipmentModel?->category?->name ?? 'Não informado' }}
                </div>

            </td>


            <td>

                <div class="label">
                    Filial de origem
                </div>

                <div class="value">
                    {{ $latestShipment->originBranch?->name ?? 'Não informado' }}
                </div>

            </td>

        </tr>

    </table>

</div>


{{-- =====================================================================
     INFORMAÇÕES DO REPARO
===================================================================== --}}

<div class="section">

    <table class="section-heading">

        <tr>

            <td class="section-number-cell">
                02
            </td>

            <td class="section-title-cell">

                <span class="section-title">
                    Informações do reparo
                </span>

            </td>

        </tr>

    </table>


    {{-- Defeito inicial --}}

    <div class="repair-box">

        <div class="label">
            Defeito informado no 1º envio
        </div>

        <div class="repair-text">

            {{ $initialShipment?->defect_description ?: 'Não informado.' }}

        </div>

    </div>


    {{-- Defeito do ciclo atual --}}

    <div class="repair-box current">

        <div class="label">
            Motivo / defeito deste envio
        </div>

        <div class="repair-text">

            {{ $latestShipment->defect_description ?: 'Não informado.' }}

        </div>

    </div>


    {{-- Observação --}}

    @if ($latestShipment->observation)

        <div class="observation">

            <div class="label">
                Observação
            </div>

            <div class="observation-text">
                {{ $latestShipment->observation }}
            </div>

        </div>

    @endif

</div>


{{-- =====================================================================
     RODAPÉ
===================================================================== --}}

<div class="footer">

    OS #{{ $maintenanceOrder->id }}

    &nbsp;&nbsp;•&nbsp;&nbsp;

    Ciclo #{{ $latestShipment->sequence }}

    &nbsp;&nbsp;•&nbsp;&nbsp;

    Sistema Preventivas

    &nbsp;&nbsp;•&nbsp;&nbsp;

    Emitido em {{ now()->format('d/m/Y H:i') }}

</div>


</body>

</html>
