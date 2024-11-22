<?php
session_start();
ob_start();

require_once "../_app/conf.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$stmt = $pdo->prepare("SELECT img_path FROM abastecimento_img WHERE img_id = :id");
$stmt->execute(['id' => $dados['img_id']]);
$filename = $stmt->fetchColumn(); // Fetch the single column result

echo $filename;




if ($filename && file_exists('../imagens/' . $filename)) {
  unlink('../imagens/' . $filename);
}


// Delete the record from the database
$stmt = $pdo->prepare("DELETE FROM abastecimento_img WHERE img_id = :id");
$stmt->execute(['id' => $dados['img_id']]);
$count = $stmt->rowCount();


if($count > 0){
    $_SESSION['msg'] = "<div class='trigger accept'>Imagem excluida com sucesso.</div>";
    header("Location: ../editar_abastecimento?id=" . $dados['abast_id']);  
}else{
    $_SESSION['msg'] = "<div class='trigger error'>ERRO: Compra não excluida!</div>";
    header("Location: ../editar_abastecimento?id=" . $dados['abast_id']);  
}




/*

// ID da imagem a ser removida (pode vir de um formulário ou URL)
$imagem_id = $dados['img_id'] ?? null;

if ($imagem_id) {
    // Recupera o caminho da imagem no banco de dados
    $stmt = $pdo->prepare("SELECT img_path FROM abastecimento_img WHERE img_id = :id");
    $stmt->execute(['id' => $imagem_id]);
    $imagem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($imagem) {
        $caminho_imagem = "imagens/".$dados['img_path'];

        // Remove o arquivo físico do servidor
        if (file_exists($caminho_imagem)) {
            unlink($caminho_imagem);
        } else {
            echo "O arquivo não existe no servidor.";
        }

        $deletar = "DELETE FROM abastecimento_img WHERE img_id =:img_id";
        $delete = $pdo->prepare($deletar);
        $delete->bindParam(':img_id', $dados['img_id']);
        $delete->execute();
        $count = $delete->rowCount();

    }

}
    */

?>