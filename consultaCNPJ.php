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
echo $teste;
?>