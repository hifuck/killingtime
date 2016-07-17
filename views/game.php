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
		$(document).ready(function(){
			$("#show_findGameRoom").button({
				icons: { primary: "ui-icon-search" }
			});
			$("#show_createGameRoom").button({
				icons: { primary: "ui-icon-plus" }
			});
			
			$( "#gameName" ).selectmenu({width: 150});
			
			$('.toolBar input:text').button().css({
			       'outline' : 'none',
			        'cursor' : 'text',
			});
		});
		
	</script>
</head>
<body>
	<div id="background">
		<?php include_once("header.php") ?>
		<div id="body">
			<div>
				<div>
					<div class="toolBar">
						<select id="gameName">
							<option value="">依遊戲篩選</option>
							<option value="TicTacToe">井字遊戲</option>
							<option value="CowsAndBulls">猜數字</option>
						</select>
						<input id="gameId" type="text" placeholder="輸入遊戲室編號">
						<button id="show_findGameRoom" type="button">搜尋遊戲室</button>
						<button id="show_createGameRoom" type="button">建立遊戲室</button>
					</div>
					<div class="section">
						<div>
							<h3 style="text-align: center">~ 生命就該浪費在美好的時光 ~</h3>
							<div>
								<ul>
									<li>不可不知且絕無冷場的<strong>阿諾使我性格</strong>影片，你一定要看到最後！</li>
									<li>讓專家跌破眼鏡，<strong>糖伯虎</strong>從未被揭發的新聞，他是怎麼做到的？</li>
									<li>天啊！這居然是駭人聳聽的靈異現象我忍不住一直看下去...</li>
									<li><strong>威爾使蜜濕</strong>55個殘酷的人生領悟，接下來的畫面讓眾人震驚了！</li>
									<li>整整流傳兩世紀，<strong>安節力那求力</strong>不輕易透露的超強技巧，真是太天才了！</li>
									<li>這太神了！你一定得看，可愛到爆表的<strong>比餌蓋之</strong>，這不分享還是人嗎？</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include_once("footer.php"); ?>
	</div>
</body>
</html>