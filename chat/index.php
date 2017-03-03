<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="css/chat.css?<?=uniqid()?>">
<body>
	<div class="container-fluid">
		<div id="chat" class="" width="100%">
			
		</div>
	</div>
</body>

<script type="text/javascript" src='https://code.jquery.com/jquery-3.1.1.min.js'></script>
<script type="text/javascript" src='../php.js?<?=uniqid()?>'></script>
<script type="text/javascript">
	
	var phpjs = new $PHP('chat');
	phpjs.loaded = Init;

	function Init(){

		if( localStorage.getItem('chat') ){

			phpjs.chatname = localStorage.getItem('chat');
			phpjs.username = localStorage.getItem('username');

			$('#chat').load('Messages.php',function(){

				checkMessage( true );

				$('#send').unbind('click').click(function(){
					phpjs.call('send',[$('#message').val()],function(){
						$('#message').val('')
						//checkMessage();
						ApllyJs();
					})
				})			

			});

		} else {

			$('#chat').load('DefineChat.php',function(){

				ApllyJs()

			});

		}

	}


	function ApllyJs(){

		$('form').unbind('submit').submit(function(){

			var action = $(this).attr('action');

			phpjs.call( action , [$(this).serializeArray()] , function(){
				
				if( phpjs.msg ){
					alert( phpjs.msg )	
				} else {
					if( phpjs.chatname ){
						localStorage.setItem('chat',phpjs.chatname);
						localStorage.setItem('username',phpjs.username);
						location.reload();
					}
				}
				
			})

			return false;
		})

		$('#exit').unbind('click').click(function(){
			phpjs.call( 'exit' , [] , function(){
				
				localStorage.clear();
				location.reload();
				
			})
			
		})

		$('#message').unbind('keydown').keydown(function( e ){
			if(e.keyCode == 13){
		        $('#send').click();
		    }
		})

	}

	function checkMessage( refresh ){
		phpjs.call('messages',[],function(){
			$('.panel-body.messages').html('')
			if( phpjs.data ){

				var div = '';

				for( var i in phpjs.data ){

					div = '';					
					div = [];
					for( var c in phpjs.data[i] ){
						if( !phpjs.data[i][c] ) phpjs.data[i][c] = '';
						div.push("<div class='c"+(c)+"'>"+phpjs.data[i][c]+"</div>")
					}

					$('.panel-body.messages').append("<div>"+div.join('')+"</div>")
				}
			}
			
			if( refresh ){
				setTimeout( function(){
					checkMessage( true );
				}, 2000 )
			}

			ApllyJs();
			
		})
	}
	
</script>


