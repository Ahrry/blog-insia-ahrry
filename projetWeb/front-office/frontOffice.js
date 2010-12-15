	function load(){
		//$("#menuArticles").hide();
	}	
		
	function afficher(action,id){
		if(action=="supprimer"){
			if(!confirm("Voulez vous supprimer l\'article? \n(+Suppression des commentaires)")){
				return;
			}
		}
		$("#contentFront").empty(); // on vide le div
		// ajax avec jquery
		$.ajax({  
			type: "POST",	//méthod POST
			url: 'frontOffice_ajax.php', // url de la page charger
			data: 'action='+action+'&id='+id, //paramètres à passer en post
			success:function(html){ // si la requêté est un succès
				if(action=="listeArticles" || action=="supprimer"){
					$("#menuArticles").empty(); // on vide le div
					$("#menuArticles").append(html); // on met dans le div le résultat de la requete ajax
				}else{
					$("#contentFront").empty(); // on vide le div
					$("#contentFront").append(html); // on met dans le div le résultat de la requete ajax
				}
			},
			error:function(msg){
				//alert( "Error !: " + msg );
			}
		});
	}