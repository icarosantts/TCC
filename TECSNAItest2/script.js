document.querySelector('form').addEventListener('submit', function(event) {

    var email= document.getElementById('email').value;
    var senha= document.getElementById('senha').value;

    if( email=== '' || senha=== ''){
        alert('Por favor, preencha todos os campos.');
        event.preventDefault();
     } else{
        form.reset();
     }
});