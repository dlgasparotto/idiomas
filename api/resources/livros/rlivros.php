<?
$reuter->get('livros' , [], 'get');
$reuter->get('livros' , ['idlivro','idperfil'], 'getLivroPerfil');

//$reuter->get('livros' , ['idlivro','idlicao'], 'getLicao');

//$reuter->post('perfils', [], 'save' ); 
//$reuter->delete('perfils', ['id'], 'delete');
//$reuter->put('perfils' , [], 'save');
?>