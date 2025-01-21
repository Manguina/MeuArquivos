<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura-Recibo</title>
    <style type="text/css">
        body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
}

.invoice {
    width: 210mm;
    padding: 20mm;
    margin: auto;
    background: #fff;
    border: 1px solid #ddd;
}

.header {
    display: flex;
    justify-content: space-between;
    border-bottom: 2px solid #000;
    padding-bottom: 10px;
}

.company-info, .invoice-details {
    width: 45%;
}

.client-info {
    margin: 20px 0;
    background: #f9f9f9;
    padding: 10px;
    border-radius: 5px;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.items-table th, .items-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.summary {
    display: flex;
    justify-content: space-between;
}

.footer {
    text-align: center;
    font-size: 0.8em;
    color: #666;
}

button {
    margin: 20px;
    padding: 10px 20px;
    background: #007BFF;
    color: #fff;
    border: none;
    cursor: pointer;
}

    </style>
</head>
<body>
    <div class="invoice" id="invoice">
        <div class="header">
            <div class="company-info">
                <h1>DELCOOM INVESTIMENTOS LDA</h1>
                <p>Contribuinte: 5000238384</p>
                <p>Telefone: 923456789</p>
                <p>LUANDA, VILA DE VIANA, RUA DOS BANCOS</p>
            </div>
            <div class="invoice-details">
                <h2>FACTURA RECIBO</h2>
                <p>Número: FR DI2024/26</p>
                <p>Data de Emissão: 02-12-2024</p>
                <p>Hora: 15:21:54</p>
                <p>Vencimento: 09-12-2024</p>
                <p>Contribuinte: 5001187090</p>
            </div>
        </div>

        <div class="client-info">
            <strong>Exmo.(s) Sr.(s):</strong>
            <p>Para gestão consultoria investimentos e formação limitada</p>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Preço Uni.</th>
                    <th>Qtd</th>
                    <th>Desconto</th>
                    <th>Taxa Imp.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SOFTWARE QUIANNI LICENÇA ANUAL</td>
                    <td>45.000,00</td>
                    <td>1</td>
                    <td>0,00</td>
                    <td>0,00</td>
                    <td>45.000,00</td>
                </tr>
            </tbody>
        </table>

        <div class="summary">
            <div class="tax-summary">
                <h3>Quadro Resumo de Imposto</h3>
                <p>ISENTO | Taxa: 0% | Incidência: 45.000,00 | Imposto: 0,00</p>
            </div>

            <div class="financial-summary">
                <p><strong>Total Ilíquido:</strong> 45.000,00</p>
                <p><strong>Total Imposto:</strong> 0,00</p>
                <p><strong>Total (Kz):</strong> 45.000,00</p>
                <p><strong>Por Extenso:</strong> QUARENTA E CINCO MIL KWANZAS</p>
            </div>
        </div>

        <div class="footer">
            <p>Oh3K - Processado por programa validado nº 77/AGT/2019 Software Quianni (5.7.7.7 B1)</p>
        </div>
    </div>
    <script type="text/javascript">
        function printInvoice() {
    const printContents = document.getElementById('invoice').innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}

    </script>
</body>
</html>
