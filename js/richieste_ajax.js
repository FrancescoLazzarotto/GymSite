//CREATE - richiesta ajax per iscriversi ai corsi - metodo POST - chiamata api: iscrizione_corso.php 

$(document).ready(function() {
    $('#iscriviti').on('click', function() {
        var corsoId = $(this).data('corso-id');

        // Verifica che l'ID del corso sia stato fornito
        if (!corsoId) {
            console.error("ID corso non trovato!");
            return;  
        }

        console.log("ID Corso:", corsoId);
        //richiesta ajax
        $.ajax({
            url: 'http://localhost/api/iscrizione_corso.php',
            type: 'POST',
            contentType: 'application/json',  
            dataType: 'json',
            data: JSON.stringify({ id_corso: corsoId }),
            success: function(response) {
                console.log("Risposta ricevuta dal server:", response);

                //Verifica se la risposta ha successo e gestione messaggi e pop up
                if (response.success) {
                     $('#modal-message').removeClass('error').addClass('success').text(response.message);

                } else {
                    $('#modal-message').removeClass('success').addClass('error').text(response.message);
                }

                $('#modal').fadeIn();
                setTimeout(function() {
                    $('#modal').fadeOut();
                }, 8000);
            },
            error: function(xhr, status, error) {
                console.error('Errore AJAX:', error);

                // Controlla la risposta e gestisci correttamente
                if (xhr.status === 409) {
                    $('#modal-message').removeClass('success').addClass('error').text('Sei già iscritto a questo corso.');
                } else if (xhr.status === 500) {
                    $('#modal-message').removeClass('success').addClass('error').text('Errore interno al server.');
                } else {
                    $('#modal-message').removeClass('success').addClass('error').text('Errore durante l\'iscrizione al corso.');
                }

                $('#modal').fadeIn();
                setTimeout(function() {
                    $('#modal').fadeOut();
                }, 10000);
            }
        });
    });
});




//gestione chiusura del pop up 
// Chiudi il modal quando si clicca sul bottone di chiusura
$('#close-modal').on('click', function() {
    $('#modal').fadeOut();  // Nascondi il modal
});

// Chiudi il modal quando si clicca fuori dal modal
$(window).on('click', function(event) {
    if ($(event.target).is('#modal')) {
        $('#modal').fadeOut();
    }
});


//DELETE - richiesta ajax per disiscriversi dai corsi - metodo DELETE - chiamata api: cancella_corso.php 
$('#disiscriviti').on('click', function() {
    var corsoId_dis = $(this).data('corso-id');
    
    if (typeof corsoId_dis === 'undefined' || corsoId_dis === null) {
        console.error("ID del corso non trovato per la disiscrizione!");
        return;
    }

    console.log("ID Corso per disiscrizione:", corsoId_dis);  //debug
    //Richiesta ajax
    $.ajax({
        url: 'http://localhost/api/cancella_corso.php',
        type: 'DELETE',
        contentType: 'application/json',
        dataType: 'json',
        xhrFields: {
            withCredentials: true //cookies
        },
        data: JSON.stringify({ id_corso: corsoId_dis }),
        success: function(response) {
            $('#modal-message').removeClass('error').addClass('success').text(response.message);
            $('#modal').fadeIn();
            setTimeout(function() {
                $('#modal').fadeOut();
            }, 8000);
        },
        error: function(xhr, status, error) {
            console.error('Errore AJAX:', error);

            let errorMessage = 'Errore durante la disiscrizione dal corso.'; 
            //gestione degli errori per la disiscrizione
            if (xhr.status === 409) {
                errorMessage = 'Non sei iscritto a questo corso, impossibile disiscriversi.';
            } else if (xhr.status === 500) {
                errorMessage = 'Errore interno al server. Riprova più tardi.';
            } else if (xhr.status === 400) {
                errorMessage = 'Richiesta non valida. Controlla i dati inviati.';
            } else if (xhr.status === 401) {
                errorMessage = 'Utente non autenticato. Accedi per continuare.';
            }

            $('#modal-message').removeClass('success').addClass('error').text(errorMessage);
            $('#modal').fadeIn();
            setTimeout(function() {
                $('#modal').fadeOut();
            }, 10000);
        }
    });
});



//READ - richiesta ajax per cercare/leggere  corsi - metodo GET - chiamata api: visualizzazione_corso.php 
$(document).ready(function() {
    $('#ricerca-corsi form').on('submit', function(e) {
        e.preventDefault(); //Impedisce il submit tradizionale del form

        var parola_chiave = $('input[name="parola_chiave"]').val();
        var tipologia = $('select[name="tipologia"]').val();
        var posti_disponibili = $('input[name="partecipanti"]').is(':checked') ? 'true' : 'false';

        //richiesta AJAX
        $.ajax({
            url: 'http://localhost/api/visualizzazione_corso.php',
            type: 'GET',
            data: {
                parola_chiave: parola_chiave,
                tipologia: tipologia,
                posti_disponibili: posti_disponibili
            },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    console.log('Errore: ' + response.error);
                    return;
                }

                var corsi = response; 
                $('.lista-corsi').empty();
                corsi.forEach(function(corso) {
                    var corsoHtml = "<li class='corso'>";
                    corsoHtml += "<strong class='nome-corso'>" + corso.nome + "</strong>";
                    corsoHtml += "<p class='tipo'>TIPOLOGIA CORSO: " + corso.tipologia + "</p>";
                    corsoHtml += "<a class='det' href='paginacorso.php?id=" + corso.id_corso + "'>Dettagli corso</a>";
                    corsoHtml += "</li>";
                    $('.lista-corsi').append(corsoHtml);
                });
            },
            error: function(xhr, status, error) {
                console.error("Errore nella richiesta AJAX: ", status, error);
            }
        });
    });
});