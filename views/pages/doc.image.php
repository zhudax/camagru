<?php
function ft_resize($path_in, $path_out, $alpha)
{
    $image = imagecreatefrompng($path_in);
    list($width, $height) = getimagesize($path_in);
    $new_width = $width * $alpha;
    $new_height = $height * $alpha;
    $image_new = imagecreatetruecolor($new_width, $new_height);
    $int_color = imagecolorallocatealpha($image_new, 0, 0, 0, 127);
    imagefill($image_new, 0, 0, $int_color);
    imagecopyresampled($image_new, $image, 0, 0, 0, 0,
        $new_width, $new_height, $width, $height);
    imagealphablending($image_new, false);
    imagesavealpha($image_new, true);
    imagepng($image_new, $path_out);
}

function ft_fusion($dst_img, $src_img, $x, $y, $path_out)
{
    $image_dst = imagecreatefrompng($dst_img);
    $image_src = imagecreatefrompng($src_img);
    list($width, $height) = getimagesize($src_img);
    imagecopyresampled($image_dst, $image_src, $x, $y, 0, 0,
        $width, $height, $width, $height);
    imagepng($image_dst, $path_out);
}


ft_resize('./charizard.png', './charizard1.png', 0.1);
ft_resize('./lunutte.png', './lunette.png', 0.1);
ft_resize('./minun.png', './minun.png', 0.1);


// ft_resize('./pikachu.png', './new_pikachu.png', 0.1);
// ft_fusion('./zxu.png', './new_pikachu.png', 100, 100, './final.png');
//
// $path_1 = './lunette.png';
// $path_2 = './zxu_1.png';
// $percent = 0.1;
// //creer new image
// $image_1 = imagecreatefrompng($path_1);
// $image_2 = imagecreatefrompng($path_2);
// //get size of path_1
// list($width, $height) = getimagesize($path_1);
// $new_width = $width * $percent;
// $new_height = $height * $percent;
//
// //creer image noir
// $image_new = imagecreatetruecolor($new_width, $new_height);
// // initialiser les couleurs (image, red, green, blue, alpha 0-127 opaque-transparent)
// $int_color = imagecolorallocatealpha($image_new, 0, 0, 0, 127);
// // remplir l'image à partir de x et y avec les couleurs int_color
// imagefill($image_new, 0, 0, $int_color);
// //copier src dans dst
// //(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_width, dst_height, src_width, src_height)
// imagecopyresampled($image_new, $image_1, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
// // modifie le mode de blending d'une image.
// imagealphablending($image_new, false);
// // sauvegarder les informations du canal alpha.
// imagesavealpha($image_new, true);
// //imagepng($image_new, './lunette2.png');
//
// //var_dump(imagepng($image_2, './merge.png'));
//
// //imagecopyresampled($image_2, $image_new, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
// // envoie une image png vers un navigateur ou un fichier.
// imagepng($image_new, './test.png');
// imagecopyresampled($image_2, $image_new, 150, 60, 0, 0, $new_width, $new_height, $new_width, $new_height);
// imagepng($image_2, 'final.png');
?>
