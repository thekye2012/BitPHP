<style type="text/css">
	body, html { font-size: 0.9em; }
</style>
<p>Consulta generada: {{ $consulta_sql }}</p>
<p>Numero de resultados: {{ $numero_de_resultados }}</p>
<pre>{{ json_encode( $consulta, JSON_PRETTY_PRINT ) }}</pre>