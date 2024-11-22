<?php
if (!function_exists('resize_image')) {
    function resize_image($file, $target_width, $target_height) {
        list($original_width, $original_height) = getimagesize($file);
        $image_resource = imagecreatefromstring(file_get_contents($file));
        
        if ($original_height > $original_width) {
            // Rotaciona a imagem em 90 graus se a altura for maior que a largura
            $image_resource = imagerotate($image_resource, 90, 0);
            // Atualiza as dimensões originais após a rotação
            list($original_width, $original_height) = [$original_height, $original_width];
        }

        $ratio_orig = $original_width / $original_height;

        // Calcula nova largura e altura para preencher completamente a área de destino
        if ($target_width / $target_height > $ratio_orig) {
            $new_width = $target_width;
            $new_height = $target_width / $ratio_orig;
        } else {
            $new_height = $target_height;
            $new_width = $target_height * $ratio_orig;
        }

        // Converte os valores para inteiros
        $new_width = round($new_width);
        $new_height = round($new_height);

        // Cria a imagem redimensionada
        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $image_resource, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

        // Cria a imagem final com o tamanho desejado e corta o excesso
        $final_image = imagecreatetruecolor($target_width, $target_height);
        $x_offset = round(($new_width - $target_width) / 2);
        $y_offset = round(($new_height - $target_height) / 2);
        imagecopy($final_image, $new_image, 0, 0, $x_offset, $y_offset, $target_width, $target_height);

        // Salva a imagem final
        imagejpeg($final_image, $file, 90);
        imagedestroy($new_image);
        imagedestroy($final_image);
        imagedestroy($image_resource);
    }
}
?>
