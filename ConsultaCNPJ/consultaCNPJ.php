<?PHP 
$cnpj = $_GET['cnpj'];
function curlExec($url, $post = NULL, array $header = array()){
 
    //Inicia o cURL
    $ch = curl_init($url);
 
    //Pede o que retorne o resultado como string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
    //Envia cabeçalhos (Caso tenha)
    if(count($header) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
 
    //Envia post (Caso tenha)
    if($post !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
 
    //Ignora certificado SSL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
    //Manda executar a requisição
    $data = curl_exec($ch);
 
    //Fecha a conexão para economizar recursos do servidor
    curl_close($ch);
 
    //Retorna o resultado da requisição
 
    return $data;
}
$teste = curlExec("http://receitaws.com.br/v1/cnpj/".$cnpj);
echo '<strong>Array obtido (bruto)</strong><br/>';
echo $teste;

$obj = json_decode($teste);
echo '<br/><br/><strong>Array obtido (Pré-Tratado)</strong></br>';
//busca o nome
$nome = $obj->nome;
//busca o tipo = Matriz/filial
$tipo = $obj->tipo;
echo '<B>CNPJ</B> - '.$obj->cnpj.'</br>';
echo 'R. Social: '.$nome.' ('.$tipo.')</br>';
echo  'Fantasia: '.$obj->fantasia.'<br/>';

echo 'Situação: '.$obj->situacao.' (Desde '.$obj->abertura.')<br/>';
echo '<br/>'.$obj->logradouro.', '.$obj->numero.' - '.$obj->bairro.' - '.$obj->municipio.'/ '.$obj->uf.' - '.$obj->cep.'<br/>';


//busca a atividade principal
$atividade_principal = $obj->atividade_principal;
echo '<br/><b>Atividade(s) Econômica(s):</b>';
foreach ($atividade_principal as $a) {
   echo "<br/> - $a->text ";//$a->code 
}

//busca as atividades secundárias
$atividades_secundarias = $obj->atividades_secundarias;
echo "<br/><b>Atividades secundárias:</b><br/> ";
foreach ($atividades_secundarias as $a){
   echo "$a->text ($a->code)<br/>";
}
//busca Sócios
$socios = $obj->qsa;
echo "</br>Sócios: ";
foreach ($socios as $a){
   echo "$a->nome ($a->qual) | ";
}
echo 'R$ '.$obj->capital_social.'</br>';


echo '<br/> Nat Jurídica - '.$obj->natureza_juridica
 .'<br/>Status - '.$obj->status
 .'<br/>'.$obj->complemento
 .'<br/>Email - '.$obj->email



//------------------Outros parâmetros que podem ser buscados ----------------------
//busca a data da situaçao
//$data_situacao = $obj->data_situacao;
//echo "<br/>Data de situação: $data_situacao </br>";

/*
 .'<br/>EFR - '.$obj->efr
 .'<br/>MTV - '.$obj->motivo_situacao
 .'<br/>ESP - '.$obj->situacao_especial
 .'<br/>DT ESP - '.$obj->data_situacao_especial
 .'<br/>U ATT - '.$obj->ultima_atualizacao
*/
?>
