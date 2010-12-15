	function load(){
		$("#divSaisie").hide();
		$("#divSupprimer").hide();
	}	
	
	//---- Recherche
	function rechercher(){
		var valRech=$('#recherche').val();
		traitement("recherche",0,valRech,"null")
	};
	
	///////MODIFICATION////////
	//---- Modifier un utilisateur
	function modifier(id,login,email){
		$("#divSupprimer").slideUp("normal"); //cache le formulaire supprimer
		$('#infoResult').text('');
		$('#infoErreur').text('');
		$("#login").val(login); //pré rempli la textBox
		$("#email").val(email); //pré rempli la textBox
		$("#divSaisie").slideDown("normal");
		$("#svgId").val(id); //Garde en mémoire l'id de l'utilisateur		
	};		
	//---- Valider le formulaire de modification
	$("#btnvaliderModif").click(function(e){
		e.preventDefault(); //Stop l'événement submit
		var valLogin = $("#login").val();
		var valEmail = $("#email").val();
		var id = $("#svgId").val();		
		controle("modifier",id,valLogin,valEmail);	
	});	
	//---- Annuler la modification
	$("#btnannulerModif").click(function(e){
		e.preventDefault(); //Stop l'événement submit
		$("#divSaisie").slideUp("normal");
	});
	
	////////SUPPRESSION//////////
	//---- Supprimer un utilisateur
	function supprimer(id,login,email){
		$("#divSaisie").slideUp("normal");
		$('#infoResult').text('');
		$('#infoErreur').text('');
		$('#motif').text('');
		$("#divSupprimer").slideDown("normal");
		$("#svgId").val(id); //Garde en mémoire l'id de l'utilisateur
		$("#svgLogin").val(login); 
		$("#svgEmail").val(email); 		
	};	
	//---- Valider la suppression
	$("#btnvaliderSup").click(function(e){
		e.preventDefault(); //Stop l'événement submit
		var login = $("#svgLogin").val();
		var email = $("#svgEmail").val();
		var id = $("#svgId").val();
		var motif = $("#motif").val();
		
		controle("supprimer",id,login,email,motif);
		$("#divSupprimer").slideUp("normal");
	});	
	//---- Annuler la suppression
	$("#btnannulerSup").click(function(e){
		e.preventDefault(); //Stop l'événement submit
		$("#divSupprimer").slideUp("normal");
	});

	//---- réinitialiser le password d'un utilisateur
	function reinitialiser(id,login,email){
		$("#divSupprimer").slideUp("normal");
		$("#divSaisie").slideUp("normal");
		traitement("reinitialiser",id,login,email)
	};	
	
	//---- Gestion des traitements
	function controle(action,id,login,email,motif){
		if(motif==undefined){motif='';} //paramètre facultatif
		// ajax avec jquery
		$.ajax({  
			type: "POST",	//méthod POST
			url: 'GestionUtilisateurs_ajax.php', // url de la page charger
			data: 'controle='+action+'&id='+id+'&login='+login+'&email='+email+'&motif='+motif, //paramètres à passer en post
			success:function(html){ // si la requêté est un succès
				$("#infoErreur").empty(); // on vide le div
				$("#infoErreur").append(html); // on met dans le div le résultat de la requete ajax
			},
			error:function(msg){
				alert( "Erreur : " + msg );
			}
		});
	}
	
	
	
	//---- Gestion des traitements
	function traitement(action,id,login,email){
		//Remize à zéro
		$('#infoResult').text('');
		$('#infoErreur').text('');
		//on récupère le nombre de ligne
		if (action=="supprimer"){		
			if(!confirm("Voulez vous supprimer l'utilisateur "+login+" ?")){
				return;
			}
		}		
		// ajax avec jquery
		$.ajax({  
			type: "POST",	//méthod POST
			url: 'GestionUtilisateurs_ajax.php', // url de la page charger
			data: 'action='+action+'&id='+id+'&login='+login+'&email='+email, //paramètres à passer en post
			success:function(html){ // si la requêté est un succès
				$("#lesutilisateurs").empty(); // on vide le div
				$("#lesutilisateurs").append(html); // on met dans le div le résultat de la requete ajax
				
				var message=''
				//message de confirmation
				if(action=="valider"){
					message="Validation effectuée avec succès!";					
				}else if(action=="supprimer"){
					message="Suppression effectuée avec succès!";					
				}else if(action=="modifier"){
					message="Modification effectuée avec succès!";					
				}else if(action=="reinitialiser"){
					message="Reinitialisation du password effectuée avec succès!";					
				}
				$('#infoResult').text(message);
				$("#divSaisie").slideUp("normal"); //cache le formualaire si succès
			},
			error:function(msg){
				alert( "Erreur : " + msg );
			}
		});
	}