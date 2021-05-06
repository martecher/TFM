<!DOCYPE html>
<html>
<head>
    <tittle>La tarea que solicitaste ya está en marcha</tittle>
</head>
<body>
<p>Nos alegramos de comunicarte que la tarea <strong>{{ $tarea->habilidad }}   ,  {{ $tarea->descripcion }} </strong> que solicitaste ya ha sido asignada. </p>

<p>El usuario que la realizará será <strong> {{ $tarea->usuarioRealiza }}</strong>. </p> 
<p>Recuerda acceder a la aplicación y compruebar el estado de la tarea. No olvides que puedes comunicarte con el usuario que la realizará a través de nuestro chat. </p>

<p>Saludos desde Ayudarte. </p>

</body>
</html>