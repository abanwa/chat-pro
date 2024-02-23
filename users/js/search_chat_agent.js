$(function(){
	$('#search').keyup(function(event){

		const URLL = "http://localhost/chat_project/users/"
		const search 		= $(this).val();
		const userListDiv 	= $('#onlineUsersList');
		const resultDiv 	= $('.result');
		// alert(search);
		$.post(URLL+'process_chat_agent_search.php', {search: search}, function(data){
				// we will hide our chat div by the nav side
			// alert(data);
			// console.log(data);
			userListDiv.hide();
			resultDiv.html(data);
			resultDiv.removeClass('hidden');
			resultDiv.show();

			if (search === ""){
				resultDiv.hide();
				userListDiv.show();
			}
		});
	});
});