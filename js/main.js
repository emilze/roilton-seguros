$(document).ready(function(){
  $('#submit-form').click(function(event){
    $('input[class="fail"]').removeClass('fail');
    var fields = ['name', 'email', 'tel', 'nasc', 'cpf', 'estc', 'end','cep','prof','carro','placa','chassi'];
    var hasErrors = false;
    for (var i in fields) {
      var value = fields[i];
      var field = $("input[name='" + value + "']").val();
      if (field == "") {
        hasErrors = true;
        $("input[name='" + value + "']").addClass('fail');
      }
    }

    if (hasErrors) {
      alert('Por favor preencha todos os camppos em vermelho e tente novamente.');
      event.preventDefault();
      return false;
    }

  });
});
