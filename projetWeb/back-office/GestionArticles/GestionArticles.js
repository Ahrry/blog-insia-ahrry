	//---- Drag & drop tableau
	$("#tableau tbody.corps").sortable({ 
		//disabled: false,
		opacity: 0.6,
		cursor: 'move',
		placeholder: 'ui-state-highlight',
		//scroll: true,
		//tolerance: 'pointer',
		
		update : function () {  //Sur modification
			//Remize à zéro
			$('#infoResult').text('');
			
			$.ajax({  
				type: "POST",	//méthod POST
				url: 'GestionAticles_ajax.php', // url de la page charger
				data: $('#tableau tbody.corps').sortable('serialize'), //paramètres à passer en post
				success:function(html){ // si la requêté est un succès
					$("#infoResult").empty(); // on vide le div
					$("#infoResult").append(html); // on met dans le div le résultat de la requete ajax
				},
				error:function(msg){
					alert( "Error !: " + msg );
				}
			});
			// //récupère les éléments du tableau
			// var tabElement = $('#tableau tbody.corps').sortable('serialize'); 
			// //Traite le tableau dans dragdrop et affiche le résultat dans la div info
			// $("#infoResult").load("GestionAticles_ajax.php?"+tabElement); 
		} 
	});	
	$("#tableau tbody.corps").disableSelection();
	
	//---- Sur l'événement click de la recherche
	$("#btnRech").click(function(){
		var valRech=$('#recherche').val();
		choixArticle("recherche",valRech);
	});
	
	//---- Choix d'affichage (valide, non valider, afficher plus) AJAX
	function choixArticle(choix,recherche){
		//Remize à zéro
		$('#infoResult').text('');
		
		//on récupère le nombre de ligne
		var nbLignes = $("#lesarticles").find(".ligneArticle").length;
		var svg = $('#sauvegarde').val();
		if(recherche==undefined){recherche = $('#rech').val();}
		
		// ajax avec jquery
		$.ajax({  
			type: "POST",	//méthod POST
			url: 'GestionAticles_ajax.php', // url de la page charger
			data: 'choix='+choix+'&nbLignes='+nbLignes+'&svg='+svg+'&recherche='+recherche, //paramètres à passer en post
			success:function(html){ // si la requêté est un succès
				$("#lesarticles").empty(); // on vide le div
				$("#lesarticles").append(html); // on met dans le div le résultat de la requete ajax
			},
			error:function(msg){
				//alert( "Error !: " + msg );
			}
		});
	}
	
	//---- Gestion des traitements
	function traitement(action,id,date,titre){
		//Remize à zéro
		$('#infoResult').text('');
		//on récupère le nombre de ligne
		if (action=="supProposition" || action=="supArticle" || action=="supArticleMasque"){
			if(action=="supProposition"){
				var msgConfirm="Voulez vous supprimer la proposition de l\'article (Titre: "+titre+") ?"
			}else if(action=="supArticle" || action=="supArticleMasque"){
				var msgConfirm="Voulez vous supprimer l\'article (Titre: "+titre+") \n(+Suppression des commentaires)?"
			}
			if(!confirm(msgConfirm)){
				return;
			}
		}
		var nbLignes = $("#lesarticles").find(".ligneArticle").length;
		
		// ajax avec jquery
		$.ajax({  
			type: "POST",	//méthod POST
			url: 'GestionAticles_ajax.php', // url de la page charger
			data: 'action='+action+'&id='+id+'&date='+date+'&nbLignes='+nbLignes, //paramètres à passer en post
			success:function(html){ // si la requêté est un succès
				$("#lesarticles").empty(); // on vide le div
				$("#lesarticles").append(html); // on met dans le div le résultat de la requete ajax
				
				var message=''
				//message de confirmation
				if(action=="valider"){
					message="Validation effectuée avec succès! retrouvez l'article dans \"Les articles visibles\"";					
				}else if(action=="supProposition" || action=="supArticle" || action=="supArticleMasque"){
					message="Suppression effectuée avec succès";					
				}else if(action=="masquer"){
					message="Article masqué! retrouvez l'article dans \"Les articles masqués\"";					
				}
				else if(action=="visible"){
					message="Article rendu visible! retrouvez l'article dans \"Les articles visibles\"";					
				}
				$('#infoResult').text(message);
			},
			error:function(msg){
				//alert( "Error !: " + msg );
			}
		});
	}