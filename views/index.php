<!DOCTYPE HTML>
<html>
<head>
	<?PHP include_once('init.php'); ?>
	<script>
		$(document).ready(function(){						
			var isPass_account = true;
			var isPass_nickname = true;
			
			$('a[id^=show_]').click(function(){
				var formId = $(this).attr("id").split("_")[1];
				$('div[id^=form_]').each(function(){
					$(this).find('form').trigger('reset');
					$(this).hide();
				});
				$('input[type=text], div input[type=email], div input[type=password]').each(function(){
					$(this).val("");
				});
				clearAllErrMsg();
				$('#form_'+formId).fadeIn("slow");
			});
			
			$( "#dialog" ).dialog({
				modal: true,
				autoOpen: false,
				resizable: false,
				width: 600,
				height: 500,
				show: {
					effect: "blind",
					duration: 1000
				},
				hide: {
					effect: "blind",
					duration: 500
				},
				buttons: {
					"確認": function() {
						$('input[name=termsOfService]').attr("checked","checked");
						$( this ).dialog( "close" );
					}
				}
			});
			
			$('#termsOfserv').click(function(){
				$('#dialog').dialog( "open" );
			});
			
			$('#btn_resetForm').click(function(){
				clearAllErrMsg();
				$('#form_registe').find('form').trigger("reset");
			});
			
			$('#btn_registeSubmit').click(function(){
				var thisForm = $('#form_registe').find('form');
				var data = thisForm.toObject();
				var isPass = true;
				
				if(!$('input[name=termsOfService]').is(':checked')){
					setErrMsg('registe', '請瀏覽並勾選同意『服務條款』。');
					isPass = false
				}
				if(data.passwordCheck != data.password){
					setErrMsg('registe', '兩次密碼輸入不相符。');
					isPass = false;
				}
				if(!validatePassword(data.password)){
					setErrMsg('registe', '密碼請以英文字母開頭，長度為6~15個英數字。');
					isPass = false;
				}
				if(!validateEmail(data.email)){
					setErrMsg('registe', '請確認信箱格式是否正確。');
					isPass = false;
				}
				if(!validateNickname(data.nickname)){
					setErrMsg('registe', '暱稱僅接受中文、英文及數字，長度為3~11個字。');
					isPass = false;
				}
				if(!validateAccount(data.account)){
					setErrMsg('registe', '帳號請以英文字母開頭，長度為3~15個英數字。');
					isPass = false;
				}

				$('#form_registe').find('input').each(function(){
					if($(this).val().length <= 0){
						setErrMsg('registe', '所有欄位皆必須填寫。');
						isPass = false;
					}	
				});
				if(!isPass_account){
					setErrMsg('registe', '該帳號已被使用。');
					isPass = false;
				}
				if(!isPass_nickname){
					setErrMsg('registe', '該暱稱已被使用。');
					isPass = false;
				}
				
				if(isPass){
					$('#form_registe').find('form').attr("action","/player/registe").submit();
				}
			});
			
			$('#btnLogin_submit').click(function(){
				var thisForm = $('#form_login').find('form');
				var data = thisForm.toObject();
				var isPass = true;
				if(data.password.length <= 0){
					setErrMsg('login', '請輸入密碼。');
					isPass = false
				}
				
				if(data.account.length <= 0){
					setErrMsg('login', '請輸入帳號。');
					isPass = false;
				}
				
				if(isPass){
					thisForm.attr("action","<?= $config->root ?>player/login").submit();
				}
			});
			
			$('#btn_logout').click(function(){
				$('#form_player').find('form').attr("action","<?= $config->root ?>player/logout").submit();
			});
			
			$('#btn_newPasswordApply').click(function(){
				$('#form_forgetPassword').find('form').attr("action","<?= $config->root ?>player/forgetPassword").submit();
			});
			
			$('#form_registe').find("#account").on("blur change", function(){
				if($(this).val().length > 2){
					$.ajax({
						method: "POST",
						url: "<?= $config->root ?>player/isAccountExsist",
						data: { account: $(this).val() }
					}).done(function( msg ) {
						if(msg == "true"){
							setErrMsg('registe', '該帳號已被使用。');
							isPass_account = false;
						}else{
							clearErrMsg('registe');
							isPass_account = true;
						}
					});
				}
			});
			
			$('#form_registe').find("#nickname").on("blur change",function(){
				if($(this).val().length > 2){
					$.ajax({
						method: "POST",
						url: "<?= $config->root ?>player/isNicknameExsist",
						data: { nickname: $(this).val() }
					}).done(function( msg ) {
						if(msg == "true"){
							setErrMsg('registe', '該暱稱已被使用。');
							isPass_nickname = false;
						}else{
							clearErrMsg('registe');
							isPass_nickname = true;
						}
					});
				}
			});
		});
	</script>
	<?PHP
		if(isset($data['show_form']) && isset($data['err_registe'])){
			echo "<script>$(window).on('load', function() {";
			echo "$('#".$data['show_form']."').trigger('click');";
			echo "setErrMsg('registe', '".$data['err_registe']."');";
			echo "});</script>";
		}
		if(isset($data['show_form']) && isset($data['err_forgetPassword'])){
			echo "<script>$(window).on('load', function() {";
			echo "$('#".$data['show_form']."').trigger('click');";
			echo "setErrMsg('forgetPassword', '".$data['err_forgetPassword']."');";
			echo "});</script>";
		}
	?>
