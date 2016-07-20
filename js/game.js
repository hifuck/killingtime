var dataBox = {
action : '',
data : ''
}
$(document).ready(function(){
	$(window).bind('beforeunload', function(){
		return '離開此頁面，將與遊戲大廳中斷連線，並不會儲存目前的遊戲紀錄。';
	});
	
	$('#noConnection').show();
	$('#playRoom').dialog({ autoOpen: false});
	
	var conn = new WebSocket('ws://localhost:8080');
	conn.onopen = function(e) {
		alertMsg("提示訊息", "遊戲伺服器連接成功！");
		$('#noConnection').hide();

		dataBox.action = 'setPlayer';
		dataBox.data = player;
		sendData(dataBox);
	};

	conn.onmessage = function(e) {
		var dataBox = $.parseJSON( e.data );
		var data = null;
		console.log("GET------dataBox-");	
		console.log(dataBox);
		if(dataBox.data != 'null'){
			data = $.parseJSON( dataBox.data );
			console.log("GET------data-");	
			console.log(data);
		}
		console.log("----------------------");	

		if(dataBox.action == 'refreshGameRoom'){
			if(data == "null" || data.length <= 0){
				$('#noConnection').hide();
				$('li[id^=room_]').each(function(){
					$(this).remove();
				});
				$('#noGameRoom').show();
			}else{
				$('#noConnection').hide();
				$('#noGameRoom').hide();
				$('li[id^=room_]').each(function(){
					$(this).remove();
				});
				$.each(data, function () {
					if(this.player1 == null || this.player2 == null){
						var playerName = "";
						var palyerAccount = "";
						if(this.player1 != null){
							palyerAccount = this.player1.account;
							playerName = this.player1.nickname;
						}
						if(this.player2 != null){
							palyerAccount = this.player2.account;
							playerName = this.player2.nickname;
						}
						
						var newGameRoom = $('#ex_room_0000').clone();
						$(newGameRoom).attr('id', 'room_' + this.roomID);
						$(newGameRoom).find('label[class=title]').text('第 '+ this.roomID +' 室  '+ this.gameName);
						$(newGameRoom).find('label[class=playerName]').text(playerName);
						$('#gameList').append(newGameRoom);
						$('#room_'+ this.roomID).show();
					}
				});
			}
		}
		
		if(dataBox.action == 'sendMessage'){
			$('#chatMessage').append("<label class='guestName'>"+data.nickname+"</label><label class='message'>"+data.msg+"</label>");
			$("#chatMessage").animate({ scrollTop:  $('#chatMessage').prop("scrollHeight") }, 500);
		}
		
		if(dataBox.action == 'joinGameRoom'){
			if ($('#playRoom').dialog('isOpen') === true) {
				$('#playRoom').find('#other_nickname').text(data.player2.nickname);
				$('#playRoom').find('#other_img').attr("src", "images/head/"+data.player2.account+".jpg");
				$('#btn_sendMessage').button({disabled: false});
			} else {
				$("#playRoom").dialog({
					title: "第 " + data.roomID + " 遊戲室 - " + data.gameName,
					modal: true,
					resizable: false,
					draggable: false,
					autoOpen: true,
					height: 750,
					width: 950,
					open: function() {
							$(this).find('#you_nickname').text(data.player2.nickname);
							$(this).find('#you_img').attr("src", "images/head/"+data.player2.account+".jpg");
							$(this).find('#other_nickname').text(data.player1.nickname);
							$(this).find('#other_img').attr("src", "images/head/"+data.player1.account+".jpg");
							
							$(this).find('#btn_sendMessage').button({disabled: false});
							$(this).find('#btn_sendMessage').attr("name", "roomID_" +data.roomID);
					},
					buttons: {
						"離開": function() {
							dataBox.action = 'leaveRoom';
							dataBox.data = {'roomID' : data.roomID , 'player' : player };
							sendData(dataBox);
							$( this ).dialog( "close" );
							$("#chatMessage").html("");
						}
					}
				});
			}
		}
		
		if(dataBox.action == 'createRoom'){			
			$("#playRoom").dialog({
				title: "第 " + data.roomID + " 遊戲室 - " + data.gameName,
				modal: true,
				resizable: false,
				draggable: false,
				autoOpen: true,
				height: 750,
				width: 950,
				open: function() {
						$(this).find('#you_nickname').text(data.player1.nickname);
						$(this).find('#you_img').attr("src", "images/head/"+data.player1.account+".jpg");
						
						$(this).find('#btn_sendMessage').button({disabled: true});
						$(this).find('#btn_sendMessage').attr("name", "roomID_" +data.roomID);
				},
				buttons: {
					"離開": function() {
						dataBox.action = 'leaveRoom';
						dataBox.data = {'roomID' : data.roomID , 'player' : player };
						sendData(dataBox);
						$( this ).dialog( "close" );
						$("#chatMessage").html("");
					}
				}
			});
		}
		
		if(dataBox.action == 'anotherOneleft'){
			alert("對手已離開遊戲室");
			$("#playRoom").dialog('close');
			$("#chatMessage").html("");
		}
	};
	
	conn.onerror = function(e){
		alertMsg("錯誤訊息", "無法連接至遊戲伺服器");
		$('#noConnection label').text("無法連接至遊戲伺服器");
		$('#noConnection').show();
		$('#noGameRoom').hide();
		$('li[id^=room_]').each(function(){
			$(this).remove();
		});
	}
	
	function sendData(data){
		conn.send(JSON.stringify(data));
		dataBox = {
			action : '',
			data : ''
		}
	}
	
	$("#createGameRoomSetting").dialog({
		modal: true,
		autoOpen: false,
		resizable: false,
		height: 250,
		buttons: {
			"取消": function() {
				$( this ).dialog( "close" );
			},
			"建立": function() {
				dataBox.action = 'createRoom';
				dataBox.data = {'gameName' : $('#CGR_gameName').val()};
				sendData(dataBox);
				$( this ).dialog( "close" );
			}
		}
	});
	
	$('#btn_createGameRoom').click(function(){
		$("#createGameRoomSetting").dialog("open");
		$('#CGR_gameName').selectmenu({width: 250});
	});
	
	$('#gameList').delegate('a', 'click', function(){
		var roomID = $(this).closest("li").attr("id").split("_")[1];
		dataBox.action = 'joinGameRoom';
		dataBox.data = {'roomID' : roomID, "player" : player};
		sendData(dataBox);
	});
	
	
	$("#btn_sendMessage").click(function() {
		if($('#iwantsay').val().length > 0){
			var now = new Date(Date.now());
			var formatted = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
			
			var msg = escapeHtml($('#iwantsay').val());
			var roomID = $(this).attr('name').split("_")[1];
			$('#iwantsay').val("");
			$('#chatMessage').append("<label class='hostName'>"+player.nickname + " (" +formatted+")</label><label class='message'>"+msg+"</label>");
			dataBox.action = 'sendMessage';
			dataBox.data = {'roomID' : roomID, "player" : player, "msg": msg};
			sendData(dataBox);
			
			$("#chatMessage").animate({ scrollTop:  $('#chatMessage').prop("scrollHeight") }, 500);
		}
		$('textarea').focus();
	});
	
	$('textarea').keypress(function (e) {
		if (e.which == 13) {
			if(!$("#btn_sendMessage").is(":disabled")){
				$('#btn_sendMessage').trigger('click');
				$('textarea').focus();
			}else{
				$('textarea').val("");
				$('textarea').focus();
			}
			
			return false;
		}
	});
});