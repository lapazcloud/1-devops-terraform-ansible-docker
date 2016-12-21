<?php
require_once('./redis.php');
$redis = new redis_cli ( 'redis', 6379 );
function addVote() {
	global $redis;
	$equipo = $_POST["equipo"];
	$redis->cmd('INCR',$equipo)->set();
	//$redis->cmd('INCR','tigre')->set();
	echo "<header>Gracias por participar. Puedes votar las veces que quieras.</header>";
}
function showVotes() {
	global $redis;

	$votos_tigre = $redis->cmd('GET','tigre')->get();
	$votos_tigre = $votos_tigre >= 1 ? $votos_tigre : 0 ;

	$votos_bolivar = $redis->cmd('GET','bolivar')->get();
	$votos_bolivar = $votos_bolivar >= 1 ? $votos_bolivar : 0 ;

	echo "<li>$votos_tigre - The Strongest</li>";
	echo "<li>$votos_bolivar - Bolívar</li>";
}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<title>Bolívar vs. The Strongest</title>
		<link rel="stylesheet" href="normalize.min.css" media="screen">
		<link rel="stylesheet" href="estilos.css" media="screen">
	</head>
	<body>
		<?php if (!empty($_POST["equipo"])) {addVote();} ?>
		<div id='wrapper'>
			<h1>Bolívar vs. The Strongest!</h1>
			<ul>
			<form class="" action="/" method="post">
				<li class="tigre">
					<input id="tigre" type="radio" name="equipo" onclick="this.form.submit();" value="tigre">
					<label for="tigre">The Strongest</label>
				</li>
				<li class="bolivar">
					<input id="bolivar" type="radio" name="equipo" onclick="this.form.submit();" value="bolivar">
					<label for="bolivar">Bolívar</label>
				</li>
			</form>
			</ul>
			<h2>Resultados de la votación</h2>
			<ul>
				<?php showVotes(); ?>
			</ul>
			<footer>
				<p>
					Procesado por el contenedor con ID <br>
					<?php echo getenv('HOSTNAME');?>
				</p>
			</footer>
		</div>
	</body>
</html>
