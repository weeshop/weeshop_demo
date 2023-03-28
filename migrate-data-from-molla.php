<?php

/**
 * @file
 * Migrate data from molla front-end project.
 *
 * Products, product variations, attributes,
 * attribute values, categories, brands.
 */

$molla_path = '/home/kent/WebstormProjects/molla';
$molla_json_path = $molla_path . '/molla-react/api/src/data/demo1.json';
$molla_image_path = $molla_path . '/molla-react/api/src/images';

$image_data_path = 'images/molla/product';
$image_target_path = __DIR__ . '/data/' . $image_data_path;
$product_brands_csv_target_path = __DIR__ . '/data/product_brands.csv';
$product_categories_csv_target_path = __DIR__ . '/data/product_categories.csv';
$product_attribute_values_csv_target_path = __DIR__ . '/data/product_attibute_values.csv';
$products_csv_target_path = __DIR__ . '/data/products.csv';
$product_variations_csv_target_path = __DIR__ . '/data/products.csv';

$json = json_decode(file_get_contents($molla_json_path), TRUE);
$product_brands_csv = 'id,name,path,parent' . PHP_EOL;
$product_categories_csv = 'id,name,path,parent' . PHP_EOL;
$product_attribute_values_csv = 'id,attribute,name' . PHP_EOL;
$products_csv = 'id,stores,title,main_image,detail_images,categories,body' . PHP_EOL;
$product_variations_csv = 'id,product,sku,title,price,attribute_clothes_color,attribute_clothes_size,stock,weight' . PHP_EOL;

$product_brands = $product_categories = $product_attribute_values = [];

foreach ($json['products'] as $product_index => $product) {

  // Obtain product brands.
  foreach ($product['brands'] as $product_brand) {
    $product_brand[$product_brand['slug']] = $product_brand['name'];
  }

  // Obtain product categories.
  foreach ($product['category'] as $product_category) {
    $product_categories[$product_category['slug']] = $product_category['name'];
  }

  // Copy all the pictures.
  $images = [];
  $product_index++;
  foreach ($product['pictures'] as $picture_index => $picture) {
    $picture = $picture['url'];
    $picture_index++;
    $source_path = str_replace('/uploads', $molla_image_path, $picture);
    $image_file_name = $product_index . '-' . $picture_index . '.' . get_suffix($picture);
    $target_path = $image_target_path . '/' . $image_file_name;
    copy($source_path, $target_path);
    $images[] = $image_file_name;
  }

  // Make up products.
  $images_with_data_path = array_map(function ($image) use ($image_data_path) {
    return $image_data_path . '/' . $image;
  }, $images);
  $detail_images = implode('|', $images_with_data_path);

  $categories = array_map(function ($category) {
    return $category['name'];
  }, $product['category']);
  $categories_str = '"' . implode(',', $categories) . '"';

  $body = '"' . $product['short_desc'] . '"';
  $products_csv .= "product${product_index},1,${product['name']},${images_with_data_path[0]},${detail_images},${categories_str},${body}" . PHP_EOL;
}

file_put_contents($product_brands_csv_target_path, $product_brands_csv);
file_put_contents($product_categories_csv_target_path, $product_categories_csv);
file_put_contents($product_attribute_values_csv_target_path, $product_attribute_values_csv);
file_put_contents($products_csv_target_path, $products_csv);
file_put_contents($product_variations_csv_target_path, $product_variations_csv);

/**
 * Get the file name suffix.
 */
function get_suffix(string $file_name): string {
  return pathinfo($file_name, PATHINFO_EXTENSION);
}
