<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$pageName = 'gameLobby'; 
?>
<!DOCTYPE HTML>
<html>
<head>
	<?PHP include_once('init.php'); ?>
	<script>
		var player = {
			account : '<?= $_SESSION["player"]["account"] ?>',
			nickname : '<?= $_SESSION["player"]["nickname"] ?>'
		};
		$(document).ready(function(){			
			$("#show_findGameRoom").button({
				icons: { primary: "ui-icon-search" }
			});
			$("#btn_createGameRoom").button({
				icons: { primary: "ui-icon-plus" }
			});
			
			$( "#gameName" ).selectmenu({width: 150});
			
			$('.toolBar input:text').button().css({
			       'outline' : 'none',
			        'cursor' : 'text',
			});
		});
	</script>
	<?= $this->script('game') ?>
</head>
<body>
	<div id="background">
		<?php include_once("header.php") ?>
		<div id="body">
			<div>
				<div>
					<div class="toolBar">
						<select id="gameName" name="gameName">
							<option disabled selected>依遊戲篩選</option>
							<option value="TicTacToe">井字遊戲</option>
							<option value="CowsAndBulls">猜數字</option>
						</select>
						<input id="gameId" type="text" placeholder="輸入遊戲室編號">
						<button id="show_findGameRoom" type="button">搜尋遊戲室</button>
						<button id="btn_createGameRoom" type="button">建立遊戲室</button>
					</div>
					
					<div class="games">
						<div class="aside">
							<h3>遊戲室列表</h3>
							<ul id="gameList">
								<li id="noConnection" class="messageBox">
									<label class="title">正在連接至遊戲伺服器...</label>
								</li>
								<li id="noGameRoom" class="messageBox">
									<label class="title">目前尚無遊戲室</label>
								</li>
								<li id="ex_room_0000" style="display: none">
									<label class="title">第&nbsp;0001&nbsp;室&nbsp;&nbsp;井字遊戲</label>
									<label class="playerName">殺很大殺很大</label>
									<span><a id="btn_joinGameRoom" href="javascript:void(0);">進入挑戰</a></span>
								</li>
							</ul>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<?php include_once("footer.php"); ?>
	</div>
	
	<input type="hidden" value="" name="G_gameName">
	<div id="createGameRoomSetting" title="建立遊戲室">
		<label>選擇遊戲</label>
		<select name="gameName" id="CGR_gameName">
			<option value="井字遊戲">井字遊戲</option>
			<option value="猜數字">猜數字</option>
		</select>
	</div>
	
	<div id="playRoom" title="第 0001 遊戲室 - 井字遊戲" style="display: none">
		<div class="player1Box">
			<label>你/妳</label>
			<img src="images/head/head_0.jpg">
			<label id="nickname">--等待對手加入--</label>
			<label id="msg">O</label>
			<label id="whosTurn">輪到你/妳囉!!</label>
		</div>
		<div class="gameController">
			<ul>
				<li id="table_1">O</li>
				<li id="table_4">&nbsp;</li>
				<li id="table_7">&nbsp;</li>
			</ul>
			<ul>
				<li id="table_2">&nbsp;</li>
				<li id="table_5">X</li>
				<li id="table_8">&nbsp;</li>
			</ul>
			<ul>
				<li id="table_3">&nbsp;</li>
				<li id="table_6">O</li>
				<li id="table_9">&nbsp;</li>
			</ul>
		</div>
		<div class="player2Box">
			<label>對手</label>
			<img src="images/head/head_0.jpg">
			<label id="nickname">--等待對手加入--</label>
			<label id="msg">X</label>
		</div>
	</div>
</body>
</html>