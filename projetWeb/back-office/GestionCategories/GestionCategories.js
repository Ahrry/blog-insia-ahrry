	function load(){
		$("#divSaisie").hide();
	}	
	
	//---- Drag & drop tableau
	$("#tableau tbody.corps").sortable({ 
		//disabled: false,
		opacity: 0.6,
		cursor: 'move',
		placeholder: 'ui-state-highlight',
		//scroll: true,
		//tolerance: 'pointer',
		
		update : function () { 

			//Sur modification		
			// //récupère les éléments du tableau
			// var tabElement = $('#tableau tbody.corps').sortable('serialize'); 
			// //Traite le tableau dans dragdrop et affiche le résultat dans la div info
			// $("#infoResult").load("GestionCategories_ajax.php?"+tabElement); 
			$.ajax({  
				type: "POST",	//méthod POST
				url: 'GestionCategories_ajax.php', // url de la page charger
				data: $('#tableau tbody.corps').sortable('serialize'), //paramètres à passer en post
				success:function(html){ // si la requêté est un succès
					$("#infoResult").empty(); // on vide le div
					$("#infoResult").append(html); // on met dans le div le résultat de la requete ajax
				},
				error:function(msg){
					alert( "Error !: " + msg );
				}
			});
		} 
	});	
	$("#tableau tbody.corps").disableSelection();

	
	//---- Recherche
	function rechercher(){
		var valRech=$('#recherche').val();
		traitement("recherche",0,valRech)
	};
	
	//---- Ajouter une catégorie
	function ajouter(){
		$('#infoResult').text('');
		$('#infoErreur').text('');
		$("#textCategorie").val("");
		$("#divSaisie").slideDown("normal");
		$("#action").val("ajouter"); //spécifie l'action dans un hidden
		$("#svgId").val("0"); 
	};	

	//---- Modifier une catégorie
	function modifier(id,libelle){
		$('#infoResult').text('');
		$('#infoErreur').text('');
		$("#textCategorie").val(libelle); //pré rempli la textBox
		$("#divSaisie").slideDown("normal");
		$("#action").val("modifier"); //spécifie l'action dans un hidden		
		$("#svgId").val(id); //renseigne l'id de la catégorie		
	};	
	
	//---- Valider le formulaire
	//$("#btnvalider").click(function(e){
	function valider(){
		var action = $("#action").val();
		var valeur = $("#textCategorie").val();
		var id = $("#svgId").val();
		controle(action,id,valeur);	
	}
	//});
	//---- Annuler le formulaire
	$("#btnannuler").click(function(e){
		$("#divSaisie").slideUp("normal");
	});
	
	//---- Gestion des traitements
	function controle(action,id,valeur){
		// ajax avec jquery
		$.ajax({  
			type: "POST",	//méthod POST
			url: 'GestionCategories_ajax.php', // url de la page charger
			data: 'controle='+action+'&id='+id+'&valeur='+valeur, //paramètres à passer en post
			success:function(html){ // si la requêté est un succès
				$("#infoErreur").empty(); // on vide le div
				$("#infoErreur").append(html); // on met dans le div le résultat de la requete ajax
			},
			error:function(msg){
				alert( "Error !: " + msg );
			}
		});
	}
	
	//---- Gestion des traitements
	function traitement(action,id,valeur){
		if(valeur==undefined){valeur='';} //paramètre facultatif
		//Remize à zéro
		$('#infoResult').text('');
		$('#infoErreur').text('');
		//on récupère le nombre de ligne
		if (action=="supprimer"){		
			if(!confirm("Voulez vous supprimer la catégorie (Libellé: "+valeur+")?")){
				return;
			}
		}		
		// ajax avec jquery
		$.ajax({  
			type: "POST",	//méthod POST
			url: 'GestionCategories_ajax.php', // url de la page charger
			data: 'action='+action+'&id='+id+'&valeur='+valeur, //paramètres à passer en post
			success:function(html){ // si la requêté est un succès
				$("#lescategories").empty(); // on vide le div
				$("#lescategories").append(html); // on met dans le div le résultat de la requete ajax
				
				var message=''
				//message de confirmation
				if(action=="valider"){
					message="Validation effectuée avec succès!";					
				}else if(action=="supprimer"){
					message="Suppression effectuée avec succès!";					
				}else if(action=="modifier"){
					message="Modification effectuée avec succès!";					
				}else if(action=="masquer"){
					message="Article masqué!";					
				}else if(action=="ajouter"){
					message="Article ajouté!";					
				}
				$('#infoResult').text(message);
				$("#divSaisie").slideUp("normal"); //Cache le formulaire si succès
			},
			error:function(msg){
				alert( "Error !: " + msg );
			}
		});
	}