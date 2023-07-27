<!DOCTYPE html>
<html>
<head>
    <title>Calculadora de Planos de Saúde</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Calculadora de Planos de Saúde</h1>
        <form id="formProposta">
            <label for="registro_plano">Registro do Plano:</label>
            <select name="registro_plano" id="registro_plano" required>
                <option value="">Selecione o plano</option>
                <!-- Opções de plano serão adicionadas dinamicamente via JavaScript -->
            </select>

            <label for="quantidade_beneficiarios">Quantidade de Beneficiários:</label>
            <input type="number" name="quantidade_beneficiarios" id="quantidade_beneficiarios" required>

            <div id="beneficiariosInput">
                <!-- Beneficiários serão adicionados dinamicamente via JavaScript -->
            </div>

            <button type="submit" id="calcularPreco">Calcular Preço</button> 
        </form>

        <div id="precoTotal"></div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
