<html>
<head>
	<title>Insert Test</title>
</head>
<body>
	<form target="response" method="POST" action="{{ $_APP_LINK }}crud_testing/insert/?action=post">
		<input name="nombre" type="text">
		<input name="msg" type="text">
		<button>Enviar</button>
	</form>
	<iframe name="response" width="100%" height="auto"></iframe>
</body>
</html>