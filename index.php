<?php
require 'vendor/autoload.php';
date_default_timezone_set('America/Fortaleza');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
//
 $log = new Logger('name');
 $log->pushHandler(new StreamHandler('app.log', Logger::WARNING));
 $log->addWarning('Oh Noes');

$app = new \Slim\Slim( array(
  'view' => new \Slim\Views\Twig()
));

$app->add(new \Slim\Middleware\SessionCookie());

$view = $app->view();
$view->parserOptions = array(
    'debug' => true
);
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);

$app->get('/',function() use($app){
  $app->render('main.twig');
})->name('home');


$app->post('/contact',function() use($app){
  $name = $app->request->post('name');
  $email = $app->request->post('email');
  $tel = $app->request->post('tel');
  $nasc = $app->request->post('nasc');
  $cpf = $app->request->post('cpf');
  $garagr = $app->request->post('garagr');
  $garag = $app->request->post('garag');
  $garagt = $app->request->post('garagt');
  $estc = $app->request->post('estc');
  $end = $app->request->post('end');
  $cep = $app->request->post('cep');
  $prof = $app->request->post('prof');
  $carro = $app->request->post('carro');
  $placa = $app->request->post('placa');
  $chassi = $app->request->post('chassi');
  $cambio = $app->request->post('cambio');
  $blin = $app->request->post('blin');


  if(!empty($name) && !empty($email) && !empty($tel) && !empty($nasc) && !empty($cpf) && !empty($garagr) && !empty($garagt) &&
    !empty($estc) && !empty($end) && !empty($cep) && !empty($prof) && !empty($carro) && !empty($placa) &&
    !empty($chassi) && !empty($cambio) && !empty($blin)) {
    $cleanName = filter_var($name, FILTER_SANITIZE_STRING);
    $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
    $cleanTel = filter_var($tel, FILTER_SANITIZE_STRING);
    $cleanNasc = filter_var($nasc, FILTER_SANITIZE_STRING);
    $cleanCpf = filter_var($cpf, FILTER_SANITIZE_STRING);
    $cleanGaragr = filter_var($garagr, FILTER_SANITIZE_STRING);
    $cleanGaragt = filter_var($garagt, FILTER_SANITIZE_STRING);
    $cleanEstc = filter_var($estc, FILTER_SANITIZE_STRING);
    $cleanEnd = filter_var($end, FILTER_SANITIZE_STRING);
    $cleanCep = filter_var($cep, FILTER_SANITIZE_STRING);
    $cleanProf = filter_var($prof, FILTER_SANITIZE_STRING);
    $cleanCarro = filter_var($carro, FILTER_SANITIZE_STRING);
    $cleanPlaca = filter_var($placa, FILTER_SANITIZE_STRING);
    $cleanChassi = filter_var($chassi, FILTER_SANITIZE_STRING);
    $cleanCambio = filter_var($cambio, FILTER_SANITIZE_STRING);
    $cleanBlin = filter_var($blin, FILTER_SANITIZE_STRING);

  } else {
    //message the user that there was a problem
    $app->flash('danger', 'Todos os campos são necessários.');
    $app->redirect('/');
  }

  $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 587,'tls')
    ->setUsername('email@gmail.com')
    ->setPassword('senha');
    $mailer = \Swift_Mailer::newInstance($transport);

  $message = \Swift_Message::newInstance();
  $message->setSubject('Cotação para ' . $name);
  $message->setFrom(array(
     $cleanEmail => $cleanName
  ));
  $message->setTo(array('email@mail.com'));
  $cleanMsg='Nome: ' . $cleanName .
            '<br/> e-mail:' . $cleanEmail .
            '<br/> Telefone: ' . $cleanTel .
            '<br/> Data de nascimento: '. $cleanNasc .
            '<br/> CPF: ' . $cleanCpf .
            '<br/> Tem garagem na redidência? ' . $cleanGaragr.
            '<br/> Tem garagem no trabalho? ' . $cleanGaragt .
            '<br/> Estado civil: ' . $cleanEstc .
            '<br/> Endereço: ' . $cleanEnd .
            '<br/> CEP: ' . $cleanCep .
            '<br/> Profissão: ' . $cleanProf .
            '<br/> Veículo: ' . $cleanCarro .
            '<br/> Placa: '. $cleanPlaca .
            '<br/> Chassi: ' . $cleanChassi .
            '<br/> Cambio automático? ' . $cleanCambio .
            '<br/> Bindado? ' . $cleanBlin;
  $message->setBody($cleanMsg);
  $message->setContentType('text/html');

  $result = $mailer->send($message);

  if($result > 0) {
    // send a message that says thank you.
    $app->flash('success', 'Seus dados foram enviados com sucesso! Em breve enviaremos o melhor preço para seu seguro.');
    $app->redirect('/');

  } else {
    // send a message to the user that the message failed to send
    // log that there was an error
    $app->flash('danger', 'Ocorreu um erro, favor entrar em contato (85)98805-5171.');
    $app->redirect('/');
  }

});

$app->run();

?>
