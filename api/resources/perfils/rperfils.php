<?
$reuter->get('perfils' , [], 'get');
$reuter->get('perfils' , ['id'], 'get');
$reuter->post('perfils', [], 'save' ); 
$reuter->delete('perfils', ['id'], 'delete');
$reuter->put('perfils' , [], 'save');
?>