</head>
<body>
	<div id="background">
		<?php include_once("header.php") ?>
		<div id="body">
			<div>
				<div>
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
						
						<div id="form_player" <?PHP if(empty($isLogin)){ echo 'style="display: none;"'; }?> >
							<h3>歡迎回來！ <?=$player['nickname']?></h3>
							<form method="post">
							<div>
								<div style="text-align: center;">
									<img id="imgHead" style="width: 150px; height: 150px;" src="<?= $config->imgRoot ?>head/<?=$player['account']?>.jpg" />
									<br />
									<br /> 
									<label><?=$player['email']?></label>
									<br />
									<label id="score_win">勝場：<?=$data['score']['win']?></label><label id="score_lose">敗場：<?=$data['score']['lose']?></label><label id="score_tie">平場：<?=$data['score']['tie']?></label>
								</div>
								<span><a href="<?= $config->root ?>player">編輯基本資料</a></span>
								<span><a id="btn_logout" href="javascript:void(0);">登出</a></span>
								<br />
							</div>
							</form>
						</div>
						
						<div id="form_login" <?PHP if(!empty($isLogin)){ echo 'style="display: none;"'; }?>>
							<form method="post" action="<?= $config->root ?>player/login">
							<h3>會員登入</h3>
							<div>
								<div>
									<label for="account">帳號</label>
									<input type="text" class="form-control" id="account" name="account" value="<?PHP if(isset($_POST['account'])){ echo trim($_POST['account']); } ?>">
									<label for="password">密碼</label>
									<input type="password" class="form-control" id="password" name="password">
								</div>
								<span id='err_login'><?= $data['errMsg'];  ?></span>
								<span><a id="btnLogin_submit" href="javascript:void(0);">登入</a></span>
								<br />
								<a class="a_link" id="show_registe" href="javascript:void(0);" style="float: left; margin-left: 5px;">會員申請</a>
								<a class="a_link" id="show_forgetPassword" href="javascript:void(0);" style="float: right; margin-right: 5px;">忘記密碼</a>
								<br />
							</div>
							</form>
						</div>
												
						<div id="form_registe" style="display: none;">
							<form method="post" action="<?= $config->root ?>player/registe">
							<h3>會員申請</h3>
							<div>
								<div>
									<label for="account">帳號</label>
									<input type="text" class="form-control" id="account" name="account" value="<?PHP if(isset($_POST['account'])){ echo trim($_POST['account']); } ?>">
									<label for="nickName">暱稱</label>
									<input type="text" class="form-control" id="nickname" name="nickname" value="<?PHP if(isset($_POST['nickname'])){ echo trim($_POST['nickname']); } ?>">
									<label for="email">信箱</label>
									<input type="email" class="form-control" id="email" name="email" value="<?PHP if(isset($_POST['email'])){ echo trim($_POST['email']); } ?>">
									<label for="password">密碼</label>
									<input type="password" class="form-control" id="password" name="password">
									<label for="passwordCheck">密碼確認</label>
									<input type="password" class="form-control" id="passwordCheck" name="passwordCheck">
									<label><input type="checkbox" value="agree" name="termsOfService">同意『<a class="a_link" id="termsOfserv" href="javascript:void(0);">服務條款</a>』</label>
								</div>
								<span id='err_registe'><?= $data['err_registe'];  ?></span>
								<span><a id="btn_registeSubmit" href="javascript:void(0);">送出申請</a></span>
								<span><a id="btn_resetForm" href="javascript:void(0);">重新填寫</a></span>
								<br />
								<a class="a_link" id="show_login" href="javascript:void(0);" style="display: block; width: 100%;text-align: center;">回登入頁</a>
							</div>
							</form>
						</div>
						
						<div id="form_forgetPassword" style="display: none;">
							<form method="post" action="<?= $config->root ?>player/forgetPassword">
							<h3>忘記密碼</h3>
							<div>
								<div>
									<label for="account">帳號</label>
									<input type="text" class="form-control" id="account" name="account" value="<?PHP if(isset($_POST['account'])){ trim($_POST['account']); } ?>">
								</div>
								<span id='err_forgetPassword'><?= $data['err_forgetPassword'];  ?></span>
								<span><a id="btn_newPasswordApply" href="javascript:void(0);">申請密碼重置</a></span>
								<br />
								<a class="a_link" id="show_login" href="javascript:void(0);" style="float: left; margin-left: 5px;">回登入頁</a>
								<a class="a_link" id="show_registe" href="javascript:void(0);" style="float: right; margin-right: 5px;">會員申請</a>							
								<br />
							</div>
							</form>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<?php include_once("footer.php"); ?>
	</div>
	
	
	<div id="dialog" title="服務條款">
		<p>請仔細閱讀以下內容後，再確認您同意申請帳號，以保障您的權益，如果您有任何疑問，請立即向我們詢問。</p>
		<p>歡迎您加入成為Killing Time的會員。</p>
		<p>本合約載明使用Killing Time會員系統服務時，應有的權利、義務及所適用的條件與條款等資料，同時記載本公司應負之責任。為保障您的權益，請在註冊成為本公司會員前，詳閱本合約所有內容。</p>
		<p>請您務必詳細閱讀並經三日以上審閱，以保障您的權益。一旦您勾選同意鍵後，即視為您已閱讀並同意遵守，所有合約條款之會員規範（包括：會員系統服務合約、停權管理規章、儲值與使用規範、客服中心內容、公告事項、會員服務申請規範、會員服務申請說明、個人資料暨隱私權保護政策等），以及本合約其後如有任何增刪修改。您於勾選同意鍵時，亦表示願遵守相關法律規定，且相關法律規定如有改時，亦同。另外提醒您，此處之規定隨時可能會變更，敬請定期查詢Killing Time客服中心之公告。如您不同意以下使用者註冊條款，請您立即停止使用本服務。</p>
		<p>如您為無行為能力人（如未滿七歲之未成年人），應由其法定代理人為之；若為限制行為能力人（滿七歲但未滿二十歲），應於您的法定代理人或監護人閱讀、了解並同意本服務條款之所有內容及其後修改變更後，方得使用或繼續使用本服務。另外提醒您，此處之規定隨時可能會變更，敬請定期查詢Killing Time客服中心之公告。</p>
		<p>本契約條款未記載主管機關公告應記載事項者，視為已記載。記載主管機關公告不得記載事項者，視為無記載。</p>
		<strong>
		一、認知與接受條款
		</strong>
		<p>您了解您於Killing Time註冊成為會員後，可使用Killing Time會員服務。當您使用Killing Time服務時即表示您同意接受Killing Time會員規範及所有注意事項之約束，並須遵守當地之法律規定。</p>
		<p>立約人雙方當事人及其基本資料：</p>
		<p>1. Killing Time台灣競舞娛樂有限公司（以下簡稱「甲方」）係指利用網路提供線上服務之人，代表人：
		馬禮倩，聯絡電話：(02)7735-2000、客服電子郵件service.tw@Killing Time.com，網址：http://www.Killing Time.tw，地址：臺北市信義區菸廠路88號10樓，統一編號：25108719。</p>
		<p>2. 茲因Killing Time會員服務提供事宜，會員系統服務使用者（以下簡稱「乙方」），請於甲方所提供之註冊頁面填寫個人資料。</p>
		<p>上述甲方與乙方訂立本服務提供合約（以下稱「本合約」）雙方合意訂定條款如下：</p>
		<p>■名詞定義■</p>
		<p>以下名詞定義適用於本合約與會員規範（包括會員系統服務合約、停權管理規章、儲值與使用規範、客服中心內容、公告事項、會員服務申請規範、會員服務申請說明、個人資料暨隱私權保護政策等）中：</p>
		<p>1. 遊戲網站：係指由甲方為提供乙方登入或遊戲服務所建置之網站。</p>
		<p>2. Killing Time：係指甲方於遊戲網站內標示為「Killing Time」所含會員服務或線上遊戲之統稱。</p>
		<p>3. Killing Time id：係指乙方登入Killing Time服務時輸入之特定帳號。</p>
		<p>4. Killing Time uid：係指乙方於甲方註冊並認證成功後，乙方所持有之唯一會員數字序號。</p>
		<p>5. 儲值：係指乙方利用甲方所提供之付費方式，向甲方購買限定使用於Killing Time中「貝殼幣」之行為，若將「貝殼幣」移轉為各遊戲內部之其他點數（點數之名稱依甲方於儲值中心及各遊戲官網公告為準）即為已消費之行為。</p>
		<p>6. 貝殼幣：係指乙方利用甲方所提供之付費方式所換取作為支付甲方服務對價之點數名稱。</p>
		<p>7. 線上遊戲：係指乙方以其設備透過網路傳輸的方式，於Killing Time內始得操作、進行之電腦遊戲軟體。</p>
		<p>8. 外掛程式：除甲方提供之線上遊戲程式、服務程式與乙方電腦設備之作業系統程式、輔助作業系統運作之相關程式、連接網際網路所需之必要程式外，非甲方所提供或影響甲方線上遊戲程式、服務程式運作或影響封包傳輸之程式。（包括但不限於以下：按鍵精靈／連點程式／自動練功／開圖程式／輔助程式等。）</p>
		<p>9. 管理員：擔任維護遊戲進行之順暢、公平與提供遊戲操作問題解答或其他會員服務相關事項之人員。</p>
		<p>10.封包傳輸：資料在網路上傳輸時，需分割為數個區塊，每一個封包內含部份傳輸資料與表頭資料、封包型態、送件者與收件者的位址等資料。</p>
		<p>封包首先會傳送到閘道電腦，閘道閱讀目的位址後，將封包傳送到鄰近閘道，如此反覆進行，直到一閘道認得封包所屬的電腦，將該封包直接送達目的位址。</p>
		<p>11.遊戲數據：乙方於Killing Time與其他使用者競技下之成果（包括但不限於積分、虛擬貨幣與其他）。</p>
		<p>12.下載相關軟體：係指由網路下載遊戲套件、服務程式之行為。</p>
		<p>13.遊戲暱稱：係指乙方於Killing Time提供之線上遊戲中自行設定的角色名稱，不得任意更改。</p>
		<p>14.永久停權：係指甲方永久禁止乙方使用Killing Time會員服務，並代表雙方已無條件終止合約。</p>
		<p>15.必要成本：係指甲方為履行本合約之特定服務而已支出之成本，或已給付予第三人之費用。</p>
		<p>16.扣除必要成本：係指扣除依最新比值轉換新台幣後的未使用貝殼幣總額30%之計算方式。</p>
		<p>17.禁言處分：係指甲方於一定時間中暫停乙方於Killing Time遊戲內、服務程式內輸入文字顯示之權利。</p>
		<p>18.遊戲歷程：係指乙方進行線上遊戲時，甲方系統可得提供之電磁記錄。</p>
		<p>19.遊戲相關管理規範：係指由甲方訂立，專供為規定Killing Time提供之線上遊戲進行方式、會員行為等相關條款。</p>
		<p>20.遊戲套件：係指具備主、副程式，且能完整執行線上遊戲、服務程式所有功能之軟體。</p>
		<p>21.會員系統服務合約：係指包括但不限於會員系統服務合約、停權管理規章、儲值與使用規範、客服中心內容、公告事項、會員服務申請規範、個人資料暨隱私權保護政策等。</p>
		<p>22.暫停遊戲權限處分：係指甲方以包括但不限於限制登入Killing Time、限制進入某遊戲房間、限制進入某遊戲頻道、扣除一定幅度之遊戲數據、扣除虛擬物品、刪除角色等方式，暫時禁止乙方使用Killing Time會員服務之行為。</p>
		<p>23.程式漏洞（BUG）：非甲方設計遊戲程式時所預見或規劃之遊戲呈現方式、操作模式或所表現之遊戲結果。</p>
		<p>24.聊聊：係指由甲方為提供乙方之語音溝通、群組互動、聊天室功能之套件。</p>
		<p>25.公共頻道：係指經甲方核准認定後之聊聊公開聊天室。</p>
		<p>26.禁頻處分：係指於一定時間中暫停乙方進入Killing Time聊聊特定之公共頻道權利。</p>
		<p>■條款內容■</p>
		<p>1.乙方了解按下「我同意」之按鍵，即表示乙方已詳細審閱本合約所有條款達三日以上，並同意遵守所有合約條款之會員規範（包括：會員系統服務合約、停權管理規章、儲值與使用規範、客服中心內容、公告事項、個別服務申請規範、個人資料暨隱私權保護政策等），接受本合約任何之增刪修改，並願遵守相關法律規定。</p>
		<p>2. 遊戲服務之申請，若乙方為無行為能力人（如未滿七歲之未成年人），應由其法定代理人為之；若為限制行為能力人（滿七歲但未滿二十歲），應於您的法定代理人或監護人閱讀、了解並同意本服務條款之所有內容及其後修改變更後，方得使用或繼續使用本服務。</p>
		<p>3. 以下視為本合約之一部分，與本合約具有相同之效力：</p>
		<p>　a.甲方有關服務之公告、活動規定。
		　b.費率表、遊戲相關管理規範、客服中心內容、公告事項、個別服務申請規範等。
		　c.甲方有關本服務之廣告或宣傳內容。</p>
		<p>4. 為促進兒童及少年身心健全發展，並保障其相關權益，甲方應於各產品包裝及各遊戲官網標明遊戲分級標誌及適用年齡層，乙方了解按下「我同意」之按鍵，即表示乙方已符合相關法令對使用本級別之服務的年齡要求。</p>
		<p>5. 乙方於合約審閱期過後初次註冊帳號，進入顯示本合約條款之網頁，並按下「我同意」之按鍵後，即推定乙方同意本合約條款之所有規定。</p>
		<strong>
		二、服務範圍
		</strong>
		<p>Killing Time服務範圍係由甲方提供網路伺服器，供乙方透過網際網路連線登入進行本服務。此不包括乙方向網際網路接取服務業者申請接取網際網路之服務，乙方必須自行配備上網所需之各項電腦設備，以及負擔接上網際網路之費用及電話費用。</p>
		<strong>
		三、會員責任與義務
		</strong>
		<p>■真實登錄義務■</p>
		<p>1. 若乙方同意並需要使用甲方服務的所有細項，必須經過甲方制定之系統認證，否則甲方無須提供完整的服務內容。</p>
		<p>2. 乙方於註冊時須提供完整詳實且符合真實之個人資料，且乙方所登錄資料事後有變更時，應隨時於線上更新或利用會員服務申請變更之。若乙方故意登載不實之個人資料即構成違約事由，甲方得無條件永久停止其會員帳號使用權，且無須負擔任何損害賠償責任。如因乙方虛偽登載導致甲方損失，甲方得索取損害賠償及懲罰性違約金，同時乙方應自負相關民刑事責任（包括但不限於刑法偽變造文書、詐欺、妨礙電磁紀錄等相關罪章）。</p>
		<p>3. 如乙方提供之個人資料有填寫不實，或原所登錄之資料已不符合真實而未更新，或有任何其他誤導之嫌，於乙方提供真實資料或更新資料前，甲方保留隨時暫停乙方使用各項服務之權利（包含但不限於暫停乙方遊戲進行及遊戲歷程查詢之服務）。</p>
		<p>■服務使用之責任與義務■</p>
		<p>1. 乙方不得利用本會員服務進行任何商業行為，如濫發廣告郵件、宣傳性商業網站等。</p>
		<p>2. 除了遵守本服務條款外，乙方同意遵守網際網路國際使用慣例與禮節之相關規定。</p>
		<p>3. 乙方不得利用本服務傳送、發表涉及辱罵、毀謗、不雅、淫穢、攻擊性之文章或圖片。</p>
		<p>4. 乙方同意必須充分尊重智慧財產權，禁止發表侵害他人各項智慧財產權或其他權利之文字、圖片或任何形式之檔案。</p>
		<p>■帳號保管義務■</p>
		<p>1. 乙方了解開始使用本會員服務，其Killing Time id帳號所有權仍屬甲方所有，乙方僅得依本服務條款之約定使用，不得出租、出借、移轉或讓與給其他之第三人使用。</p>
		<p>2. 乙方應妥善保管帳號與密碼，不得將該組帳號、密碼轉讓、交付、揭露或出借予第三人。因未遵守保管義務而產生之相關民刑事糾紛，須由乙方自行負擔法律責任；就乙方因未將帳號及密碼妥善保管，而導致權益受損或衍生損失，均由乙方自行承受，甲方無法亦不予負責。</p>
		<p>3. 若乙方發現帳號或密碼遭人非法使用，或有任何異常破壞使用安全之情形，應立即通知甲方。惟若因乙方保管疏忽，導致帳號、密碼遭他人非法使用時，甲方毋庸負責處理。</p>
		<p>4. 乙方應定期妥善的管理帳號，並定期更改密碼的設定，且勿隨意下載非法軟體，以避免駭客入侵。因前開事由產生包括但不限於被盜帳號等損失，由乙方自行承擔，甲方無法亦不予負責。</p>
		<p>■資料保密原則■</p>
		<p>1. 為維護乙方自身權益，乙方同意不將帳號與密碼洩露或提供予第三人知悉，或出借或轉讓第三人使用。若有違反，乙方應自行負擔因此所生之相關風險與損失。</p>
		<p>2. 乙方註冊成功自訂密碼後，得於甲方之服務網頁上進行變更。甲方不得主動詢問乙方之密碼。</p>
		<p>3. 對於乙方所登錄或留存之個人資料，乙方同意將個人資料提供於本公司，若有與關係企業異業合作時，甲方應依個人資料保護法第8條規定踐行告知消費者之義務，就已知的第三方企業應先清楚告知乙方，甲方於進行行銷服務時，應提供乙方行使首次行銷拒絕權之機制。除下列情況外，甲方在未獲得乙方同意以前，不對外揭露乙方之姓名、聯絡電話、地址、電子郵件地址及其他依法受保護之個人資料。</p>
		<p>　a.基於法律之規定。
		　b.受司法機關或其他有權機關之要求。
		　c.在緊急情況下為維護其他乙方或第三人之人身安全。</p>
		<p>4. 甲方不會向任何人士或公司提供乙方的個人資料。但在以下的情況下，甲方會向其他人士或公司提供乙方的個人資料：
		需要與甲方之合作夥伴、為甲方執行工作的承包商或供應商，以及其他共用乙方的資料，方能夠提供乙方所要求的產品或服務者。</p>
		<p>■會員儲值責任限制■</p>
		<p>1. 貝殼幣為使用甲方限定付費項目時之對價點數名稱，乙方了解當貝殼幣移轉為遊戲內部之其他點數後，依照本合約第一項名詞解釋第5點，視為已消費，故無法任意取消、更改、退費或轉入其他帳號中。</p>
		<p>2. 若乙方有取消、更改、退費之需求，需使用甲方提供之會員服務方式申請變更或終止合約。</p>
		<p>3. 若乙方使用虛偽不正之方式進行儲值，甲方保留隨時終止乙方會員資格及使用本服務之權利，並將追究相關法律責任。</p>
		<p>■特別約定事項■</p>
		<p>1. 雙方如基於特定事項另行簽訂文件，包括但不限於：協議書、和解契約等（以下簡稱「補充條款」）時，補充條款視為本合約內容之補充，如本合約中部分條款與補充條款相衝突時，補充條款中之約定事項優先本合約適用，補充條款未約定者，適用本合約之相關約定。</p>
		<p>2. 乙方同意與甲方洽商、締結補充條款時，知悉甲方之未公開之營業資訊、營業秘密、機密資訊、活動規劃、商業決策、相關契約內容（包括但不限於補充條款約定事項）等所有資訊及洽商與締結補充條款之過程，均屬應祕密之範疇，未經甲方書面同意，不得直接或間接揭露予任何第三人，或協助第三人取得前述資訊。</p>
		<p>3. 若乙方有違反本條約定之行為，應賠償甲方所付出的相對應成本或損失，包括但不限於訴訟費用、律師費、處理此事件之行政成本等，並支付甲方新台幣拾萬元整之懲罰性違約金。</p>
		<strong>
		四、服務之暫停或中斷
		</strong>
		<p>1. 乙方應了解並同意，本服務可能會出現中斷、故障、硬體損毀、連線不通或駭客入侵等現象。此現象會造成會員使用不便、資料喪失、遭第三人篡改，或其他衍生的經濟損失，均由乙方自行負擔，為此建議乙方宜自行採取防護措施。</p>
		<p>2. 於下列情形發生時，甲方有權停止或中斷提供服務。</p>
		<p>　a.系統設備進行例行性或必要性的維護。
		　b.甲方伺服器的電子通信服務發生狀況或進行維護，導致無法通訊。
		　c.發生突發性之軟硬體設備與電子通信設備故障。
		　d.由於天災等不可抗拒的因素，導致甲方系統無法進行。
		　e.其他不可歸責於甲方所造成服務停止或中斷。</p>
		<p>3. 甲方依本合約之規定負有於提供本服務時，維護其自身電腦系統，符合當時科技或專業水準可合理期待之安全性之責任，如有系統或電磁紀錄遭破壞或異常，應採合理的措施後儘速回復。甲方違反前二項規定，致生乙方損害時，應依乙方之受損害情形，負損害賠償責任，但甲方能證明已盡相當之注意義務者，得依情節減輕其賠償責任。</p>
		<p>4. 甲方電腦系統發生上述第3點所稱情況時，於完成修復並正常運作之前，若有付費使用功能，甲方不得向乙方收取費用。</p>
		<strong>
		五、服務之合約終止與變更
		</strong>
		<p>1. 乙方得隨時向甲方終止本合約，合約終止時，甲方於扣除必要成本後，應於三十日內以現金、信用卡、匯票或掛號寄發支票方式退還乙方所購買但未消費之貝殼幣，惟已消費之貝殼幣無法退回。</p>
		<p>2. 乙方得於開始遊戲後七日內，以電子郵件或書面告知甲方解除本契約，乙方無需說明理由及負擔任何費用。乙方得就未使用之儲值向甲方請求退費。為避免帳號惡意註冊之情事，乙方同意甲方保有拒絕曾申請契約服務終止之身分證字號進行註冊之權力。</p>
		<p>3. 乙方若有下列任一情形時，甲方以書面或電子郵件通知乙方後，有權立即終止本契約。</p>
		<p>　a.以甲方或類似甲方名稱使用本服務者。</p>
		<p>　b.使用中文、英文及數字以外之字元或甲方不允許或不雅文字作為註冊之名稱。</p>
		<p>　c.冒用他人名義申請甲方服務之帳號。</p>
		<p>　d.利用任何系統或工具對甲方遊戲網站或本服務程式之惡意攻擊或破壞者。</p>
		<p>　e.乙方有違反現行法律之行為，或經司法機關查獲任何不法之行為，其他違背法律、命令之行為。</p>
		<p>　f.乙方在遊戲中發表、張貼或傳播任何關於誹謗、攻擊、猥褻、脅迫、騷擾、中傷、粗俗、侵害他人隱私、侵犯他人權
		   利或道德上令人不快的文字、圖片、聲音、影片或其他形式的內容，經甲方認定屬情節重大者。</p>
		<p>　g.乙方透過其他通路儲值，而後拒付款項或如涉及詐欺等違法行為，有任何造成甲方或第三人實質財務損失之情事。</p>
		<p>　h.違反遊戲管理規範或其他本合約內容之任一規定，經甲方認定屬情節重大者。</p>
		<p>　i.乙方創立多重帳號或利用帳號複製遊戲貨幣、貝殼幣或者是洗道具，經甲方認定屬實者。</p>
		<p>　j.乙方於遊戲網站或透過本服務進行、揭露、公開以有價物品或金錢與其他人進行本服務各類虛擬貨幣或虛擬物品之交
		   易、買賣、交換、轉讓、其他類似行為或廣告性宣傳。</p>
		<p>　k.利用外掛程式、病毒程式、本服務之程式漏洞或其他違反公平合理之方式使用本服務者。</p>
		<p>4. 對於乙方的違約行為，甲方得依據會員規範，進行終止合約或採下列方式予以處分：</p>
		<p>　a.視違規輕重給予七天內不等之暫停遊戲權限處分，若有重大違規者，給予永久停權之處分。</p>
		<p>　b.降低遊戲內一定幅度的遊戲數據或虛擬物品，並取回不當得利。</p>
		<p>5. 若甲方採取第4點之處分，係因甲方之事實認定錯誤或無法舉證時，應賠償乙方之損害。</p>
		<strong>
		六、遊戲需求及遊戲套件與軟體之退費
		</strong>
		<p>1. 乙方得於購買甲方提供之遊戲套件、產品包或付費下載相關軟體後七日內，向原購買處之業者，請求全額退費。前項情形，原購買處之業者不處理或無法處理者，由甲方依乙方之請求進行退費。</p>
		<p>2. 進行各遊戲之最低軟硬體需求、遊戲分級標誌及適用年齡層，甲方應明訂於各產品包裝及各遊戲官網。各遊戲維修及開關機時間甲方應公告於各遊戲官網。</p>
		<p>3. 乙方有義務至各遊戲官網確認上揭各該遊戲分級標誌及適用年齡層，是否適用於乙方之個別情況。乙方如為無行為能力人或限制行為能力人時，應由法定代理人或監護人代為確認，並連帶負擔由此行為所生之法律責任。如乙方違反本確認遊戲適用年齡層之義務，甲方保留隨時終止乙方使用本會員服務之權限。
		</p><strong>
		七、申訴權利
		</strong>
		<p>1. 乙方不滿意甲方提供之連線品質、遊戲管理、費用計費、其他相關之服務品質時，得以申訴服務專線或客服中心回報系統，向甲方提出申訴。</p>
		<p>2. 若甲方依「會員系統服務合約」處分乙方，乙方若不服判決結果，得於收到通知之翌日起七日內至甲方之客服中心、申訴服務專線或以電子郵件、書面配合甲方提供之方式提出申訴，甲方應於接獲申訴後之十五日內回覆處理結果。</p>
		<p>3. 甲方應於遊戲網站、遊戲管理規則或服務契約中明訂24小時申訴或服務專線與電子郵件位址。</p>
		<strong>
		八、智慧財產權
		</strong>
		<p>1. 甲方提供之會員服務或遊戲網站上之所有著作及資料、一切相關周邊產品，其著作權、專利權、商標、營業秘密、其他智慧財產權、所有權或其他權利，均為甲方或其權利人所有，除事先經甲方或其權利人之合法授權外，乙方不得擅自重製、傳輸、改作、編輯或以其他任何形式、基於任何目的加以使用，否則應負擔相關法律責任。</p>
		<p>2. 甲方所擁有各遊戲代理營運權之特定區域，應分別明訂於各產品包裝及各遊戲官網，乙方應詳加注意相關特定區域限制，並應遵守之。</p>
		<p>3. 乙方不得採用任何方法對其提供經營 (或主機)服務、中間媒介服務，或對其進行攔截、模擬或重定向。這些被禁止的方法包括但不僅限於架設私人伺服器、逆向工程、修改本遊戲，或添加新的組件，或使用某種工具程式來提供遊戲經營(或主機)服務。</p>
		<strong>
		九、遵守中華民國法令
		</strong>
		<p>乙方必須遵守使用本服務的所有相關中華民國法令。若乙方的行為違反本合約或其他法令，乙方同意甲方得以書面或電子郵件通知乙方後，隨時停止帳號使用權。乙方有違反法律規定之情事，應自負法律責任。</p>
		<p>1. 乙方承諾遵守中華民國相關法規及一切國際網際網路規定與慣例。若使用甲方台灣地區以外之網站，同意遵守各該網站當地之法令及網路慣例。</p>
		<p>2. 乙方同意並保證不公布或傳送任何毀謗、不實、威脅、不雅、猥褻、不法、攻擊性、毀謗性或侵害他人智慧財產權的文字，圖片或任何形式的檔案於甲方的相關網站上。</p>
		<p>3. 乙方同意不會於甲方的相關網站上從事廣告或販賣商品行為。</p>
		<p>4. 乙方同意避免在公眾討論區討論私人事務，發表文章時，應尊重他人的權益及隱私權及其他合法權益。</p>
		<p>5. 乙方同意必須充份尊重著作權，禁止發表侵害他人各項智慧財產權之文字、圖片或任何形式的檔案。</p>
		<strong>
		十、計費標準之變更及其通知相關規定
		</strong>
		<p>1. 費率如有調整時，應自調整生效日起按新費率計收，惟乙方以預付儲值使用本遊戲時，其已預付之儲值點數或有效期限不因新費率調整而減少或縮短。</p>
		<p>2. 費率調整時，甲方應於調整生效日起三十日前，於甲方遊戲網站、遊戲進行中或遊戲起始畫面公告，並以電子郵件通知乙方。</p>
		<p>3. 甲方在各遊戲首頁上有遊戲收費制度的詳細說明，該制度及說明亦為服務合約的一部分。所有的費用是以新台幣為單位，費用採取預付制。</p>
		<p>乙方同意接受本合約後，即表示授權甲方依照收費制度從乙方的帳號中扣取點數。</p>
		<strong>
		十一、本會員服務提供之遊戲應載明之資訊
		</strong>
		<p>甲方應於遊戲官網及遊戲套件包裝上載明以下事項：</p>
		<p>1. 依「遊戲軟體分級管理辦法」規定標示遊戲分級級別及禁止或適合使用之年齡層。</p>
		<p>2. 進行本遊戲之最低軟硬體需求。</p>
		<p>3. 第五項第1、2點及第六項所列之退費權利。</p>
		<p>4. 有提供安全裝置者，其免費或付費資訊。</p>
		<strong>
		十二、帳號與密碼之非法使用通知與處理
		</strong>
		<p>任一方發現第三人非法使用乙方之帳號，或有乙方帳號之使用安全遭異常破壞情形時，應立即通知對方。甲方接獲乙方通知，或甲方通知乙方後，經乙方確認有前述情事，甲方得暫停該組帳號或密碼之使用權限，亦可更換新帳號或密碼予乙方。</p>
		<p>前項情形，甲方應返還乙方已扣除之儲值，或補償相當之遊戲費用，但若可歸責於乙方者，不在此限。</p>
		<strong>
		十三、電磁紀錄被不當移轉時之處理方式
		</strong>
		<p>1. 乙方於甲方操作遊戲所留存之電磁紀錄與甲方其他之電磁紀錄均屬甲方所有，甲方對於所管理之電磁紀錄應努力維持完整。乙方對於電磁紀錄享有受限制的使用權利，使用的範圍概依雙方合約規範。</p>
		<p>2. 乙方如發現帳號、密碼被非法使用，且遊戲電磁紀錄遭不當移轉時，應立即通知甲方查證，甲方應以檢視該IP位址是否為乙方未曾使用過之IP位址此一方式做查證，經甲方以前述方式查證屬實者，甲方得暫時限制相關使用人就本服務之使用權利。</p>
		<p>3. 甲方應於暫時限制遊戲使用權利之時起，即刻以書面或電子郵件通知持有前項電磁紀錄之第三人提出說明。如該第三人未於接獲通知時起七日內提出說明，甲方應直接回復遭不當移轉之電磁紀錄予乙方，如不能回復時可採其他雙方同意之相當補償方式，並於回復後解除對相關使用人之限制；惟甲方有提供免費安全裝置(如防盜卡、電話鎖等)而乙方不為使用者，甲方得直接回復遭不當移轉之電磁紀錄予乙方，不負返還乙方已扣除之儲值，或遊戲費用之責。</p>
		<p>4. 持有本項第1點電磁紀錄之第三人不同意甲方前述之處理，乙方得依報案程序，循司法途徑處理。甲方依本項規定限制乙方之使用權時，在限制使用期間內，甲方不得向乙方收取費用。</p>
		<p>5. 乙方如有申告不實情形導致甲方或其他使用人權利受損時，應負一切法律責任。</p>
		<strong>
		十四、遊戲歷程之保存與查詢
		</strong>
		<p>1. 甲方應保存乙方之個人遊戲歷程紀錄，且保存期間為三十天，以供乙方查詢。如逾電磁紀錄保存期限三十天者，甲方即無義務受理乙方之查詢申請。</p>
		<p>2. 乙方得以書面、網路，或親自至甲方之服務中心申請調閱乙方之個人遊戲歷程，且須提出與身份証明文件相符之個人資料以供查驗，並使用甲方提供之申請方式方可受理。甲方得依政府相關法律規定酌收查詢服務費用280貝殼幣(即新台幣200元整)，並由乙方自行負擔。</p>
		<p>3. 甲方接獲乙方之查詢申請，應提供本項第1點所列之乙方個人遊戲歷程，並於七日內以光碟或磁片等儲存媒介或以書面、電子郵件方式提供資料。</p>
		<strong>
		十五、遊戲相關管理規則訂定原則
		</strong>
		<p>為規範遊戲進行之方式，甲方得訂立合理公平之遊戲相關管理規則，乙方應遵守甲方公告之遊戲相關管理規則、中華民國相關法規及一切國際網際網路之規定與慣例，並對其透過本服務傳輸之一切內容負法律責任。</p>
		<strong>
		十六、違反遊戲相關管理規則之處理
		</strong>
		<p>1. 除本合約另有規定外，有事實足證乙方於本遊戲中違反遊戲相關管理規則時，甲方應於遊戲網站或遊戲登入時公告，並以線上即時通訊或電子郵件等方式通知乙方。經甲方通知改善而未改善，或改善後仍再次違反者，甲方得依遊戲管理規則，按其情節輕重限制乙方之遊戲使用權利。</p>
		<p>2. 甲方依遊戲管理規則暫時停止乙方進行遊戲之權利，每次不得超過七日。</p>
		<p>3. 除構成合約終止事由外，甲方依遊戲管理規則對乙方所為之處置，不得影響乙方依本合約應享之權利。</p>
		<strong>
		十七、帳號安全
		</strong>
		<p>1. 甲乙雙方互負帳號安全義務。甲方應提供乙方各式安全機制，乙方如使用甲方提供之安全機制(如防盜卡、電話鎖)電磁記錄仍遭不當移轉時，經甲方調查屬實，甲方無條件回復為原始狀態。</p>
		<p>2. 任一方發現第三人非法使用乙方之帳號，或有使用安全遭異常破壞之情形時，應立即通知對方。</p>
		<p>3. 乙方應提供正確個人資料申辦會員帳號，並不定期接受甲方查證，如有不實甲方得暫停其使用權限。乙方並應以正確的資料參與各項活動，資料不實者甲方得無條件將取消其資格，且乙方不得事後更正。</p>
		<strong>
		十八、合約之變更及通知
		</strong>
		<p>1. 甲方修改本合約時，應於網站公告之，並以書面或電子郵件通知乙方。乙方於第一項通知到達後七日內：</p>
		<p>　a.乙方未為反對之表示者，視為乙方接受甲方合約變更之內容。</p>
		<p>　b.乙方為反對之表示者，視為乙方對甲方終止本合約之通知。</p>
		<p>2. 乙方應確保留存於甲方之聯絡地址或電子郵件之正確，若有變更，乙方應立即通知甲方。甲方以乙方留存於甲方之聯絡地址或電子郵件為通知之發出時，即視為已合法送達。</p>
		<strong>
		十九、遊戲管理規則及公告
		</strong>
		<p>1. 為確保遊戲進行之秩序，甲方得訂立合理公平之遊戲管理規範或遊戲規則，乙方應遵守甲方公告之遊戲管理規範或遊戲規則。</p>
		<p>2. 遊戲管理規範、公告、服務之廣告或宣傳內容皆視為本合約之一部份，與本合約具有相同效力。</p>
		<p>3. 遊戲管理規則有下列情形之一者，其規定無效：</p>
		<p>　a.牴觸本合約之規定。</p>
		<p>　b.剝奪或限制乙方之合約上權利。但甲方係依第五項第3點、第4點規定處理者，不在此限。</p>
		<strong>
		二十、程式漏洞
		</strong>
		<p>因遊戲程式漏洞致乙方受損時，甲方應依乙方之受損害情形，負損害賠償責任。但甲方證明其無過失者，得減輕其賠償責任。</p>
		<strong>
		二十一、連線品質及補償
		</strong>
		<p>1. 甲方各項系統設備因預先計畫所需之系統維護停機，應於七日前於遊戲網站中公告，且於乙方登入時通知，並於遊戲進行中發佈停機訊息。</p>
		<p>2. 若甲方免費提供服務或遊戲，乙方除負擔網際網路連線費用及配備上網所需之費用外，未支付甲方其他遊戲費用時，甲方僅就故意或重大過失之損害賠償責任。若甲方提供為付費制服務時，對於遊戲中斷或無法連線可歸責甲方時，甲方應返還已扣除之儲值或免收相當之遊戲費用或遞延乙方得進行服務或遊戲之時間。</p>
		<strong>
		二十二、送達
		</strong>
		<p>1. 有關本合約所有事項之通知，乙方同意甲方以乙方登錄之連絡地址或電子郵件地址為送達。</p>
		<p>2. 前項地址或電子郵件地址變更，乙方應即通知甲方，並同意改依變更後之電子郵件地址為送達。</p>
		<p>3. 甲方依乙方提供之電子郵件地址為通知發出後，經通常之傳遞期間，即推定為已送達。</p>
		<p>4. 如乙方怠於連絡地址或電子郵件地址變更或因其他事由致通知無法到達或拒收時，以甲方通知發出時，視為已合法送達。</p>
		<p>5. 因乙方故意或過失致甲方無法為送達者，甲方對乙方因無法送達所致之損害不負任何賠償責任。</p>
		<strong>
		二十三、個別條款之效力
		</strong>
		<p>本合約所定之任何乙方條款之全部或一部分無效時，不影響其他條款之效力。</p>
		<strong>
		二十四、準據法
		</strong>
		<p>本合約與相關遊戲管理規範、遊戲規則之解釋及適用，以及乙方因使用本服務而與甲方間所生之權利義務關係，雙方同意以中華民國法律為準據法。</p>
		<strong>
		二十五、管轄法院
		</strong>
		<p>因本服務合約涉及的一切爭訟，雙方合意以臺灣台北地方法院為第一審管轄法院，但不得排除消費者保護法第四十七條或民事訴訟法第四百三十六條之九規定小額訴訟管轄法院之適用。</p>
	</div>
</body>
</html>