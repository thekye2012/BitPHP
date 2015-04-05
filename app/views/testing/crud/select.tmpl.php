<style type="text/css">
	body, html { font-size: 0.9em; }
</style>
<p>Consulta generada: {{ $consulta }}</p>
<p>Numero de resultados: {{ $i_resultados }}</p>
<pre>{{ json_encode( $resultados, JSON_PRETTY_PRINT ) }}</pre>