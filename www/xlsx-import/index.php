<form action="/xlsx-import/med_importar.php" method="get">
<hr />
<label for="conexao"> Conexão
<select name="conexao" id="conexao">
    <option value="local">Local</option>
    <option value="producao">Produção</option>
</select>
</label>

<br />
<label for="tipo"> Tipo
<select name="tipo" id="tipo">
    <option value="med">Medicamento</option>
    <option value="mat">Material</option>
</select>
</label>

<br />
<label for="versao">Versão
<input type="text" name="versao" id="versao" />
</label>

<button type="submit">Enviar</button>
</form>
