<?php

/**
 * @file
 * Migrate data from molla front-end project.
 */

$molla_path = '/home/kent/WebstormProjects/molla';
$molla_json_path = $molla_path . '/molla-react/api/src/data/demo1.json';
$molla_image_path = $molla_path . '/molla-react/api/src/images';

$image_data_path = 'images/molla/product';
$image_target_path = __DIR__ . '/data/' . $image_data_path;
$csv_target_path = __DIR__ . '/data/products-molla.csv';

$json = json_decode(file_get_contents($molla_json_path), TRUE);
$csv = 'id,stores,title,main_image,detail_images,categories,body' . PHP_EOL;

foreach ($json['products'] as $product_index => $product) {
  $product_index++;
  $images = [];
  foreach ($product['pictures'] as $picture_index => $picture) {
    $picture = $picture['url'];
    $picture_index++;
    $source_path = str_replace('/uploads', $molla_image_path, $picture);
    $image_file_name = $product_index . '-' . $picture_index . '.' . get_suffix($picture);
    $target_path = $image_target_path . '/' . $image_file_name;
    copy($source_path, $target_path);
    $images[] = $image_file_name;
  }

  $images_with_data_path = array_map(function ($image) use ($image_data_path) {
    return $image_data_path . '/' . $image;
  }, $images);
  $detail_images = implode('|', $images_with_data_path);

  $categories = array_map(function ($category) {
    return $category['name'];
  }, $product['category']);
  $categories_str = implode('|', $categories);

  $body = '"' . $product['short_desc'] . '"';
  $csv .= "product${product_index},1,${product['name']},${images_with_data_path[0]},${detail_images},${categories_str},${body}" . PHP_EOL;
}

file_put_contents($csv_target_path, $csv);

/**
 * Get the file name suffix.
 */
function get_suffix(string $file_name): string {
  return pathinfo($file_name, PATHINFO_EXTENSION);
}
